<?php

namespace TMG\Api\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use TMG\Api\ApiBundle\DataFixtures\AbstractFixture;
use TMG\Api\ApiBundle\Entity\ResolveTag;

class ResolveTagFixture extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return mixed
     */
    public function run(ObjectManager $manager)
    {
        $this->createResolveTags($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function createResolveTags(ObjectManager $manager)
    {
        $tagCollection = $this->loadData("resolveTag.json");

        foreach ($tagCollection as $tagData) {
            $tag = $this->loadRecord(['tag' => $tagData->tag], 'tag');
            if (!$tag) {
                $tag = new ResolveTag();
                $tag->setTag($tagData->tag);
                $tag->setSource($tagData->source);
            }

            $tag->setDescription($tagData->description);

            $manager->persist($tag);
        }

        $manager->flush();
    }

    /**
     * Finds ResolveTag with matching tag
     *
     * @param ObjectManager $manager   The Doctrine ObjectManager
     * @param string        $queryName The name of the query to return
     *
     * @return mixed
     */
    protected function getQuery(ObjectManager $manager, $queryName = null)
    {
        /** @var EntityManager $qb */
        $qb = $this->container->get('doctrine')->getEntityManager();

        if ($queryName == 'tag') {
            return $qb
                ->createQueryBuilder()
                ->select('t')
                ->from('TMG\Api\ApiBundle\Entity\ResolveTag', 't')
                ->where('t.tag = :tag');
        } else {
            return null;
        }
    }
}
