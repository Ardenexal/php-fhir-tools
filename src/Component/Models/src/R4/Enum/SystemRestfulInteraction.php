<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SystemRestfulInteraction
 * URL: http://hl7.org/fhir/ValueSet/system-restful-interaction
 * Version: 4.0.1
 * Description: Operations supported by REST at the system level.
 */
enum SystemRestfulInteraction: string
{
    /** read */
    case read = 'read';

    /** vread */
    case vread = 'vread';

    /** update */
    case update = 'update';

    /** patch */
    case patch = 'patch';

    /** delete */
    case delete = 'delete';

    /** history */
    case history = 'history';

    /** create */
    case create = 'create';

    /** search */
    case search = 'search';

    /** capabilities */
    case capabilities = 'capabilities';

    /** transaction */
    case transaction = 'transaction';

    /** batch */
    case batch = 'batch';

    /** operation */
    case operation = 'operation';

    /** search-system */
    case searchsystem = 'search-system';

    /** history-system */
    case historysystem = 'history-system';
}
