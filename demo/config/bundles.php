<?php

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\UX\Turbo\TurboBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;

return [
    FrameworkBundle::class   => ['all' => true],
    DebugBundle::class       => ['dev' => true],
    TwigBundle::class        => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    StimulusBundle::class    => ['all' => true],
    TurboBundle::class       => ['all' => true],
    TwigExtraBundle::class   => ['all' => true],
    MakerBundle::class       => ['dev' => true],
    FHIRBundle::class        => ['all' => true],
];
