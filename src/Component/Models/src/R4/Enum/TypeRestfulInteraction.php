<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TypeRestfulInteraction
 * URL: http://hl7.org/fhir/ValueSet/type-restful-interaction
 * Version: 4.0.1
 * Description: Operations supported by REST at the type or instance level.
 */
enum TypeRestfulInteraction: string
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

    /** history-instance */
    case historyinstance = 'history-instance';

    /** history-type */
    case historytype = 'history-type';

    /** search-type */
    case searchtype = 'search-type';
}
