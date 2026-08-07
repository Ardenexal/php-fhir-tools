<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: System Restful Interaction
 * URL: http://hl7.org/fhir/ValueSet/system-restful-interaction
 * Version: 5.0.0
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

    /** history-instance */
    case historyinstance = 'history-instance';

    /** history-type */
    case historytype = 'history-type';

    /** history-system */
    case historysystem = 'history-system';

    /** create */
    case create = 'create';

    /** search */
    case search = 'search';

    /** search-type */
    case searchtype = 'search-type';

    /** search-system */
    case searchsystem = 'search-system';

    /** search-compartment */
    case searchcompartment = 'search-compartment';

    /** capabilities */
    case capabilities = 'capabilities';

    /** transaction */
    case transaction = 'transaction';

    /** batch */
    case batch = 'batch';

    /** operation */
    case operation = 'operation';
}
