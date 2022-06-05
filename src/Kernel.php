<?php

/**
 * Author: Johan Mickaël
 */

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    
    // Overrinding the boot methods and retrieving the timezone parameter from the container
    public function boot(): void
    {
        parent::boot();
        date_default_timezone_set($this->getContainer()->getParameter('timezone'));
    }
}
