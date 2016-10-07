<?php

namespace TMG\Api\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use TMG\Api\GlobalBundle\Controller\GlobalController;

use Doctrine\Common\Collections\ArrayCollection;

use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\TollFree;
use TMG\Api\ApiBundle\Entity\ProductTypes;
use TMG\Api\ApiBundle\Entity\IHGProperty;
use TMG\Api\ApiBundle\Entity\Social;
use TMG\Api\ApiBundle\Entity\Amenities;

class InfoController extends GlobalController
{
    // Entity
    protected $propertyRepo;
    protected $tollFreeRepo;
    protected $productTypesRepo;
    protected $ihgRepo;
    protected $socialRepo;
    protected $amenityRepo;

    // Toll Free
    protected $tollFreeClass= 'TMG\Api\ApiBundle\Entity\TollFree';
    protected $socialClass= 'TMG\Api\ApiBundle\Entity\Social';

    public function initialize()
    {
        $this->propertyRepo =$this->em->getRepository('ApiBundle:Property');
        $this->tollFreeRepo = $this->em->getRepository('ApiBundle:TollFree');
        $this->productTypesRepo = $this->em->getRepository('ApiBundle:ProductTypes');
        $this->ihgRepo = $this->em->getRepository('ApiBundle:IHGProperty');
        $this->socialRepo = $this->em->getRepository('ApiBundle:Social');
        $this->amenityRepo = $this->em->getRepository('ApiBundle:Amenities');
        $this->addingfortestinggitcommitinbranch = $this->em->getRepository('ApiBundle:Amenities');
    }

    // Phone
    public function updatePhoneAction(Request $request)
    {
        $result = null;
        $phone = $request->request->get('phone');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));
        if ($property) {
            $property->setPhone($phone);
            $this->em->flush();
            $result = $property->getPhone();
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result
        ]);
    }

    // Toll Free
    public function updateTollFreeAction(Request $request)
    {
        $result = null;
        $tfNum = $request->request->get('toll-free');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));


        if ($property) {
            $pType = $this->productTypesRepo->findOneById(2);

            $tollFree = $this->tollFreeRepo->findOneBy(array(
                'property' => $property,
                'type' => $pType,
            ));
            if (!$tollFree) {
                $tollFree = new $this->tollFreeClass;
                $tollFree->setType($pType);
                $tollFree->setProperty($property);
                $this->em->persist($tollFree);
            }
            $tollFree->setNumber($tfNum);
            $this->em->flush();
            $result = $tollFree->getNumber();
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Geocode
    public function updateGeocodeAction(Request $request)
    {
        $result = null;
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));


        if ($property) {
            $address = $property->getAddress();
            if ($address) {
                $address->setLatitude($lat);
                $address->setLongitude($lon);
                $this->em->flush();
                $result = array($address->getLatitude(), $address->getLongitude());
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }


    // Brief Description
    public function updateBriefAction(Request $request)
    {
        $result = null;
        $brief = $request->request->get('brief');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $description = $property->getDescription();
            if ($description) {
                $description->setBriefDescription($brief);
                $this->em->flush();
                $result = $description->getBriefDescription();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Description
    public function updateDescriptionAction(Request $request)
    {
        $result = null;
        $full = $request->request->get('full');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $description = $property->getDescription();
            if ($description) {
                $description->setDescription($full);
                $this->em->flush();
                $result = $description->getDescription();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Directions
    public function updateDirectionsAction(Request $request)
    {
        $result = null;
        $directions = $request->request->get('directions');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $description = $property->getDescription();
            if ($description) {
                $description->setDirections($directions);
                $this->em->flush();
                $result = $description->getDirections();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Disclaimer
    public function updateDisclaimerAction(Request $request)
    {
        $result = null;
        $disclaimer = $request->request->get('disclaimer');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $description = $property->getDescription();
            if ($description) {
                $description->setRestrictions($disclaimer);
                $this->em->flush();
                $result = $description->getRestrictions();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Email
    public function updateEmailAction(Request $request)
    {
        $result = null;
        $email = $request->request->get('email');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $property->setEmail($email);
            $this->em->flush();
            $result = $property->getEmail();
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Url
    public function updateUrlAction(Request $request)
    {
        $result = null;
        $url = $request->request->get('url');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $description = $property->getDescription();
            if ($description) {
                $description->setUrl($url);
                $this->em->flush();
                $result = $description->getUrl();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Ihg
    public function updateIhgAction(Request $request)
    {
        $result = null;
        $ihg = $request->request->get('ihg');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $ihgProp = null;
            if ($ihg) {
                $ihgProp = $this->ihgRepo->findOneBy(array(
                    'hotelCode' => $ihg,
                ));
                if ($ihgProp) {
                    $ihgProp->setProperty($property);
                    $this->em->flush();
                    $result = $ihgProp->getHotelCode();
                }
            } else {
                $ihgProp = $this->ihgRepo->findOneBy(array(
                    'property' => $property,
                ));
                if ($ihgProp) {
                    $ihgProp->removeProperty();
                    $this->em->flush();
                    $result = true;
                }
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Social
    public function updateSocialAction(Request $request)
    {
        $result = null;
        $url = $request->request->get('social');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $social = $property->getSocial();
            if ($social) {
                $social->setUrl($url);
                $this->em->flush();
                $result = $social->getUrl();
            } else {
                $social = new $this->socialClass;
                $social->setProperty($property);
                $social->setActive(0);
                $social->setUrl($url);
                $this->em->persist($social);
                $this->em->flush();
                $result = $social->getUrl();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }


    // Rate Lock
    public function updateLockAction(Request $request)
    {
        $result = null;
        $lock = $request->request->get('lock');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $property->setRateLock((int) $lock);
            $this->em->flush();
            $result = 'true';
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Modify Featured Amenities
    public function modifyFeaturedAction(Request $request)
    {
        $result = null;
        $featured = $request->request->get('featured');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $property->setFeaturedAmenities($featured);
            $this->em->flush();
            $result = $property->getFeaturedAmenities();
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }


    // Modify Amenities
    public function modifyAmenitiesAction(Request $request)
    {
        $result = null;
        $aName = $request->request->get('name');
        $action = $request->request->get('action');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $amenity = $this->amenityRepo->findOneBy(array(
                'name' => $aName,
            ));
            if ($amenity) {
                if ($action == 'add') {
                    $property->addAmenity($amenity);
                    $this->em->flush();
                    $result = 'add';
                } elseif ($action == 'remove') {
                    if ($property->hasAmenity($amenity)) {
                        $property->removeAmenity($amenity);
                        $this->em->flush();
                    }
                    $result = 'remove';
                }
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }


    // Show Online
    public function updateOnlineAction(Request $request)
    {
        $result = null;
        $show = $request->request->get('show');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $property->setForceLive((int) $show);
            $this->em->flush();
            $result = 'true';
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }

    // Display Interstate Exit
    public function modifyDisplayAction(Request $request)
    {
        $result = null;
        $interstate = $request->request->get('interstate');
        $exit = $request->request->get('exit');
        $hash = $request->request->get('hash');

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $hash,
        ));

        if ($property) {
            $address = $property->getAddress();
            if ($address) {
                $intExit = null;
                if ($interstate && $exit) {
                    $intExit = $interstate.' / '.$exit;
                } elseif ($interstate) {
                    $intExit = $interstate;
                } elseif ($exit) {
                    $intExit = $exit;
                }
                $address->setDisplayInterstateExit($intExit);
                $this->em->flush();
                $result = $address->getDisplayInterstateExit();
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }
}
