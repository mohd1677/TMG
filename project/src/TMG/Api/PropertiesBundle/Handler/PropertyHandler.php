<?php

namespace TMG\Api\PropertiesBundle\Handler;

use FOS\RestBundle\Request\ParamFetcher;
use TMG\Api\LegacyBundle\Entity\PropertyPhoto;
use TMG\Api\ApiBundle\Entity\Repository\PropertyRepository;
use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\ApiBundle\Util\PagingInfo;
use TMG\Api\LegacyBundle\Entity\CombinedListing;

class PropertyHandler extends ApiHandler
{
    /** @var PropertyRepository $repository */
    protected $repository;
    /**
     * @param array $resolveContracts
     * @return array
     */
    public function getActiveResolveProperties(array $resolveContracts)
    {
        return $this->repository->findActivePropertiesByContracts($resolveContracts);
    }
    
    protected $propertyRepo;
    protected $contractRepo;
    protected $reputationRepo;
    protected $socialRepo;
    protected $tollFreeRepo;
    /**
     * @param $hash
     * @return json
     */
    public function checkPropertyByHash($hash)
    {
        $results = array(
            'reputation' => false,
            'social' => false,
            'toll-free' => false,
            'online-contract' => false,
        );

        $this->propertyRepo = $this->em->getRepository('ApiBundle:Property');
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
        $this->reputationRepo = $this->em->getRepository('ApiBundle:Reputation');
        $this->socialRepo = $this->em->getRepository('ApiBundle:Social');
        $this->tollFreeRepo = $this->em->getRepository('ApiBundle:TollFree');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));
        if ($property) {
            $propId = $property->getId();
            $reputation = $this->reputationRepo->activeReputation($propId);
            if ($reputation) {
                $results['reputation'] = $reputation['active'];
            }
            $social = $this->socialRepo->activeSocial($propId);
            if ($social) {
                $results['social'] = $social['active'];
            }
            $tollFree = $this->tollFreeRepo->activeTollFree($propId);
            if ($tollFree) {
                $results['toll-free'] = true;
            }
            $hasOnlineContract = $this->contractRepo->hasOnlineContract($propId);
            if ($hasOnlineContract) {
                $results['online-contract'] = true;
            }
        }

        return $results;
    }

    /**
     * @param CombinedListing $combinedListing
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function formatListing($combinedListing, $paramFetcher)
    {
        $s3_url = $this->container->getParameter('s3_url');
        $photos = [];
        $recentDate = new \DateTime('-1 day');
        $recentTimestamp = $recentDate->getTimestamp();
        $updatedTimestamp = $combinedListing->getActiveAt()->getTimestamp();
        $recentlyUpdated = $recentTimestamp <= $updatedTimestamp;
        $lat = $paramFetcher->get('lat');
        $long = $paramFetcher->get('long');
        $distance = 0;

        if ($lat && $long) {
            $distance = $this->distance($lat, $long, $combinedListing->getLatitude(), $combinedListing->getLongitude());
        }

        // Default to whatever is in combined listings. (null or not)
        $phoneNumber = $combinedListing->getPhoneNumber();

        // If the listing is an IHG only listing, we'll use the standard IHG phone number.
        if ($combinedListing->getIhgProperty() && !$combinedListing->getProperty()) {
            $phoneNumber = '(877) 626-2021';
        }

        // If the listing has an 800 number associated with it, we'll use that.
        if ($combinedListing->getProperty() && $combinedListing->getProperty()->getTollFreeActive()) {
            $phoneNumber = $combinedListing->getProperty()->getTollFreePhone();
        }

        foreach ($combinedListing->getPhotos() as $photo) {
            /** @var PropertyPhoto $photo */
            $photos[] = [
                'is_banner_img' => $photo->getIsBannerImg(),
                'url_extra_large' => $s3_url.$photo->getUrlExtraLarge(),
                'url_extra_small' => $s3_url.$photo->getUrlExtraSmall(),
                'url_large' => $s3_url.$photo->getUrlLarge(),
                'url_large_featured' => $s3_url.$photo->getUrlLargeFeatured(),
                'url_medium' => $s3_url.$photo->getUrlMedium(),
                'url_original' => $s3_url.$photo->getUrlOriginal(),
                'url_small' => $s3_url.$photo->getUrlSmall(),
                'url_small_featured' => $s3_url.$photo->getUrlSmallFeatured(),
            ];
        }

        return [
            'address' => $combinedListing->getAddress(),
            'amenities' => $combinedListing->getAmenities(),
            'description' => $combinedListing->getDescription(),
            'distance' => $distance,
            'featured' => $combinedListing->getIsFeatured(),
            'id' => $combinedListing->getId(),
            'lat' => $combinedListing->getLatitude(),
            'long' => $combinedListing->getLongitude(),
            'name' => $combinedListing->getName(),
            'phone' => $phoneNumber,
            'photos' => $photos,
            'rate' => $combinedListing->getRateValue(),
            'rate_pretty' => $combinedListing->getRatePretty(),
            'rate_type' => $combinedListing->getRateType(),
            'recently_updated' => $recentlyUpdated,
            'video' => $combinedListing->getVideo(),
        ];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPropertyById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param array $criteria
     * @param PagingInfo $pagingInfo
     * @return mixed
     */
    public function getAllProperties(array $criteria, PagingInfo $pagingInfo)
    {
        return $this->repository->findForProperties($criteria, $pagingInfo);
    }

    /**
     * calculate distance between coordinate pairs...should be a util?
     *
     * @param $lat1
     * @param $long1
     * @param $lat2
     * @param $long2
     * @param $unit
     * @return float
     */
    private function distance($lat1, $long1, $lat2, $long2, $unit = 'M')
    {
        $dist = rad2deg(
            acos(
                sin(deg2rad($lat1)) *
                sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) *
                cos(deg2rad($lat2)) *
                cos(deg2rad($long1 - $long2))
            )
        );

        $miles = $dist * 60 * 1.1515;

        switch (strtoupper($unit)) {
            case "K":
                //kilometers
                return ($miles * 1.609344);
                break;

            case "N":
                //nautical miles
                return ($miles * 0.8684);
                break;

            default:
                //miles
                return $miles;
                break;
        }
    }
}
