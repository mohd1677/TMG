<?php
namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class CombinedListingRepository extends EntityRepository
{
    /**
     * Ubiquitous search algorithm for mobile
     *
     * @param array $params -
     *     valid keys -
     *         lat - latitude
     *         long - longitude
     *         isFeatured - 0 or 1
     *         max - maximum number of results. int
     *         page - 0 indexed page result
     *         fromPrice - prices >= fromPrice will be displayed
     *         toPrice - prices <= toPrice will be displayed
     *         name - name of property
     *         amenities - array of amenities to search for
     *         sortBy - string representing how to sort results
     *                 valid values:
     *                     distance
     *                     low-to-high
     *                     high-to-low
     *
     * @return array
     */
    public function findListing(array $params = [])
    {

        $result = $this->createQueryBuilder('cl');

        if (array_key_exists('lat', $params) && array_key_exists('long', $params) &&
            !empty($params['lat']) && !empty($params['long'])
        ) {
            $result->addSelect('
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ')
                ->setParameter('lat', $params['lat'])
                ->setParameter('lng', $params['long']);
        }

        $result->where('cl.latitude IS NOT NULL');
        $result->andWhere('cl.longitude IS NOT NULL');
        $result->andWhere('cl.ihgProperty IS NULL');
        $result->having('distance < 50.000');

        if (array_key_exists('isFeatured', $params)) {
            $result->andWhere('cl.isFeatured = :featured')
                ->setParameter('featured', $params['isFeatured']);
        }

        if (array_key_exists('count', $params) && !empty($params['count'])) {
            $result->setMaxResults($params['count']);

            if (array_key_exists('page', $params) && (!empty($params['page']) || $params['page'] == 0)) {
                $offset = $params['count'] * $params['page'];
                $result->setFirstResult($offset);
            }
        }

        if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)
            && !empty($params['fromPrice']) && !empty($params['toPrice'])
        ) {
            $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
            $result->setParameter("fromPrice", $params['fromPrice']);
            $result->setParameter("toPrice", $params['toPrice']);
        } else {
            if (array_key_exists('fromPrice', $params) && !empty($params['fromPrice'])) {
                $result->andWhere("cl.rateValue >= :fromPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
            }

            if (array_key_exists('toPrice', $params) && !empty($params['toPrice'])) {
                $result->andWhere("cl.rateValue <= :toPrice");
                $result->setParameter("toPrice", $params['toPrice']);
            }
        }

        if (array_key_exists('name', $params) && !empty($params['name'])) {
            $result->andWhere("cl.name like :name");
            $result->setParameter("name", '%' . $params['name'] . '%');
        }

        if (array_key_exists("sortBy", $params) && array_key_exists('order', $params) &&
            !empty($params['sortBy']) && !empty($params['order'])
        ) {
            if ($params['order'] != 'distance') {
                $params['order'] = 'cl.' . $params['order'];
            }
            $result->orderBy($params['order'], $params['sortBy']);
        }

        //amenity filter
        if (array_key_exists('amenities', $params) && !empty($params['amenities'])) {
            $result->join('cl.amenities', 'a');
            $count = count($params['amenities']);
            $result->andWhere("a.keySelector in (:amenities)")
                ->groupBy("cl.id")
                ->having("COUNT(cl.id) = :count")
                ->setParameter("count", $count)
                ->setParameter("amenities", $params['amenities']);
        }

        return $result->getQuery()->getResult();

    }

    /**
     * Featured Listings Count
     *
     * @param array $params
     *
     * @return int
     */
    public function findFeaturedListingsCount($params = null)
    {
        $result = $this->createQueryBuilder('cl')
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->having("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }

        return count($result->getQuery()->getResult());
    }

    /**
     * find Featured
     *
     * @param string $lat
     * @param string $lng
     * @param int $page
     * @param int $max
     * @param array $params
     *
     * @return array
     */
    public function findFeaturedByCoordinates($lat, $lng, $page, $max = 10, $params = null)
    {
        $offset = $max * $page;
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->setFirstResult($offset)
            ->setMaxResults($max);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            if (array_key_exists("sortby", $params)) {
                if ($params['sortby'] == 'distance') {
                    $result->orderBy('distance', 'ASC');
                }
                if ($params['sortby'] == 'low-to-high') {
                    $result->orderBy('cl.rateValue', 'ASC');
                }
                if ($params['sortby'] == 'high-to-low') {
                    $result->orderBy('cl.rateValue', 'DESC');
                }
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->having("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }
        $result = $result->getQuery()->getResult();
        $result = $this->removeDistance($result);

        return $result;
    }

    /**
     * Remove 'distance' from results array
     *
     * @param array $res
     *
     * @return array
     */
    private function removeDistance($res)
    {
        $return = [];

        foreach ($res as $r) {
            $r[0]->distance = $r['distance'];
            $return[] = $r[0];
        }

        return $return;
    }

    /**
     * Featured listings
     *
     * @param int $page
     * @param int $max
     * @param array $params
     *
     * @return array
     *
     * @deprecated This method needs to be fixed.
     */
    public function findFeatured($page, $max = 10, $params = null)
    {
        // Crude "fix" not sure if this method is used anywhere. See my @deprecated tag above.
        $offset = ($page * $max) + 1;

        $result = $this->createQueryBuilder("cl")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1)
            ->setFirstResult($offset)
            ->setMaxResults($max);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            if (array_key_exists("sortby", $params)) {
                if ($params['sortby'] == 'low-to-high') {
                    $result->orderBy('cl.rateValue', 'ASC');
                }
                if ($params['sortby'] == 'high-to-low') {
                    $result->orderBy('cl.rateValue', 'DESC');
                }
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->having("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }

        $result = $result->getQuery()->getResult();

        return $result;
    }

    /**
     * Featured listings with min and max of rate
     *
     * @param string $lat
     * @param string $lng
     * @param array $params
     *
     * @return array|bool
     */
    public function findFeaturedMinMaxByCoordinates($lat, $lng, $params = null)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance, cl.rateValue
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->setParameter('feat', 1);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->having("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }

        $result->orderBy("cl.rateValue", "ASC");
        $resultSet = $result->getQuery()->getArrayResult();
        if (is_array($resultSet) && count($resultSet) > 0) {
            $output['min'] = $resultSet[0]['rateValue'];
            $output['max'] = end($resultSet)['rateValue'];
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Featured listings with min and max of rate
     *
     * @param array $params
     *
     * @return array|bool
     */
    public function findFeaturedMinMax($params = null)
    {
        $result = $this->createQueryBuilder("cl")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->having("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }
        $result->orderBy("cl.rateValue", "ASC");
        $resultSet = $result->getQuery()->getArrayResult();
        if (is_array($resultSet) && count($resultSet) > 0) {
            $output['min'] = $resultSet[0]['rateValue'];
            $output['max'] = end($resultSet)['rateValue'];
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Featured listings without distance
     *
     * @param string $lat
     * @param string $lng
     * @param int $max
     *
     * @return array
     */
    public function getFeaturedByCoords($lat, $lng, $max = 6)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->orderBy('distance', 'ASC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();

        $result = $this->removeDistance($result);
        return $result;
    }

    /**
     * Featured listings with distance
     *
     * @param string $lat
     * @param string $lng
     * @param int $max
     *
     * @return array
     */
    public function getFeaturedAndDistanceByCoords($lat, $lng, $max = 6)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->orderBy('distance', 'ASC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * Random featured listings
     *
     * @param int $max
     *
     * @return array
     */
    public function findRandomFeatured($max = 6)
    {
        $count = $this->findFeaturedListingsCount();
        $query = $this->createQueryBuilder("cl")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1);

        $query->setFirstResult(rand(0, $count - ($max + 1)));

        return $query->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * For hotelcoupon_widget plugin of wp blog
     *
     * @param string $city
     * @param string $state
     * @param int $max
     *
     * @return array
     */
    public function findListingsByCityState($city, $state, $max = 5)
    {
        $query = $this->createQueryBuilder("cl")
            ->select("cl.id, cl.name, cl.slug, a.city, a.state, cl.ratePretty, cl.rateType, p.urlSmall")
            ->join('cl.address', 'a')
            ->join('cl.photos', 'p')
            ->where('a.city = :city')
            ->andWhere('a.state = :state')
            ->andWhere('p.isDisplayImg = :display')
            ->setParameter('city', $city)
            ->setParameter('state', $state)
            ->setParameter('display', 1);

        $count = count($query->getQuery()->getResult());

        if ($count > $max) {
            $query->setFirstResult(rand(0, $count - ($max + 1)));
        }

        return $query->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Featured listings by state
     *
     * @param string $state
     * @param int $max
     * @return array
     */
    public function getFeaturedByState($state, $max = 6)
    {
        return $this->createQueryBuilder('cl')
            ->leftJoin('cl.address', 'a')
            ->where('cl.isFeatured = :feat')
            ->andWhere('a.state = :state')
            ->setParameter('feat', 1)
            ->setParameter('state', $state)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Featured listings
     *
     * @param string $lat
     * @param string $lng
     * @param string $state
     * @param int $max
     *
     * @return array
     */
    public function getFeaturedByCoordsExcludeState($lat, $lng, $state, $max = 6)
    {
        $result = $this->createQueryBuilder("cl")
            ->leftJoin('cl.address', 'a')
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->andWhere('a.state != :state')
            ->setParameter('feat', 1)
            ->setParameter('state', $state)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->orderBy('distance', 'ASC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();

        $result = $this->removeDistance($result);
        return $result;
    }

    /**
     * Random Featured listings
     *
     * @param string $state
     * @param int $max
     * @return array
     */
    public function getRandomFeaturedExcludeState($state, $max = 6)
    {
        $count = $this->findFeaturedListingsCount();

        return $this->createQueryBuilder("cl")
            ->leftJoin('cl.address', 'a')
            ->where('cl.isFeatured = :feat')
            ->andWhere('a.state != :state')
            ->setParameter('feat', 1)
            ->setParameter('state', $state)
            ->setFirstResult(rand(0, $count - ($max + 1)))
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Cities in $state
     *
     * @param string $state
     * @return array
     */
    public function getDistinctCitiesForState($state)
    {
        return $this->createQueryBuilder('cl')
            ->select("a.city")
            ->leftJoin("cl.address", 'a')
            ->where('a.state = :state')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter("state", $state)
            ->orderBy("a.city")
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Listings in $state
     *
     * @param string $state
     * @return array
     * @return array
     */
    public function findListingsForState($state)
    {
        $query = $this->createQueryBuilder('cl')
            ->leftJoin('cl.address', 'a')
            ->where('a.state = :state')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('state', $state);

        $query = $query->addSelect('a');
        return $query->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Listing count
     *
     * @param string $lat
     * @param string $lng
     * @param string $radius
     * @param array $params
     *
     * @return int
     */
    public function findListingsCountByCoords($lat, $lng, $radius, $params = null)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ")
            ->where('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->having('distance <= :radius')
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->setParameter('radius', $radius);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->andHaving("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }

        $result = $result->getQuery()->getResult();
        return count($result);
    }

    /**
     * Listings
     *
     * @param string $lat
     * @param string $lng
     * @param string $radius
     * @param int $page
     * @param int $max
     * @param array $params
     *
     * @return array
     */
    public function findListingsByCoords($lat, $lng, $radius, $page, $max = 10, $params = null)
    {
        $sortBy = '';
        $offset = $max * $page;

        //this parameter applies some advanced sorting not possible with
        //a single sql pull against the old api
        $sortListings = false;
        if (isset($params)) {
            if (array_key_exists("sortListings", $params)) {
                $sortListings = true;
            }
        }

        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance
            ")
            ->where('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->having('distance <= :radius')
            ->setParameter('radius', $radius)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng);

        if (!$sortListings) {
            $result->setFirstResult($offset);
            $result->setMaxResults($max);
        }

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            if (array_key_exists("sortby", $params)) {
                if ($params['sortby'] == 'distance') {
                    $result->orderBy('distance', 'ASC');
                } elseif ($params['sortby'] == 'low-to-high') {
                    $result->orderBy('cl.rateValue', 'ASC');
                    if ($sortListings) {
                        //for same rate, prioritize hard rates over "from" rates
                        $result->addOrderBy('cl.ratePretty', 'ASC');
                        $result->addOrderBy('distance', 'ASC');
                    }
                } elseif ($params['sortby'] == 'high-to-low') {
                    $result->orderBy('cl.rateValue', 'DESC');
                    if ($sortListings) {
                        //for same rate, prioritize "from" rates over hard rates
                        $result->addOrderBy('cl.ratePretty', 'DESC');
                        $result->addOrderBy('distance', 'ASC');
                    }
                } elseif ($params['sortby'] == 'activeAt') {
                    if ($sortListings) {
                        //for advanced sorting we want them ordered by distance
                        //the advanced sort will push the recent rates to the top as needed
                        $result->orderBy('distance', 'ASC');
                    } else {
                        $result->orderBy('cl.activeAt', 'DESC');
                    }
                }
                $sortBy = $params['sortby'];
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->andHaving("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }

        $result = $result->getQuery()->getResult();
        $result = $this->removeDistance($result);

        if ($sortListings) {
            //this is the advanced sort made for CombinedListings->cityAction
            $result = $this->sortListings($result, $sortBy);
            $result = array_slice($result, $offset, $max);
        }

        return $result;
    }

    /**
     * sortListings function.
     *
     * @access private
     * @param array $listings
     * @param mixed $sortBy
     *
     * @return array
     */
    private function sortListings(array $listings, $sortBy)
    {
        // WTF!
        $buckets = [
            [],
            [],
            [],
            [],
        ];
        $listingsSorted = [];
        $recent = strtotime('-1 day');

        switch ($sortBy) {
            case 'activeAt':
                foreach ($listings as $listing) {
                    $activeAt = $listing->getActiveAt()->getTimestamp();
                    if ($activeAt >= $recent) {
                        $buckets[0][] = $listing;
                    } elseif (!empty($listing->getIhgProperty()) && empty($listing->getProperty())) {
                        $buckets[3][] = $listing;
                    } elseif (strpos($listing->getName(), 'La Quinta') !== false) {
                        $buckets[2][] = $listing;
                    } else {
                        $buckets[1][] = $listing;
                    }
                }

                foreach ($buckets as $bucket) {
                    foreach ($bucket as $listing) {
                        $listingsSorted[] = $listing;
                    }
                }
                break;

            case 'low-to-high':
            case 'high-to-low':
                foreach ($listings as $listing) {
                    $rate = $listing->getRateValue();
                    if (is_numeric($rate) && $rate > 0) {
                        $buckets[0][] = $listing;
                    } else {
                        $buckets[1][] = $listing;
                    }
                }

                foreach ($buckets as $bucket) {
                    foreach ($bucket as $listing) {
                        $listingsSorted[] = $listing;
                    }
                }
                break;


            default:
                $listingsSorted = $listings;
                break;
        }

        return $listingsSorted;
    }

    /**
     * Listings with min and max rate
     *
     * @param string $lat
     * @param string $lng
     * @param string $radius
     * @param array $params
     *
     * @return array|bool
     */
    public function findListingsMinMaxByCoordinates($lat, $lng, $radius, $params = null)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                * acos(cos(radians(:lat))
                * cos(radians(cl.latitude))
                * cos(radians(cl.longitude) - radians(:lng))
                + sin(radians(:lat)) * sin(radians(cl.latitude))
                AS distance, cl.rateValue
            ")
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->having('distance <= :radius')
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->setParameter('radius', $radius);

        if (isset($params)) {
            if (array_key_exists('fromPrice', $params) && array_key_exists('toPrice', $params)) {
                $result->andWhere("cl.rateValue BETWEEN :fromPrice AND :toPrice");
                $result->setParameter("fromPrice", $params['fromPrice']);
                $result->setParameter("toPrice", $params['toPrice']);
            } else {
                if (array_key_exists('fromPrice', $params)) {
                    $result->andWhere("cl.rateValue >= :fromPrice");
                    $result->setParameter("fromPrice", $params['fromPrice']);
                }
                if (array_key_exists('toPrice', $params)) {
                    $result->andWhere("cl.rateValue <= :toPrice");
                    $result->setParameter("toPrice", $params['toPrice']);
                }
            }
            if (array_key_exists('name', $params)) {
                $result->andWhere("cl.name like :name");
                $result->setParameter("name", '%' . $params['name'] . '%');
            }
            //amenity filter
            if (array_key_exists('amenities', $params)) {
                $result->join('cl.amenities', 'a');
                $count = count($params['amenities']);
                $result->andWhere("a.keySelector in (:amenities)")
                    ->groupBy("cl.id")
                    ->andHaving("COUNT(cl.id) = :count")
                    ->setParameter("count", $count)
                    ->setParameter("amenities", $params['amenities']);
            }
        }

        $result->orderBy("cl.rateValue", "ASC");
        $resultSet = $result->getQuery()->getArrayResult();
        if (is_array($resultSet) && count($resultSet) > 0) {
            $output['min'] = $resultSet[0]['rateValue'];
            $output['max'] = end($resultSet)['rateValue'];
            return $output;
        } else {
            return false;
        }
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
     * Find suggestions near $input
     *
     * @param string $input
     *
     * @return array
     */
    public function findResultSuggestions($input)
    {
        $input = str_replace('%', '', $input);

        $suggestions = $this->getZipSuggestions($input);

        if (strpos($input, ',') === false) {
            $suggestions = array_merge(
                $suggestions,
                $this->getCityStateSuggestions($input),
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
        $stateSlugs = $this->stateSlugs();
        $results = $this->createQueryBuilder('cl')
            ->select("a.city,a.state")
            ->leftJoin("cl.address", 'a')
            ->where('a.city LIKE :city')
            ->setParameter("city", '%' . $input . '%')
            ->distinct()
            ->getQuery()
            ->getResult();

        $out = [];
        foreach ($results as $addr) {
            $citystate = $addr['city'] . ", " . $addr['state'];
            $citySlug = $this->slugify($addr['city']);
            $stateSlug = $stateSlugs[$addr['state']];

            $out[$citystate] = [
                'type' => 'citystate',
                'display' => $citystate,
                'value' => [$stateSlug, $citySlug]
            ];
        }

        return array_values($out);

    }

    /**
     * Get all cities that state containing $input
     *
     * @param string $input
     *
     * @return array
     */
    public function getCityStateSuggestions($input)
    {
        $stateSlugs = $this->stateSlugs();
        $results = $this->createQueryBuilder('cl')
            ->select("a.state,a.city")
            ->leftJoin("cl.address", 'a')
            ->where('a.state LIKE :state')
            ->setParameter("state", '%' . $input . '%')
            ->distinct()
            ->getQuery()
            ->getResult();

        $out = [];
        foreach ($results as $result) {
            $code = strtoupper($result['state']);
            $citystate = $result['city'] . ", " . $code;
            $citySlug = $this->slugify($result['city']);
            $stateSlug = $stateSlugs[$code];

            $out[] = [
                'type' => 'state',
                'display' => $citystate,
                'value' => [$stateSlug, $citySlug],
            ];
        }

        return $out;
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
        $results = $this->createQueryBuilder('cl')
            ->select("a.state,a.city")
            ->leftJoin("cl.address", 'a')
            ->where('a.state LIKE :state')
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

        return $this->createQueryBuilder('cl')
            ->select("a.zip")
            ->leftJoin("cl.address", 'a')
            ->where('a.zip LIKE :zip')
            ->setParameter("zip", '%' . $input . '%')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if US zip
     *
     * @param string $input
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
     * @return string
     */
    public static function normalizeZip($zip)
    {
        // remove the +4
        if (strpos($zip, '-') === 5) {
            $zip = substr($zip, 0, 5);
        }
        return $zip;
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

    // Old Queries
    // =========================

    /**
     * Total Listing Count
     *
     * @param array $params
     *
     * @return int
     */
    public function getTotalListingCount($params)
    {
        $qb = $this->createQueryBuilder("cl")
            ->select("Count(cl)")
            ->leftJoin("cl.address", 'a');

        $i = 0;
        foreach ($params as $key => $val) {
            if ($key == "fromPrice" || $key == "toPrice") {
                if ($key == "fromPrice") {
                    $qb->andWhere("cl.rateValue >= :fromPrice");
                } else {
                    $qb->andWhere("cl.rateValue <= :toPrice");
                }
                continue;
            }

            if ($key == "state" || $key == "city" || $key == "zip") {
                if ($i == 0) {
                    $qb->where("a.$key = :$key");
                } else {
                    $qb->andWhere("a.$key = :$key");
                }
            } else {
                if ($i == 0) {
                    $qb->where("cl.$key = :$key");
                } else {
                    $qb->andWhere("cl.$key = :$key");
                }
            }
            $i++;
        }
        return $qb->setParameters($params)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find specific listings
     *
     * @param array $params
     * @param int $page
     * @param string $sortby
     * @param string $lat
     * @param string $lng
     * @param int $max
     *
     * @return array
     */
    public function getListings($params, $page = 0, $sortby = null, $lat = null, $lng = null, $max = 10)
    {
        $offSet = $max * $page;

        $sortby = $this->getSortByVal($sortby);

        $qb = $this->createQueryBuilder("cl");

        // If sorting by distance we'll have to modify the result to fit to only include combined listings
        $removeDistance = false;

        if (is_array($sortby)) {
            if ($sortby["sortby"] == "distance") {
                $qb->addSelect("
                    3959
                     * acos(cos(radians($lat))
                     * cos(radians(cl.latitude))
                     * cos(radians(cl.longitude) - radians($lng))
                     + sin(radians($lat)) * sin(radians(cl.latitude))
                     AS distance
                     ");

                $removeDistance = true;
            }
        }

        $qb->leftJoin("cl.address", 'a');

        // Gets the search terms
        $i = 0;
        foreach ($params as $key => $val) {
            // Checks to see if this is a sorting by price range param.
            if ($key == "fromPrice" || $key == "toPrice") {
                if ($key == "fromPrice") {
                    $qb->andWhere("cl.rateValue >= :fromPrice");
                } else {
                    $qb->andWhere("cl.rateValue <= :toPrice");
                }
                continue;
            }

            if ($key == "state" || $key == "city" || $key == "zip") {
                if ($i == 0) {
                    $qb->where("a.$key = :$key");
                } else {
                    $qb->andWhere("a.$key = :$key");
                }
            } else {
                if ($i == 0) {
                    $qb->where("cl.$key = :$key");
                } else {
                    $qb->andWhere("cl.$key = :$key");
                }
            }
            $i++;
        }

        $qb->setParameters($params)
            ->setFirstResult($offSet)
            ->setMaxResults($max);

        if (is_array($sortby)) {
            $sort = $sortby["sortby"];
            $order = $sortby["order"];
            if ($sort != "distance") {
                $qb->orderBy("cl.$sort", $order);
            } else {
                $qb->orderBy("$sort", $order);
            }
        }

        $qb = $qb->getQuery()
            ->getResult();


        if ($removeDistance) {
            $qb = $this->removeDistance($qb);
        }

        return $qb;

    }

    /**
     * Get random $max listings with $params
     *
     * @param array $params
     * @param int $max
     *
     * @return array
     */
    public function getFeaturedProperties($params, $max = 6)
    {
        $qb = $this->createQueryBuilder('cl')
            ->select('COUNT(cl)')
            ->leftJoin('cl.address', 'a');

        $i = 0;
        // Removing fromPrice and toPrice from param if exists.
        foreach ($params as $key => $val) {
            if ($key == "fromPrice" || $key == "toPrice") {
                unset($params[$key]);
                continue;
            }

            if ($key == "state" || $key == "city" || $key == "zip") {
                if ($i == 0) {
                    $qb->where("a.$key = :$key");
                } else {
                    $qb->andWhere("a.$key = :$key");
                }
            } else {
                if ($i == 0) {
                    $qb->where("cl.$key = :$key");
                } else {
                    $qb->andWhere("cl.$key = :$key");
                }
            }

            $i++;
        }

        $qb->setParameters($params);

        $count = $qb->getQuery()->getSingleScalarResult();

        if ($max > $count) {
            $max = $count;
        }

        $qb = $this->createQueryBuilder('cl')
            ->leftJoin('cl.address', 'a');
        $i = 0;
        foreach ($params as $key => $val) {
            if ($key == "state" || $key == "city" || $key == "zip") {
                if ($i == 0) {
                    $qb->where("a.$key = :$key");
                } else {
                    $qb->andWhere("a.$key = :$key");
                }
            } else {
                if ($i == 0) {
                    $qb->where("cl.$key = :$key");
                } else {
                    $qb->andWhere("cl.$key = :$key");
                }
            }

            $i++;
        }

        $qb->setParameters($params);

        return $qb->setFirstResult(rand(0, $count - ($max + 1)))
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Sort by $v
     *
     * @param string $v
     *
     * @return array|null
     */
    private function getSortByVal($v)
    {
        if ($v == "relevance") {
            return null;
        } elseif ($v == "low-to-high") {
            return ["sortby" => "rateValue", "order" => "asc"];
        } elseif ($v == "high-to-low") {
            return ["sortby" => "rateValue", "order" => "desc"];
        } elseif ($v == "near-to-far") {
            return ["sortby" => "distance", "order" => "asc"];
        } elseif ($v == "far-to-near") {
            return ["sortby" => "distance", "order" => "desc"];
        } elseif ($v == "default") {
            return ["sortby" => "distance", "order" => "asc"];
        }

        return null;
    }

    /**
     * If city name is valid
     *
     * @param string $city
     *
     * @return int
     */
    public function isValidCity($city)
    {
        return $this->createQueryBuilder('cl')
            ->select("count(cl)")
            ->leftJoin("cl.address", 'a')
            ->where('a.city = :city')
            ->setParameter("city", $city)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $input
     *
     * @return array
     */
    public function getNameSuggestions($input)
    {
        return [[
            'type' => "search",
            'display' => "Name contains $input",
            'value' => $input
        ]];

    }

    /**
     * Get Random Featured
     *
     * @param int $page
     * @param int $max
     * @param string $sortby
     * @param array $params
     *
     * @return array
     */
    public function getRandomFeatured($page = null, $max = 6, $sortby = null, $params = null)
    {
        $count = $this->getFeaturedCount();
        $sortby = $this->getSortByVal($sortby);
        $query = $this->createQueryBuilder("cl")
            ->where('cl.isFeatured = :feat')
            ->setParameter('feat', 1);

        if (isset($page)) {
            $offset = $max * $page;
            $query->setFirstResult($offset);
        } else {
            $query->setFirstResult(rand(0, $count - ($max + 1)));
        }
        if (is_array($sortby)) {
            $sort = $sortby["sortby"];
            $order = $sortby["order"];
            if ($sort != "distance") {
                $query->orderBy("cl.$sort", $order);
            } else {
                //$query->orderBy("$sort", $order);
            }
        }
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "brandName") {
                    $query->andWhere("cl.name like :brandName");
                    $query->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }

        return $query->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Featured Paginate
     *
     * @param int $page
     * @param int $max
     *
     * @return array
     */
    public function getRandomFeaturedAndPaginate($page, $max = 10)
    {
        $offset = $max * $page;

        return $this->createQueryBuilder("cl")
            ->where('cl.isFeatured = :feat')
            ->setParameter('feat', 1)
            ->setFirstResult($offset)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Featured By Coords
     *
     * @param string $lat
     * @param string $lng
     * @param int $page
     * @param int $max
     * @param string $sortby
     * @param array $params
     *
     * @return array
     */
    public function getFeaturedByCoordsAndPaginate($lat, $lng, $page, $max = 10, $sortby = null, $params = null)
    {
        $offset = $max * $page;
        $sortby = $this->getSortByVal($sortby);
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                 * acos(cos(radians($lat))
                 * cos(radians(cl.latitude))
                 * cos(radians(cl.longitude) - radians($lng))
                 + sin(radians($lat)) * sin(radians(cl.latitude))
                 AS distance
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1)
            ->orderBy('distance', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($max);
        if (is_array($sortby)) {
            $sort = $sortby["sortby"];
            $order = $sortby["order"];
            if ($sort != "distance") {
                $result->orderBy("cl.$sort", $order);
            } else {
                $result->orderBy("$sort", $order);
            }
        }
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $result->andWhere("cl.rateValue >= :fromPrice");
                        $result->setParameter("fromPrice", $val);
                    } else {
                        $result->andWhere("cl.rateValue <= :toPrice");
                        $result->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $result->andWhere("cl.name like :brandName");
                    $result->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        $result = $result->getQuery()
            ->getResult();

        $result = $this->removeDistance($result);

        return $result;
    }

    /**
     * Featured pagination by location
     *
     * @param string $lat
     * @param string $lng
     * @param string $radius
     * @param int $page
     * @param int $max
     *
     * @return array
     */
    public function getFeaturedByCoordsDistanceAndPaginate($lat, $lng, $radius, $page, $max = 10)
    {
        $offset = $max * $page;

        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                    3959
                     * acos(cos(radians($lat))
                     * cos(radians(cl.latitude))
                     * cos(radians(cl.longitude) - radians($lng))
                     + sin(radians($lat)) * sin(radians(cl.latitude))
                     AS distance
                     ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->andWhere('distance <= :radius')
            ->setParameter('feat', 1)
            ->setParameter('radius', $radius)
            ->orderBy('distance', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();

        $result = $this->removeDistance($result);

        return $result;
    }

    /**
     * Count listings by state
     *
     * @param string $state
     * @param array $params
     *
     * @return int
     */
    public function findListingsByStateCount($state, $params = null)
    {
        $query = $this->createQueryBuilder('cl')
            ->select('COUNT(cl)')
            ->leftJoin('cl.address', 'a')
            ->where('a.state = :state')
            ->setParameter('state', $state);
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $query->andWhere("cl.rateValue >= :fromPrice");
                        $query->setParameter("fromPrice", $val);
                    } else {
                        $query->andWhere("cl.rateValue <= :toPrice");
                        $query->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $query->andWhere("cl.name like :brandName");
                    $query->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        return $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Listings pagination by state
     *
     * @param string $state
     * @param int $page
     * @param int $max
     * @param string $sortby
     * @param array $params
     *
     * @return array
     */
    public function findListingsByState(
        $state,
        $page = null,
        $max = 10,
        /** @noinspection PhpUnusedParameterInspection */
        $sortby = null,
        $params = null
    ) {
        $query = $this->createQueryBuilder('cl')
            ->leftJoin('cl.address', 'a')
            ->where('a.state = :state')
            ->setParameter('state', $state);

        // add address eager load
        $query = $query->addSelect('a');

        if (isset($page)) {
            $offset = $max * $page;
            $query->setFirstResult($offset)
                ->setMaxResults($max);
        }
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $query->andWhere("cl.rateValue >= :fromPrice");
                        $query->setParameter("fromPrice", $val);
                    } else {
                        $query->andWhere("cl.rateValue <= :toPrice");
                        $query->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $query->andWhere("cl.name like :brandName");
                    $query->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }

        return $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Count featured listings
     *
     * @param array $params
     *
     * @return int
     */
    public function getFeaturedCount($params = null)
    {
        $result = $this->createQueryBuilder('cl')
            ->select('COUNT(cl)')
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1);
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $result->andWhere("cl.rateValue >= :fromPrice");
                        $result->setParameter("fromPrice", $val);
                    } else {
                        $result->andWhere("cl.rateValue <= :toPrice");
                        $result->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $result->andWhere("cl.name like :brandName");
                    $result->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        return $result->getQuery()->getSingleScalarResult();
    }

    /**
     * Listings pagination by distance
     *
     * @param string $lat
     * @param string $lng
     * @param string $radius
     * @param int $page
     * @param int $max
     * @param array $sortby
     * @param array $params
     *
     * @return array
     */
    public function getListingsByCoordsDistanceAndPaginate(
        $lat,
        $lng,
        $radius,
        $page,
        $max = 10,
        $sortby = null,
        $params = null
    ) {
        $offset = $max * $page;
        $sortby = $this->getSortByVal($sortby);
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                    3959
                     * acos(cos(radians($lat))
                     * cos(radians(cl.latitude))
                     * cos(radians(cl.longitude) - radians($lng))
                     + sin(radians($lat)) * sin(radians(cl.latitude))
                     AS distance
                     ")
            ->where('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->having('distance <= :radius')
            ->setParameter('radius', $radius)
            ->setFirstResult($offset)
            ->setMaxResults($max);
        if (is_array($sortby)) {
            $sort = $sortby["sortby"];
            $order = $sortby["order"];
            if ($sort != "distance") {
                $result->orderBy("cl.$sort", $order);
            } else {
                $result->orderBy("$sort", $order);
            }
        }
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $result->andWhere("cl.rateValue >= :fromPrice");
                        $result->setParameter("fromPrice", $val);
                    } else {
                        $result->andWhere("cl.rateValue <= :toPrice");
                        $result->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $result->andWhere("cl.name like :brandName");
                    $result->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        $result = $result->getQuery()->getResult();

        $result = $this->removeAndSortDistance($result, $radius);

        return $result;
    }

    /**
     * Count listings by distance
     *
     * @param string $lat
     * @param string $lng
     * @param string $radius
     * @param array $params
     *
     * @return array
     */
    public function getListingsByCoordsDistanceCount($lat, $lng, $radius, $params = null)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                    3959
                     * acos(cos(radians($lat))
                     * cos(radians(cl.latitude))
                     * cos(radians(cl.longitude) - radians($lng))
                     + sin(radians($lat)) * sin(radians(cl.latitude))
                     AS distance
                     ")
            ->where('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->having('distance <= :radius')
            ->setParameter('radius', $radius);
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $result->andWhere("cl.rateValue >= :fromPrice");
                        $result->setParameter("fromPrice", $val);
                    } else {
                        $result->andWhere("cl.rateValue <= :toPrice");
                        $result->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $result->andWhere("cl.name like :brandName");
                    $result->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        $result = $result->getQuery()->getResult();
        $result = $this->removeAndSortDistance($result, $radius, true);

        return $result;
    }

    /**
     * Find results within distance or count that results
     *
     * @param array $res
     * @param double $radius
     * @param bool $count
     *
     * @return int|array
     */
    private function removeAndSortDistance($res, $radius, $count = false)
    {
        $return = [];
        foreach ($res as $r) {
            if ($r['distance'] > $radius) {
                break;
            } else {
                $return[] = $r[0];
            }
        }
        if ($count) {
            return count($return);
        } else {
            return $return;
        }
    }

    /**
     * Added to get min and max values for price filter for listing pages
     *
     * @param string $lat
     * @param string $lng
     * @param int $radius
     * @param string $searchBy
     *
     * @return array|bool
     */
    public function getMinMaxForFiltersUsingCoordinates(
        $lat = '',
        $lng = '',
        $radius = 0,
        /** @noinspection PhpUnusedParameterInspection */
        $searchBy = null
    ) {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                 * acos(cos(radians($lat))
                 * cos(radians(cl.latitude))
                 * cos(radians(cl.longitude) - radians($lng))
                 + sin(radians($lat)) * sin(radians(cl.latitude))
                 AS distance, cl.rateValue
            ")
            ->where('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->having('distance <= :radius')
            ->setParameter('radius', $radius);
        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $result->andWhere("cl.rateValue >= :fromPrice");
                        $result->setParameter("fromPrice", $val);
                    } else {
                        $result->andWhere("cl.rateValue <= :toPrice");
                        $result->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $result->andWhere("cl.name like :brandName");
                    $result->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        $result->orderBy("cl.rateValue", "ASC");
        $resultSet = $result->getQuery()->getArrayResult();
        $resultSetCount = count($resultSet);
        if (is_array($resultSet) && $resultSetCount > 0) {
            $output['min'] = $resultSet[0]['rateValue'];
            $output['max'] = $resultSet[$resultSetCount - 1]['rateValue'];

            return $output;
        } else {
            return false;
        }
    }

    /**
     * Get min and max values for state filter for listing pages
     *
     * @param string $stateCode
     * @param string $searchBy
     *
     * @return array|bool
     */
    public function getMinMaxForFiltersUsingStateCode(
        $stateCode,
        /** @noinspection PhpUnusedParameterInspection */
        $searchBy = null
    ) {
        /** @var QueryBuilder $query */
        $query = $this->createQueryBuilder('cl')
            ->leftJoin('cl.address', 'a')
            ->where('a.state = :state')
            ->setParameter('state', $stateCode);

        // add min max
        $query = $query->addSelect('MIN(cl.rateValue) as first, Max(cl.rateValue) as last');

        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $query->andWhere("cl.rateValue >= :fromPrice");
                        $query->setParameter("fromPrice", $val);
                    } else {
                        $query->andWhere("cl.rateValue <= :toPrice");
                        $query->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $query->andWhere("cl.name like :brandName");
                    $query->setParameter("brandName", '%' . $val . '%');
                }
                $i++;
            }
        }
        $resultSet = $query->getQuery()->getArrayResult();
        $resultSetCount = count($resultSet);
        if (is_array($resultSet) && $resultSetCount > 0) {
            $output['min'] = $resultSet[0]['first'];
            $output['max'] = $resultSet[0]['last'];
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Get featured min and max values for location filter for listing pages
     *
     * @param string $lat
     * @param string $lng
     * @param array $params
     *
     * @return array|bool
     */
    public function getFeaturedByMinMaxForFiltersUsingCoordinates($lat, $lng, $params = null)
    {
        $result = $this->createQueryBuilder("cl")
            ->addSelect("
                3959
                 * acos(cos(radians($lat))
                 * cos(radians(cl.latitude))
                 * cos(radians(cl.longitude) - radians($lng))
                 + sin(radians($lat)) * sin(radians(cl.latitude))
                 AS distance, cl.rateValue
            ")
            ->where('cl.isFeatured = :feat')
            ->andWhere('cl.latitude is not NULL')
            ->andWhere('cl.longitude is not NULL')
            ->setParameter('feat', 1);

        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "fromPrice" || $key == "toPrice") {
                    if ($key == "fromPrice") {
                        $result->andWhere("cl.rateValue >= :fromPrice");
                        $result->setParameter("fromPrice", $val);
                    } else {
                        $result->andWhere("cl.rateValue <= :toPrice");
                        $result->setParameter("toPrice", $val);
                    }
                    continue;
                } elseif ($key == "brandName") {
                    $result->andWhere("cl.name like :brandName");
                    $result->setParameter("brandName", '%' . $val . '%');
                }

                $i++;
            }
        }
        $result->orderBy("cl.rateValue", "ASC");
        $resultSet = $result->getQuery()->getArrayResult();
        $resultSetCount = count($resultSet);
        if (is_array($resultSet) && $resultSetCount > 0) {
            $output['min'] = $resultSet[0]['rateValue'];
            $output['max'] = $resultSet[$resultSetCount - 1]['rateValue'];
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Get random featured listings with min and max rate values
     *
     * @param array $params
     *
     * @return array|bool
     */
    public function getRandomFeaturedMinMaxForFilter($params = null)
    {
        $query = $this->createQueryBuilder("cl")
            ->where('cl.isFeatured = :feat')
            ->setParameter('feat', 1);
        // add min max
        $query = $query->addSelect('MIN(cl.rateValue) as first, Max(cl.rateValue) as last');

        if (isset($params)) {
            $i = 0;
            foreach ($params as $key => $val) {
                // Checks to see if this is a sorting by price range param.
                if ($key == "brandName") {
                    $query->andWhere("cl.name like :brandName");
                    $query->setParameter("brandName", '%' . $val . '%');
                }

                $i++;
            }
        }

        $resultSet = $query->getQuery()->getArrayResult();
        $resultSetCount = count($resultSet);

        if (is_array($resultSet) && $resultSetCount > 0) {
            $output['min'] = $resultSet[0]['first'];
            $output['max'] = $resultSet[0]['last'];

            return $output;
        } else {
            return false;
        }
    }

    /**
     * Find listings with duplicated name
     *
     * @return array
     */
    public function findDuplicateNames()
    {
        return $this->createQueryBuilder('c')
            ->select('c.name, c.id, a.city, a.state, a.zip, p.accountNumber')
            ->where("(select count(sp.name) from MatrixBundle:CombinedListing sp where sp.name = c.name) > 1")
            ->andWhere('c.property is not null')
            ->leftJoin('c.address', 'a')
            ->leftJoin('c.property', 'p')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Count listings with duplicated name
     *
     * @return int
     */
    public function findDuplicateNamesCount()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where("(select count(sp.name) from MatrixBundle:CombinedListing sp where sp.name = c.name) > 1")
            ->andWhere('c.property is not null')
            ->leftJoin('c.address', 'a')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
