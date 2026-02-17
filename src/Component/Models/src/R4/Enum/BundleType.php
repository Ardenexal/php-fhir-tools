<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: BundleType
 * URL: http://hl7.org/fhir/ValueSet/bundle-type
 * Version: 4.0.1
 * Description: Indicates the purpose of a bundle - how it is intended to be used.
 */
enum BundleType: string
{
    /** Document */
    case document = 'document';

    /** Message */
    case message = 'message';

    /** Transaction */
    case transaction = 'transaction';

    /** Transaction Response */
    case transactionresponse = 'transaction-response';

    /** Batch */
    case batch = 'batch';

    /** Batch Response */
    case batchresponse = 'batch-response';

    /** History List */
    case historylist = 'history';

    /** Search Results */
    case searchresults = 'searchset';

    /** Collection */
    case collection = 'collection';
}
