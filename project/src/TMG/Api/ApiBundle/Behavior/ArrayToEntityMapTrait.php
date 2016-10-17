<?php
/**
 * ArrayToEntityMapTrait
 */
namespace TMG\Api\ApiBundle\Behavior;

/**
 * Trait ArrayToEntityMapTrait
 *
 * @package Behavior
 */
trait ArrayToEntityMapTrait
{
    /**
     * @var string
     *
     * The regular expression used to detect Zalgo text
     */
    private $zalgoRegex = '/\p{Mn}+/u';

    /**
     * This function maps $parameters -> $entity
     *
     * @param object $entity           The entity to map $parameters to
     * @param array  $parameters       The parameters being mapped to entity
     * @param array  $validParameters  The valid parameters for this mapping operation
     * @param bool   $isPatch          If this is true, missing $valid_parameters will not be nullified
     *
     * @return object
     */
    protected function mapArrayToEntity($entity, array $parameters, array $validParameters = [], $isPatch = false)
    {
        if (!count($validParameters)) {
            return $this->mapParameters($entity, $parameters, $isPatch);
        }

        return $this->mapValidParameters($entity, $parameters, $validParameters, $isPatch);
    }

    /**
     * Loops over only the parameters
     *
     * @param       $entity
     * @param array $parameters
     * @param       $isPatch
     *
     * @return mixed
     */
    private function mapParameters($entity, array $parameters, $isPatch = false)
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $setter = 'set' . str_replace(' ', '', ucwords(str_replace("_", " ", $key)));

            if (method_exists($entity, $setter)) {
                if (!is_null($value)) {
                    $entity->$setter($this->stripZalgoChars($value));
                } elseif (!$isPatch) {
                    $entity->$setter(null);
                }
            }
        }

        return $entity;
    }

    /**
     * Loops over valid parameters
     *
     * @param       $entity
     * @param array $parameters
     * @param array $validParameters
     * @param       $isPatch
     *
     * @return mixed
     */
    private function mapValidParameters($entity, array $parameters, array $validParameters = [], $isPatch = false)
    {
        foreach ($validParameters as $key) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace("_", " ", $key)));

            if (method_exists($entity, $setter)) {
                if (isset($parameters[$key])) {
                    $entity->$setter($this->stripZalgoChars($parameters[$key]));
                } elseif (!$isPatch) {
                    $entity->$setter(null);
                }
            }
        }

        return $entity;
    }

    /**
     * Extracts the requested parameters from the $entity and returns a key-value array
     *
     * @param       $entity
     * @param array $validParameters
     *
     * @return array The parsed data
     */
    public function mapEntityToArray($entity, array $validParameters = [])
    {
        $data = [];
        foreach ($validParameters as $key) {
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace("_", " ", $key)));

            if (method_exists($entity, $getter)) {
                $data[$key] = $entity->$getter();
            }
        }

        return $data;
    }

    /**
     * Strips Zalgo text from an input string.
     * If $input is not a string, just return $input.
     *
     * @param mixed $input
     * @return mixed
     */
    protected function stripZalgoChars($input)
    {
        if (is_string($input)) {
            return preg_replace($this->zalgoRegex, '', $input);
        }

        return $input;
    }
}
