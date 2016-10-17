<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\ResolveReviewTag;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class ResolveReviewTagHandler extends ApiHandler
{
    /**
     * @param $resolveReviewTagCollection
     *
     * @return array
     */
    public function stringifyReviewTagIndices($resolveReviewTagCollection)
    {
        $resolveReviewTags = [];
        /** @var ResolveReviewTag $resolveReviewTag */
        foreach ($resolveReviewTagCollection as $resolveReviewTag) {
            $resolveReviewTags[$resolveReviewTag->getResolveTag()->getHash()] = [
                'tag' => $resolveReviewTag->getResolveTag()->getTag(),
                'value' => $resolveReviewTag->getValue(),
            ];
        }
        return $resolveReviewTags;
    }
}
