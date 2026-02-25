<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Example Scenario Actor Type
 * URL: http://hl7.org/fhir/ValueSet/examplescenario-actor-type
 * Version: 5.0.0
 * Description: The type of actor - system or human.
 */
enum ExampleScenarioActorType: string
{
    /** Person */
    case person = 'person';

    /** System */
    case system = 'system';
}
