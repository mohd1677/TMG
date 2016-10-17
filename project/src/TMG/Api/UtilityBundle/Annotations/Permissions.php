<?php

namespace TMG\Api\UtilityBundle\Annotations;

use TMG\Api\ApiBundle\Exception as Exception;

/**
 * Class Permissions
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @package Annotations
 */
class Permissions
{
    /**
     * The array of valid permissions
     *ste
     * @var array
     */
    private $permissions = [];

    /**
     * Check annotations for string integrity. Then add to permissions array.
     *
     * @param array $values
     *
     * @throws Exception\InternalServerErrorHttpException
     */
    public function __construct(array $values)
    {
        if (is_string($values['value'])) {
            $this->permissions[] = strtolower($values['value']);
        } elseif (is_array($values['value'])) {
            $this->permissions = array_map(
                function ($n) {
                
                    if (!is_string($n)) {
                        throw new Exception\InternalServerErrorHttpException('All permissions must be strings.');
                    }
                    return strtolower($n);
                },
                $values['value']
            );
        } else {
            throw new Exception\InternalServerErrorHttpException('Permissions must be a string or an array or strings');
        }

    }

    /**
     * Return the permissions array or false if empty
     * @return mixed Array of permissions or false
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
