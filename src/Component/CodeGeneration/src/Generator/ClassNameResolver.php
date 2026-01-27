<?php

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use function Symfony\Component\String\u;

class ClassNameResolver
{
    private const array DEFINITION_TO_CLASS_OVERRIDES = [
        'http://hl7.org/fhir/ValueSet/claim-use' => 'ClaimUse',
    ];

    public static function resolveClassName(string $definitionUrl, string $definitionName): string
    {
        return self::DEFINITION_TO_CLASS_OVERRIDES[$definitionUrl] ?? u($definitionName)->pascal()->toString();
    }
}
