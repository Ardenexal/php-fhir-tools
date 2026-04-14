<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Request Resource Types
 * URL: http://hl7.org/fhir/ValueSet/request-resource-types
 * Version: 5.0.0
 * Description: All Resource Types that represent request resources
 */
enum RequestResourceTypes: string
{
    /** Base */
    case base = 'Base';

    /** Appointment */
    case appointment = 'Appointment';

    /** AppointmentResponse */
    case appointmentresponse = 'AppointmentResponse';

    /** CarePlan */
    case careplan = 'CarePlan';

    /** Claim */
    case claim = 'Claim';

    /** CommunicationRequest */
    case communicationrequest = 'CommunicationRequest';

    /** CoverageEligibilityRequest */
    case coverageeligibilityrequest = 'CoverageEligibilityRequest';

    /** DeviceRequest */
    case devicerequest = 'DeviceRequest';

    /** EnrollmentRequest */
    case enrollmentrequest = 'EnrollmentRequest';

    /** ImmunizationRecommendation */
    case immunizationrecommendation = 'ImmunizationRecommendation';

    /** MedicationRequest */
    case medicationrequest = 'MedicationRequest';

    /** NutritionOrder */
    case nutritionorder = 'NutritionOrder';

    /** RequestOrchestration */
    case requestorchestration = 'RequestOrchestration';

    /** ServiceRequest */
    case servicerequest = 'ServiceRequest';

    /** SupplyRequest */
    case supplyrequest = 'SupplyRequest';

    /** Task */
    case task = 'Task';

    /** Transport */
    case transport = 'Transport';

    /** VisionPrescription */
    case visionprescription = 'VisionPrescription';
}
