<?php

namespace TMG\Console\CommandBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use PDO;
use Symfony\Component\DependencyInjection\Container;
use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\State;
use TMG\Api\ApiBundle\Entity\TravelTypes;
use TMG\Api\ApiBundle\Entity\Country;
use TMG\Api\UserBundle\Entity\User;

class MigrateUsersCommand extends ContainerAwareCommand
{
    /** @var  ProgressBar $progress */
    private $progress;
    /** @var  OutputInterface $output */
    private $output;
    /** @var  Container $container */
    private $container;
    /** @var  PDO $pdo */
    private $pdo;
    /** @var  UserManager $userManager */
    private $userManager;
    /** @var  ObjectManager $doctrine */
    private $doctrine;
    /** @var  EntityManager $entityManager */
    private $entityManager;

    protected function configure()
    {
        $this->setName('migrate:users')
            ->setDescription('Get dashboard user information from Matrix DB')
            ->addArgument(
                'user',
                InputArgument::OPTIONAL,
                'Specify user to update'
            )
            ->addOption(
                'all',
                'a',
                InputOption::VALUE_NONE,
                'Update all users regardless of recent activity'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->container = $this->getContainer();
        $this->pdo = $this->getMatrixConnection();
        $this->userManager = $this->container->get('fos_user.user_manager');
        $this->doctrine = $this->container->get('doctrine');
        $this->entityManager = $this->container->get('doctrine')->getEntityManager();

        if ($input->getArgument('user')) {
            $matrixAccounts = $this->getSingleMatrixUser($input->getArgument('user'));
        } elseif ($input->hasParameterOption(['--all', '-a']) === true) {
            $matrixAccounts = $this->getAllMatrixUsers();
        } else {
            $matrixAccounts = $this->getAllRecentMatrixUsers();
        }

        if (count($matrixAccounts) < 1) {
            $this->output->writeln(
                '<error>Unable to find any MyTMG accounts to migrate.</error>'
            );
            exit(1);
        }

        $this->progress = new ProgressBar($this->output, count($matrixAccounts));
        $this->progress->start();

        foreach ($matrixAccounts as $matrixUser) {
            /** @var User $reloadedUser */
            $reloadedUser = $this->getReloadedUser($matrixUser);

            if (!$reloadedUser) {
                $reloadedUser = $this->createReloadedUser($matrixUser);
            }

            $reloadedUser = $this->migrateUserBooks($matrixUser, $reloadedUser);

            $reloadedUser = $this->migrateUserProperties($matrixUser, $reloadedUser);

            $reloadedUser = $this->migrateUserFavorites($matrixUser, $reloadedUser);

            $this->migrateUserTravelTypes($matrixUser, $reloadedUser);

            $this->entityManager->flush();
            $this->entityManager->clear();

            $this->setUserMigratedAt($matrixUser['id']);

            $this->progress->advance();
        }

        $this->progress->finish();
        echo "\n";
    }

    /**
     * @param $matrixUser
     * @param User $reloadedUser
     * @return User
     */
    private function migrateUserBooks($matrixUser, User $reloadedUser)
    {
        $hasBook = [];
        $addBook = [];

        $matrixUserBooks = $this->getMatrixUserBooks($matrixUser['id']);

        if (count($matrixUserBooks) > 0) {
            foreach ($matrixUserBooks as $book) {
                $guideName = $this->getMatrixGuide($book['guide_books_id']);

                /** @var Books $newBook */
                $newBook = $this->doctrine->getRepository('ApiBundle:Books')->findOneBy(
                    ['newsletterName' => $guideName]
                );

                if (!$newBook) {
                    continue;
                }

                if ($reloadedUser->hasBook($newBook)) {
                    array_push($hasBook, $newBook->getId());
                } else {
                    $addBook[] = $newBook;
                }
            }

            $currentBooks = $reloadedUser->getBooks();
            foreach ($currentBooks as $book) {
                if (!in_array($book->getId(), $hasBook)) {
                    $reloadedUser->removeBook($book);
                }
            }

            if (count($addBook) > 0) {
                foreach ($addBook as $book) {
                    $reloadedUser->addBook($book);
                }
            }
        }

        return $reloadedUser;
    }

    /**
     * @param $matrixUser
     * @param User $reloadedUser
     * @return User
     */
    private function migrateUserProperties($matrixUser, User $reloadedUser)
    {
        $hasProperty = [];
        $addProperty = [];

        $matrixUserProperties = $this->getMatrixUserProperties($matrixUser['id']);

        if (count($matrixUserProperties) > 0) {
            foreach ($matrixUserProperties as $property) {
                $hash = $property['properties_id'];

                /** @var Property $newProperty */
                $newProperty = $this->doctrine->getRepository('ApiBundle:Property')->findOneBy(['hash' => $hash]);

                if (!$newProperty) {
                    continue;
                }

                if ($reloadedUser->hasProperty($newProperty)) {
                    array_push($hasProperty, $newProperty->getId());
                } else {
                    $addProperty[] = $newProperty;
                }
            }
        }

        $currentProperties = $reloadedUser->getProperties();

        foreach ($currentProperties as $property) {
            if (!in_array($property->getId(), $hasProperty)) {
                $reloadedUser->removeProperty($property);
            }
        }

        if (count($addProperty) > 0) {
            foreach ($addProperty as $property) {
                $reloadedUser->addProperty($property);
            }
        }

        return $reloadedUser;
    }

    /**
     * @param $matrixUser
     * @param User $reloadedUser
     * @return User
     */
    private function migrateUserFavorites($matrixUser, User $reloadedUser)
    {
        $hasFavorite = [];
        $addFavorite = [];

        $matrixUserFavorites = $this->getMatrixUserFavorites($matrixUser['id']);

        if (count($matrixUserFavorites) > 0) {
            foreach ($matrixUserFavorites as $favorite) {
                $hash = $favorite['properties_id'];

                /** @var Property $newFavorite */
                $newFavorite = $this->doctrine->getRepository('ApiBundle:Property')->findOneBy(['hash' => $hash]);

                if (!$newFavorite) {
                    continue;
                }

                if ($reloadedUser->hasFavorite($newFavorite)) {
                    array_push($hasFavorite, $newFavorite->getId());
                } else {
                    $addFavorite[] = $newFavorite;
                }
            }
        }


        $currentFavorites = $reloadedUser->getFavorites();

        foreach ($currentFavorites as $favorite) {
            if (!in_array($favorite->getId(), $hasFavorite)) {
                $reloadedUser->removeFavorite($favorite);
            }
        }

        if (count($addFavorite) > 0) {
            foreach ($addFavorite as $favorite) {
                $reloadedUser->addFavorite($favorite);
            }
        }

        return $reloadedUser;
    }

    /**
     * @param $matrixUser
     * @param User $reloadedUser
     * @return User
     */
    private function migrateUserTravelTypes($matrixUser, User $reloadedUser)
    {
        $hasTravelType = [];
        $addTravelType = [];

        $matrixUserTravelTypes = $this->getMatrixUserTravelTypes($matrixUser['id']);

        if (count($matrixUserTravelTypes) > 0) {
            foreach ($matrixUserTravelTypes as $travelType) {
                $travelTypeId = $travelType['travel_types_id'];

                /** @var TravelTypes $newTravelType */
                $newTravelType = $this->doctrine->getRepository('ApiBundle:TravelTypes')
                    ->findOneBy(['id' => (int)$travelTypeId]);

                if (!$newTravelType) {
                    continue;
                }

                if ($reloadedUser->hasTravelType($newTravelType)) {
                    array_push($hasTravelType, $newTravelType->getId());
                } else {
                    $addTravelType[] = $newTravelType;
                }
            }
        }

        $currentTravelTypes = $reloadedUser->getTravelTypes();

        foreach ($currentTravelTypes as $travelType) {
            if (!in_array($travelType->getId(), $hasTravelType)) {
                $reloadedUser->removeTravelType($travelType);
            }
        }

        if (count($addTravelType) > 0) {
            foreach ($addTravelType as $travelType) {
                $reloadedUser->addTravelType($travelType);
            }
        }

        return $reloadedUser;
    }

    private function getMatrixConnection()
    {
        $dsn = 'mysql:host='
            .$this->container->getParameter('matrix_db_host')
            .';dbname='
            .$this->container->getParameter('matrix_db_name');

        $pdo = new PDO(
            $dsn,
            $this->container->getParameter('matrix_db_user'),
            $this->container->getParameter('matrix_db_pass')
        );

        return $pdo;
    }

    private function getSingleMatrixUser($user)
    {
        $result = $this->pdo->prepare(
            'SELECT * FROM users
             JOIN oauth_users
             ON users.id = oauth_users.user_id
             AND users.email = :user
             AND users.realm LIKE :realm'
        );

        $result->bindValue(':user', $user);
        $result->bindValue(':realm', '%dashboard%');

        if (!$result->execute()) {
            // There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        $result = $result->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    private function getAllRecentMatrixUsers()
    {
        $result = $this->pdo->prepare(
            'SELECT * FROM users
            JOIN oauth_users
            ON users.id = oauth_users.user_id
            AND (oauth_users.updated_at >= oauth_users.migrated_at OR oauth_users.migrated_at IS NULL)
            AND users.realm LIKE :realm'
        );

        $result->bindValue(':realm', '%dashboard%');

        if (!$result->execute()) {
            //There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getAllMatrixUsers()
    {
        $result = $this->pdo->prepare(
            'SELECT * FROM users
            JOIN oauth_users
            ON users.id = oauth_users.user_id
            AND users.realm LIKE :realm'
        );

        $result->bindValue(':realm', '%dashboard%');

        if (!$result->execute()) {
            //There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function setUserMigratedAt($userId)
    {
        $result = $this->pdo->prepare(
            'UPDATE oauth_users
            SET migrated_at = :now
            WHERE oauth_users.user_id = :user_id'
        );

        $result->bindValue(':now', date('Y-m-d H:i:s'));
        $result->bindValue(':user_id', $userId);

        if (!$result->execute()) {
            //There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }
    }

    private function getReloadedUser($matrixUser)
    {
        $city = '';
        $state = '';
        $postalCode = '';
        $country = '';

        /** @var User $reloadedUser */
        $reloadedUser = $this->userManager->findUserByUsernameOrEmail($matrixUser['email']);

        if (!$reloadedUser) {
            return false;
        }

        $reloadedUser
            ->setFirstName($matrixUser['first_name'])
            ->setLastName($matrixUser['last_name'])
            ->setEnabled(true)
            ->setRoles([$matrixUser['role']])
            ->setTutorial((int)$matrixUser['tutorial'])
            ->setSubscribed((int)$matrixUser['is_subscribed'])
            ->setHouseholdMembers((int)$matrixUser['num_household_members'])
            ->setHouseholdChildren((int)$matrixUser['num_children_in_household'])
            ->setPhone($matrixUser['phone'])
            ->setPassword($matrixUser['password']);

        $reloadedUser->setSubscribed((int)$matrixUser['is_subscribed']);

        if ($matrixUser['zip_code']) {
            $fullZip = null;
            $zip = null;
            $zipParts = explode('-', $matrixUser['zip_code']);

            if (count($zipParts) > 1) {
                $zip = $zipParts[0];
                $fullZip = $matrixUser['zip_code'];
            } else {
                $zip = $matrixUser['zip_code'];
            }

            $postalCode = $this->doctrine->getRepository('ApiBundle:PostalCode')->findOneBy(['code' => $zip]);

            if (!$postalCode) {
                $postalCode = new PostalCode();
                $postalCode->setCode($zip);
                if ($fullZip) {
                    $postalCode->setCodeFull($fullZip);
                }
                $this->entityManager->persist($postalCode);
            } else {
                if ($fullZip) {
                    $postalCode->setCodeFull($fullZip);
                }
            }

            $reloadedUser->setPostalCode($postalCode);
        }

        if ($matrixUser['birthdate']) {
            $reloadedUser->setBirthDate(new \DateTime($matrixUser['birthdate']));
        }

        if ($matrixUser['gender']) {
            if (strtolower($matrixUser['gender']) == 'male') {
                $reloadedUser->setGender('M');
            }
            if (strtolower($matrixUser['gender']) == 'female') {
                $reloadedUser->setGender('F');
            }
        }

        if ($matrixUser['city']) {
            $city = $matrixUser['city'];

            $reloadedUser->setCity($matrixUser['city']);
        }

        if ($matrixUser['country']) {
            /** @var Country $country */
            $country = $this->doctrine->getRepository('ApiBundle:Country')
                ->findOneBy(['id' => $matrixUser['country']]);

            if ($country) {
                $reloadedUser->setCountry($country);
            }
        }

        if (empty($country)) {
            $country = $this->doctrine->getRepository('ApiBundle:Country')
                ->findOneBy(['code' => 'US']);
        }

        if ($matrixUser['province']) {
            /** @var State $state */
            $state = $this->doctrine->getRepository('ApiBundle:State')->findOneBy(
                ['abbreviation' => strtoupper($matrixUser['province'])]
            );

            if (!$state) {
                $state = $this->doctrine->getRepository('ApiBundle:State')->findOneBy(
                    ['name' => ucwords($matrixUser['province'])]
                );
            }

            if (!$state) {
                $state = $this->doctrine->getRepository('ApiBundle:State')->findOneBy(
                    ['slug' => strtolower($matrixUser['province'])]
                );
            }

            if ($state) {
                $reloadedUser->setState($state);
            }
        }

        if ($matrixUser['address']) {
            $address = $this->getAddress(
                $matrixUser['address'],
                $city,
                $state,
                $postalCode,
                $country
            );

            if ($address) {
                $reloadedUser->setAddress($address);
            }
        }

        $this->userManager->updateUser($reloadedUser);

        return $reloadedUser;
    }

    private function createReloadedUser($matrixUser)
    {
        $city = '';
        $state = '';
        $postalCode = '';
        $country = '';

        /** @var User $user */
        $user = $this->userManager->createUser();
        $user->setUsername($this->getUniqueUsername($matrixUser['email']))
            ->setEmail($matrixUser['email'])
            ->setFirstName($matrixUser['first_name'])
            ->setLastName($matrixUser['last_name'])
            ->setEnabled(true)
            ->setRoles([$matrixUser['role']])
            ->setTutorial((int)$matrixUser['tutorial'])
            ->setSubscribed((int)$matrixUser['is_subscribed'])
            ->setHouseholdMembers((int)$matrixUser['num_household_members'])
            ->setHouseholdChildren((int)$matrixUser['num_children_in_household'])
            ->setPhone($matrixUser['phone'])
            ->setPassword($matrixUser['password']);

        if ($matrixUser['zip_code']) {
            $fullZip = null;
            $zip = null;
            $zipParts = explode('-', $matrixUser['zip_code']);

            if (count($zipParts) > 1) {
                $zip = $zipParts[0];
                $fullZip = $matrixUser['zip_code'];
            } else {
                $zip = $matrixUser['zip_code'];
            }

            $postalCode = $this->doctrine->getRepository('ApiBundle:PostalCode')->findOneBy(['code' => $zip]);

            if (!$postalCode) {
                $postalCode = new PostalCode();
                $postalCode->setCode($zip);
                if ($fullZip) {
                    $postalCode->setCodeFull($fullZip);
                }
                $this->entityManager->persist($postalCode);
            } else {
                if ($fullZip) {
                    $postalCode->setCodeFull($fullZip);
                }
            }

            $user->setPostalCode($postalCode);
        }

        if ($matrixUser['birthdate']) {
            $user->setBirthDate(new \DateTime($matrixUser['birthdate']));
        }

        if ($matrixUser['gender']) {
            if (strtolower($matrixUser['gender']) == 'male') {
                $user->setGender('M');
            }
            if (strtolower($matrixUser['gender']) == 'female') {
                $user->setGender('F');
            }
        }

        if ($matrixUser['city']) {
            $city = $matrixUser['city'];

            $user->setCity($matrixUser['city']);
        }

        if ($matrixUser['country']) {
            /** @var Country $country */
            $country = $this->doctrine->getRepository('ApiBundle:Country')
                ->findOneBy(['id' => $matrixUser['country']]);

            if ($country) {
                $user->setCountry($country);
            }
        }

        if ($matrixUser['province']) {
            /** @var State $state */
            $state = $this->doctrine->getRepository('ApiBundle:State')->findOneBy(
                ['abbreviation' => strtoupper($matrixUser['province'])]
            );

            if (!$state) {
                $state = $this->doctrine->getRepository('ApiBundle:State')->findOneBy(
                    ['name' => ucwords($matrixUser['province'])]
                );
            }

            if (!$state) {
                $state = $this->doctrine->getRepository('ApiBundle:State')->findOneBy(
                    ['slug' => strtolower($matrixUser['province'])]
                );
            }

            if ($state) {
                $user->setState($state);
            }
        }

        if ($matrixUser['address']) {
            $address = $this->getAddress(
                $matrixUser['address'],
                $city,
                $state,
                $postalCode,
                $country
            );

            if ($address) {
                $user->setAddress($address);
            }
        }

        $this->userManager->updateUser($user);

        return $user;
    }

    private function getAddress($street, $city, $state, $postalCode, $country)
    {
        if ($street && $city && $state && $postalCode && $country) {
            $address = new Address();
            $address->setLine1($street);
            $address->setCity($city);
            $address->setState($state);
            $address->setPostalCode($postalCode);
            $address->setCountry($country);
            $hash = $address->generateHash();
            $hasHash = $this->doctrine->getRepository('ApiBundle:Address')->findOneBy(['hash' => $hash]);
            if ($hasHash) {
                return $hasHash;
            } else {
                $this->entityManager->persist($address);

                return $address;
            }
        }

        return false;
    }

    private function getUniqueUsername($matrixUserEmail)
    {
        $email = explode('@', $matrixUserEmail);

        if (is_array($email) && array_key_exists(1, $email)) {
            $username = $email[0];
        } else {
            $username = $matrixUserEmail;
        }

        $taken = $this->userManager->findUserByUsername($username);
        if ($taken) {
            for ($i = 1; $i < 100; $i++) {
                $try = $username.$i;
                $fail = $this->userManager->findUserByUsername($try);
                if (!$fail) {
                    $username = $try;
                    break;
                }
            }
        }

        return $username;
    }

    private function getMatrixUserBooks($userId)
    {
        $result = $this->pdo->prepare(
            'SELECT * FROM users_guide_books_as_travelInterests
            WHERE users_id = :id'
        );

        $result->bindValue(':id', $userId);

        if (!$result->execute()) {
            // There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMatrixUserProperties($userId)
    {
        $result = $this->pdo->prepare(
            'SELECT properties_id FROM users_properties
             WHERE users_id = :id'
        );

        $result->bindValue(':id', $userId);

        if (!$result->execute()) {
            // There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMatrixUserFavorites($userId)
    {
        $result = $this->pdo->prepare(
            'SELECT properties_id FROM users_properties_as_favorites
             WHERE users_id = :id'
        );

        $result->bindValue(':id', $userId);

        if (!$result->execute()) {
            // There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMatrixUserTravelTypes($userId)
    {
        $result = $this->pdo->prepare(
            'SELECT travel_types_id FROM users_travel_types_as_travelTypes
             WHERE users_id = :id'
        );

        $result->bindValue(':id', $userId);

        if (!$result->execute()) {
            // There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMatrixGuide($guideId)
    {
        $result = $this->pdo->prepare(
            'SELECT newsletter_name FROM guide_books
            WHERE id = :id'
        );

        $result->bindValue(':id', $guideId);

        if (!$result->execute()) {
            // There is a problem with the query or the database
            $this->output->writeln('<error>'.$result->errorInfo().'</error>');
            exit(1);
        }

        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
