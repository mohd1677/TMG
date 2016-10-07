<?php

namespace TMG\Api\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
