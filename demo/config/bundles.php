<?php

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

return [
    FrameworkBundle::class => ['all' => true],
    FHIRBundle::class      => ['all' => true],
];
