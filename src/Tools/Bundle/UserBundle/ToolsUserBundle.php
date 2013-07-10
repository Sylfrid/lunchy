<?php

namespace Tools\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ToolsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
