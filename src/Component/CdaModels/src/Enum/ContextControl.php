<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAContextControl
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAContextControl
 * Version: 2.0.2-sd
 * Description: A code that specifies how an ActRelationship or Participation contributes to the context of an Act, and whether it may be propagated to descendent Acts whose association allows such propagation.
 */
enum ContextControl: string
{
    /** AN */
    case an = 'AN';

    /** AP */
    case ap = 'AP';

    /** ON */
    case on = 'ON';

    /** OP */
    case op = 'OP';
}
