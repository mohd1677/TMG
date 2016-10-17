<?php

namespace TMG\Api\GlobalBundle\Utils;

class Slugger
{
    /**
     * Generate a slug from a given string
     *
     * @param string $string
     * @return string
     */
    public function slugify($string)
    {
        $slug = str_replace('.', '', $string);
        $slug = preg_replace('/[^a-z0-9\/]/i', '-', strtolower($slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = str_replace('/', '_', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
