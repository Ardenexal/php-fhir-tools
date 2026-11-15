<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Criteria Not Exists Behavior
 * URL: http://hl7.org/fhir/ValueSet/subscriptiontopic-cr-behavior
 * Version: 5.0.0
 * Description: Behavior a server can exhibit when a criteria state does not exist (e.g., state prior to a create or after a delete).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/subscriptiontopic-cr-behavior', version: '5.0.0')]
enum CriteriaNotExistsBehavior: string
{
    /** Test passes */
    case testpasses = 'test-passes';

    /** Test fails */
    case testfails = 'test-fails';
}
