<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\Collection;

trait DoctrineJsonSerializerTrait
{
    /** @var array  */
    public static $metaDatas = [];

    /**
     * @param $manager
     * @param $class
     * @return mixed
     */
    public static function getMetaData($manager, $class)
    {
        if (!isset(self::$metaDatas[$class])) {
            self::$metaDatas[$class] = $manager->getClassMetaData($class);
        }
        return self::$metaDatas[$class];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $manager = self::getManager();
        $md = self::getMetaData($manager, get_called_class());

        $out = [];
        foreach ($md->fieldMappings as $field => $mapping) {
            $out[$field] = $value = $this->$field;
            if ($value instanceof \DateTime) {
                // ISO 8601 dates, ex: 2013-09-26T22:52+00:00
                $out[$field] = $value->format('c');
            }
        }

        $links = [];
        foreach ($md->associationMappings as $field => $mapping) {
            $entity = $this->$field;

            if ($entity instanceof Collection) {
                $ids = [];
                foreach ($entity->toArray() as $child) {
                    $ids[] = $child->getId();
                }
                $links[$field] = $ids;
            } else {
                if (isset($entity)) {
                    $links[$field] = $entity->getId();
                } else {
                    $links[$field] = null;
                }
            }
        }

        if (isset(static::$extraJsonFields)) {
            foreach (static::$extraJsonFields as $fieldName) {
                if (isset($this->$fieldName)) {
                    $out[$fieldName] = $this->$fieldName;
                }
            }
        }

        $out['links'] = $links;

        return $out;
    }
}
