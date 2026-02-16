<?php

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;

return [
    FrameworkBundle::class => ['all' => true],
    MakerBundle::class     => ['dev' => true],
    FHIRBundle::class      => ['all' => true],
];
