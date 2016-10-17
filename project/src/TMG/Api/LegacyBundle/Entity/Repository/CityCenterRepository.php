<?php
namespace TMG\Api\LegacyBundle\Entity\Repository;

use TMG\Api\LegacyBundle\Entity\CityCenter;
use Doctrine\ORM\EntityRepository;

class CityCenterRepository extends EntityRepository
{

    /**
     * Closest city
     *
     * @param string $lat
     * @param string $long
     *
     * @return CityCenter
     */
    public function findClosestCity($lat, $long)
    {
        $result = $this->createQueryBuilder('cc')
            ->select(
                "cc, ( 3959 * acos( cos( radians(:lat) ) * cos( radians( cc.latitude ) )
                * cos( radians( cc.longitude ) - radians(:long) ) + sin( radians(:lat) )
                * sin(radians(cc.latitude)) ) ) AS distance"
            )
            ->having('distance < 50')
            ->where("cc.heroImageUrl IS NOT NULL")
            ->setParameters(["lat" => $lat, "long" =>$long])
            ->orderBy('distance')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!$result) {
            $result = new CityCenter();
            // TODO
            $result->setHeroImageUrl("default Image Link here");
        }

        return $result;
    }

    /**
     * Find suggested cities
     *
     * @param string $input
     *
     * @return array
     */
    public function findResultSuggestions($input)
    {
        $input = str_replace('%', '', $input);

        $suggestions = [];
        if (strpos($input, ',') === false) {
            $suggestions = $this->getCitySuggestions($input);
        }

        return $suggestions;
    }

    /**
     * Get cities name contains $input
     *
     * @param string $input
     *
     * @return array
     */
    public function getCitySuggestions($input)
    {
        $stateSlugs = $this->stateSlugs();

        $results = $this->createQueryBuilder('cc')
            ->select('cc.city', 'cc.state')
            ->where('cc.city LIKE :city')
            ->setParameter("city", '%'. $input .'%')
            ->getQuery()
            ->getResult();

        $out = [];

        foreach ($results as $r) {
            $citystate = $r['city']. ", " . $r['state'];
            $citySlug = $this->slugify($r['city']);
            $stateSlug = $stateSlugs[$r['state']];

            $out[$citystate] = [
                'type' => 'citystate',
                'display' => $citystate,
                'value' => [$stateSlug,$citySlug]
            ];
        }

        return array_values($out);
    }

    /**
     * array of states in US and provinces in Canada
     *
     * @return array
     */
    public function stateSlugs()
    {
        return array(
            'AL' => 'alabama',
            'AK' => 'alaska',
            'AZ' => 'arizona',
            'AR' => 'arkansas',
            'CA' => 'california',
            'CO' => 'colorado',
            'CT' => 'connecticut',
            'DE' => 'delaware',
            'DC' => 'district-of-columbia',
            'FL' => 'florida',
            'GA' => 'georgia',
            'HI' => 'hawaii',
            'ID' => 'idaho',
            'IL' => 'illinois',
            'IN' => 'indiana',
            'IA' => 'iowa',
            'KS' => 'kansas',
            'KY' => 'kentucky',
            'LA' => 'louisiana',
            'ME' => 'maine',
            'MD' => 'maryland',
            'MA' => 'massachusetts',
            'MI' => 'michigan',
            'MN' => 'minnesota',
            'MS' => 'mississippi',
            'MO' => 'missouri',
            'MT' => 'montana',
            'NE' => 'nebraska',
            'NV' => 'nevada',
            'NH' => 'new-hampshire',
            'NJ' => 'new-jersey',
            'NM' => 'new-mexico',
            'NY' => 'new-york',
            'NC' => 'north-carolina',
            'ND' => 'north-dakota',
            'OH' => 'ohio',
            'OK' => 'oklahoma',
            'OR' => 'oregon',
            'PA' => 'pennsylvania',
            'RI' => 'rhode-island',
            'SC' => 'south-carolina',
            'SD' => 'south-dakota',
            'TN' => 'tennessee',
            'TX' => 'texas',
            'UT' => 'utah',
            'VT' => 'vermont',
            'VA' => 'virginia',
            'WA' => 'washington',
            'WV' => 'west-virginia',
            'WI' => 'wisconsin',
            'WY' => 'wyoming',
            //Provinces of Canada
            'AB' => 'alberta',
            'LB' => 'labrador',
            'NB' => 'new-brunswick',
            'NS' => 'nova-scotia',
            'NW' => 'north-west-terr',
            'PE' => 'prince-edward-is',
            'SK' => 'saskatchewen',
            'BC' => 'british-columbia',
            'MB' => 'manitoba',
            'NF' => 'newfoundland',
            'NU' => 'nunavut',
            'ON' => 'ontario',
            'QC' => 'quebec',
            'YU' => 'yukon',
        );
    }

    /**
     * slugify
     *
     * @param string $name
     *
     * @return string
     */
    public function slugify($name)
    {
        $slug = preg_replace('/[^a-z0-9\/]/i', '-', strtolower($name));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
