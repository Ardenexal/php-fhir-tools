<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Artifact Assessment Information Type
 * URL: http://hl7.org/fhir/ValueSet/artifactassessment-information-type
 * Version: 5.0.0
 * Description: The type of information contained in a component of an artifact assessment.
 */
enum ArtifactAssessmentInformationType: string
{
	/** Comment */
	case comment = 'comment';

	/** Classifier */
	case classifier = 'classifier';

	/** Rating */
	case rating = 'rating';

	/** Container */
	case container = 'container';

	/** Response */
	case response = 'response';

	/** Change Request */
	case changerequest = 'change-request';
}
