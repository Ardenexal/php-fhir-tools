<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: RequestResourceType
 * URL: http://hl7.org/fhir/ValueSet/request-resource-types
 * Version: 4.0.1
 * Description: A list of all the request resource types defined in this version of the FHIR specification.
 */
enum RequestResourceType: string
{
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

    /** Contract */
    case contract = 'Contract';

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

    /** ServiceRequest */
    case servicerequest = 'ServiceRequest';

    /** SupplyRequest */
    case supplyrequest = 'SupplyRequest';

    /** Task */
    case task = 'Task';

    /** VisionPrescription */
    case visionprescription = 'VisionPrescription';
}
