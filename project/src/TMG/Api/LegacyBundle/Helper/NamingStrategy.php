<?php
namespace TMG\Api\LegacyBundle\Helper;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Mapping\NamingStrategy as NamingStrategyInterface;

class NamingStrategy implements NamingStrategyInterface
{
    // My\Namespaced\DatabaseEntity -> database_entities
    public function classToTableName($className)
    {
        $className = explode("\\", $className);
        return Inflector::pluralize(Inflector::tableize(array_pop($className)));
    }
    public function propertyToColumnName($propertyName, $className = null)
    {
        return Inflector::tableize($propertyName);
    }
    public function referenceColumnName()
    {
        return 'id';
    }
    public function joinColumnName($propertyName)
    {
        return Inflector::tableize($propertyName) . '_id';
    }
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null)
    {
        $name = $this->classToTableName($sourceEntity) . '_' . $this->classToTableName($targetEntity);
        if ($propertyName != $this->classToTableName($targetEntity)) {
            $name .= "_as_$propertyName";
        }
        return $name;
    }
    public function joinKeyColumnName($entityName, $referencedColumnName = null)
    {
        return $this->classToTableName($entityName) . '_' . ($referencedColumnName ?: 'id');
    }
    public function embeddedFieldToColumnName(
        $propertyName,
        $embeddedColumnName,
        $className = null,
        $embeddedClassName = null
    ) {
        return $propertyName;
    }
}
