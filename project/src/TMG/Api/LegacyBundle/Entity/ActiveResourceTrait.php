<?php
namespace TMG\Api\LegacyBundle\Entity;

trait ActiveResourceTrait
{
    public static function findAll()
    {
        $repo = static::getRepository();
        return call_user_func_array([$repo, __FUNCTION__], func_get_args());
    }

    public static function find()
    {
        $repo = static::getRepository();
        return call_user_func_array([$repo, __FUNCTION__], func_get_args());
    }

    public static function findBy()
    {
        $repo = static::getRepository();
        return call_user_func_array([$repo, __FUNCTION__], func_get_args());
    }

    public static function findOneBy()
    {
        $repo = static::getRepository();
        return call_user_func_array([$repo, __FUNCTION__], func_get_args());
    }

    public function save()
    {
        static::$manager->persist($this);
        return static::$manager->flush($this);
    }
}
