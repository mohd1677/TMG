<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class AbstractEntity
{
    use DoctrineJsonSerializerTrait;
    use ActiveResourceTrait;

    protected static $manager;
    protected static $app;

    /**
     * @param $app
     */
    public static function setApp($app)
    {
        self::$manager = null;
        self::$app = $app;
    }

    /**
     * @return EntityRepository
     */
    public static function getRepository()
    {
        return self::getManager()->getRepository(get_called_class());
    }

    /**
     * @return EntityManager
     */
    public static function getManager()
    {
        if (!self::$manager && self::$app) {
            self::$manager = self::$app['orm.em'];
        }
        return self::$manager;
    }

    /**
     * @param EntityManager $manager
     */
    public static function setManager(EntityManager $manager)
    {
        self::$manager = $manager;
    }

    /**
     * @param $text
     *
     * @return mixed|string
     */
    public static function slugifyString($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        return (empty($text)) ? 'n-a' : $text;
    }

    abstract public function getId();
}
