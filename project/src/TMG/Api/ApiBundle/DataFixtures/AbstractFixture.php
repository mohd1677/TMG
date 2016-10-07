<?php

namespace TMG\Api\ApiBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture as Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use TMG\Api\ApiBundle\Exception as Exception;

abstract class AbstractFixture extends Fixture implements ContainerAwareInterface
{

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Generic Implementation of load that wraps the "run" function in an explicit transaction
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->setManager($manager);

        $connection = $manager->getConnection();
        $connection->beginTransaction();

        try {
            $this->run($manager);

            $manager->flush();
            $connection->commit();
        } catch (\Exception $e) {
            echo($e->getMessage());
            $connection->rollback();
        }
    }

    /**
     * Generic "run" method that gets wrapped by the generic load
     *
     * @param ObjectManager $manager
     *
     * @return mixed
     */
    abstract protected function run(ObjectManager $manager);

    /**
     * Returns a query that can be used by loadRecord
     *
     * @param ObjectManager $manager
     * @param string $queryName
     * @return mixed
     */
    abstract protected function getQuery(ObjectManager $manager, $queryName = null);

    /**
     * Generic loadData function that loads data from the <Resource>Bundle/DataFixtures/ORM/data directory
     *
     * @param string $file  The filename portion of what to load
     * @param bool   $assoc Decode the JSON as an associative array
     *
     * @return mixed
     */
    protected function loadData($file, $assoc = false)
    {
        $reflector = new \ReflectionObject($this);

        $dir = dirname($reflector->getFilename()) . '/';
        if (false === strpos($file, 'data/')) {
            $dir  = $dir . '/data/';
        }

        if (!is_dir($dir)) {
            throw new Exception\InternalServerErrorHttpException("The directory $dir does not exist");
        }

        $filename = $dir . $file;
        if (!is_file($filename)) {
            throw new Exception\InternalServerErrorHttpException("The file $filename does not exist");
        }

        $json = json_decode(file_get_contents($filename), $assoc);

        if (is_null($json)) {
            throw new Exception\InternalServerErrorHttpException("The file $filename does not contain valid JSON");
        }

        return $json;
    }

    /**
     * Loads a single record with the passed in parameters
     *
     * @param array $parameters The array of parameters to pass to the query
     * @param null  $queryName A query name to pass in to the getQuery function
     *
     * @return mixed|null
     */
    protected function loadRecord(array $parameters, $queryName = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getQuery($this->getManager(), $queryName);

        foreach ($parameters as $key => $value) {
            $qb->setParameter($key, $value);
        }

        $q = $qb->getQuery();

        $result = $q->getResult();

        if (count($result)) {
            return current($result);
        }

        return null;
    }


    /**
     * @return ObjectManager
     */
    protected function getManager()
    {
        return $this->manager;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function setManager(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
