<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ResearchStudyStatus
 * URL: http://hl7.org/fhir/ValueSet/research-study-status
 * Version: 4.0.1
 * Description: Codes that convey the current status of the research study.
 */
enum ResearchStudyStatus: string
{
    /** Active */
    case active = 'active';

    /** Administratively Completed */
    case administrativelycompleted = 'administratively-completed';

    /** Approved */
    case approved = 'approved';

    /** Closed to Accrual */
    case closedtoaccrual = 'closed-to-accrual';

    /** Closed to Accrual and Intervention */
    case closedtoaccrualandintervention = 'closed-to-accrual-and-intervention';

    /** Completed */
    case completed = 'completed';

    /** Disapproved */
    case disapproved = 'disapproved';

    /** In Review */
    case inreview = 'in-review';

    /** Temporarily Closed to Accrual */
    case temporarilyclosedtoaccrual = 'temporarily-closed-to-accrual';

    /** Temporarily Closed to Accrual and Intervention */
    case temporarilyclosedtoaccrualandintervention = 'temporarily-closed-to-accrual-and-intervention';

    /** Withdrawn */
    case withdrawn = 'withdrawn';
}
