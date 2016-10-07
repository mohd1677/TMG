<?php
namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AxCustomerAddressRepository extends EntityRepository
{
    /**
     * Get all city name in $state
     *
     * @param string $state
     *
     * @return array
     */
    public function getStateCities($state)
    {
        return $this->createQueryBuilder('ad')
            ->select("ad.city")
            ->where("ad.state = :state")
            ->setParameter("state", $state)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Find suggestions near $input
     *
     * @param string $input
     *
     * @return array
     */
    public function findSuggestions($input)
    {
        $input = str_replace('%', '', $input);

        $suggestions = $this->getZipSuggestions($input);

        if (strpos($input, ',') === false) {
            $suggestions = array_merge(
                $suggestions,
                $this->getStateSuggestions($input),
                $this->getCitySuggestions($input)
            );
        }

        return $suggestions;
    }

    /**
     * Get all cities containing $input
     *
     * @param string $input
     *
     * @return array
     */
    public function getCitySuggestions($input)
    {

        $results = $this->createQueryBuilder('ad')
                ->select("ad.city")
                ->where('ad.city LIKE :city')
                ->setParameter("city", '%' . $input . '%')
                ->distinct()
                ->getQuery()
                ->getResult();



        $out = [];

        foreach ($results as $addr) {
            $citystate = $addr['city'];
            $out[$citystate] = [
                'type' => 'citystate',
                'display' => $citystate,
                'value' => [$addr['city']]
            ];
        }

        return array_values($out);
    }

    /**
     * Get suggested state
     *
     * @param string $input
     *
     * @return array
     */
    public function getStateSuggestions($input)
    {
        $results = $this->createQueryBuilder('ad')
                ->select("ad.state")
                ->where('ad.state LIKE :state')
                ->setParameter("state", '%' . $input . '%')
                ->distinct()
                ->getQuery()
                ->getResult();

        $out = [];

        foreach ($results as $result) {
            $code = strtoupper($result['state']);
            $out[] = [
                'type' => 'state',
                'display' => $result['city'],
                'value' => $code
            ];
        }

        return $out;
    }

    /**
     * Format suggestion
     *
     * @param string $input
     *
     * @return array
     */
    public function getNameSuggestions($input)
    {
        return [[
        'type' => "search",
        'display' => "$input",
        'value' => $input
        ]];
    }

    /**
     * Get formatted zip suggestion
     *
     * @param string $input
     *
     * @return array
     */
    public function getZipSuggestions($input)
    {
        if (!self::couldBeZip($input)) {
            return [];
        }

        $input = self::normalizeZip($input);

        if (strlen($input) == 5) {
            return [[
                'type' => 'zip',
                'display' => "Near zip $input",
                'value' => $input
            ]];
        }

        return $this->createQueryBuilder('ad')
            ->select("ad.postalCode")
            ->where('ad.postalCode LIKE :zip')
            ->setParameter("zip", '%' . $input . '%')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if US zip
     *
     * @param string $input
     *
     * @return bool
     */
    public static function couldBeZip($input)
    {
        return strlen($input) < 6 && ctype_digit($input);
    }

    /**
     * Normalize Zip
     *
     * @param string $zip
     *
     * @return string
     */
    public static function normalizeZip($zip)
    {

        if (strpos($zip, '-') === 5) {
            $zip = substr($zip, 0, 5);
        }

        return $zip;
    }
}
