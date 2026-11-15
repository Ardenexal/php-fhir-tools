<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Permission Status
 * URL: http://hl7.org/fhir/ValueSet/permission-status
 * Version: 5.0.0
 * Description: Codes identifying the lifecycle stage of a product.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/permission-status', version: '5.0.0')]
enum PermissionStatus: string
{
    /** Active */
    case active = 'active';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Draft */
    case draft = 'draft';

    /** Rejected */
    case rejected = 'rejected';
}
