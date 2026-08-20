<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CriteriaNotExistsBehavior
 * URL: http://hl7.org/fhir/ValueSet/subscriptiontopic-cr-behavior
 * Version: 4.3.0
 * Description: Behavior a server can exhibit when a criteria state does not exist (e.g., state prior to a create or after a delete).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/subscriptiontopic-cr-behavior', version: '4.3.0')]
enum CriteriaNotExistsBehavior: string
{
    /** test passes */
    case testpasses = 'test-passes';

    /** test fails */
    case testfails = 'test-fails';
}
