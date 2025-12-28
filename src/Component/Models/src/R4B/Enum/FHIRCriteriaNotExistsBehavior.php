<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: CriteriaNotExistsBehavior
 * URL: http://hl7.org/fhir/ValueSet/subscriptiontopic-cr-behavior
 * Version: 4.3.0
 * Description: Behavior a server can exhibit when a criteria state does not exist (e.g., state prior to a create or after a delete).
 */
enum FHIRCriteriaNotExistsBehavior: string
{
    /** test passes */
    case testpasses = 'test-passes';

    /** test fails */
    case testfails = 'test-fails';
}
