<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ExampleScenarioActorType
 * URL: http://hl7.org/fhir/ValueSet/examplescenario-actor-type
 * Version: 4.3.0
 * Description: The type of actor - system or human.
 */
enum ExampleScenarioActorType: string
{
    /** Person */
    case person = 'person';

    /** System */
    case system = 'entity';
}
