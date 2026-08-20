<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ExampleScenarioActorType
 * URL: http://hl7.org/fhir/ValueSet/examplescenario-actor-type
 * Version: 4.0.1
 * Description: The type of actor - system or human.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/examplescenario-actor-type', version: '4.0.1')]
enum ExampleScenarioActorType: string
{
    /** Person */
    case person = 'person';

    /** System */
    case system = 'entity';
}
