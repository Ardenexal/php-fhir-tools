<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Artifact Assessment Disposition
 * URL: http://hl7.org/fhir/ValueSet/artifactassessment-disposition
 * Version: 5.0.0
 * Description: Possible values for the disposition of a comment or change request, typically used for comments and change requests, to indicate the disposition of the responsible party towards the changes suggested by the comment or change request.
 */
enum FHIRArtifactAssessmentDisposition: string
{
	/** Unresolved */
	case unresolved = 'unresolved';

	/** Not Persuasive */
	case notpersuasive = 'not-persuasive';

	/** Persuasive */
	case persuasive = 'persuasive';

	/** Persuasive with Modification */
	case persuasivewithmodification = 'persuasive-with-modification';

	/** Not Persuasive with Modification */
	case notpersuasivewithmodification = 'not-persuasive-with-modification';
}
