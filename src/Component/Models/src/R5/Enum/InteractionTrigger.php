<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Interaction Trigger
 * URL: http://hl7.org/fhir/ValueSet/interaction-trigger
 * Version: 5.0.0
 * Description: FHIR RESTful interaction codes used for SubscriptionTopic trigger.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/interaction-trigger', version: '5.0.0')]
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
