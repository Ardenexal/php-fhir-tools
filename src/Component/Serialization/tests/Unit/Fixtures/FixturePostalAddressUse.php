<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures;

/**
 * Stand-in for a generated CDA ValueSet enum used in a V3 SET<cs> attribute (e.g. AD.use), where
 * one XML attribute carries several codes space-delimited.
 */
enum FixturePostalAddressUse: string
{
    case home    = 'H';
    case work    = 'WP';
    case primary = 'PHYS';
}
