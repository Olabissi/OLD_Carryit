<?php

namespace Cit\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CitUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}