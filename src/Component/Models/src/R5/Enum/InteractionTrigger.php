<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Interaction Trigger
 * URL: http://hl7.org/fhir/ValueSet/interaction-trigger
 * Version: 5.0.0
 * Description: FHIR RESTful interaction codes used for SubscriptionTopic trigger.
 */
enum InteractionTrigger: string
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
}
