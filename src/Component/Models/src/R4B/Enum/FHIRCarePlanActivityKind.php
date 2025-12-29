<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: Care Plan Activity Kind
 * URL: http://hl7.org/fhir/ValueSet/care-plan-activity-kind
 * Version: 4.3.0
 * Description: Resource types defined as part of FHIR that can be represented as in-line definitions of a care plan activity.
 */
enum FHIRCarePlanActivityKind: string
{
	/** Resource */
	case resource = 'Resource';

	/** Appointment */
	case appointment = 'Appointment';

	/** CommunicationRequest */
	case communicationrequest = 'CommunicationRequest';

	/** DeviceRequest */
	case devicerequest = 'DeviceRequest';

	/** MedicationRequest */
	case medicationrequest = 'MedicationRequest';

	/** NutritionOrder */
	case nutritionorder = 'NutritionOrder';

	/** Task */
	case task = 'Task';

	/** ServiceRequest */
	case servicerequest = 'ServiceRequest';

	/** VisionPrescription */
	case visionprescription = 'VisionPrescription';
}
