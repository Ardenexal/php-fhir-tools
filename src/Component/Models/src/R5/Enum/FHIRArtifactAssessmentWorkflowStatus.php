<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Artifact Assessment Workflow Status
 * URL: http://hl7.org/fhir/ValueSet/artifactassessment-workflow-status
 * Version: 5.0.0
 * Description: Possible values for the workflow status of the comment or assessment, typically used to coordinate workflow around the process of accepting and rejecting changes and comments on the artifact.
 */
enum FHIRArtifactAssessmentWorkflowStatus: string
{
    /** Submitted */
    case submitted = 'submitted';

    /** Triaged */
    case triaged = 'triaged';

    /** Waiting for Input */
    case waitingforinput = 'waiting-for-input';

    /** Resolved - No Change */
    case resolvednochange = 'resolved-no-change';

    /** Resolved - Change Required */
    case resolvedchangerequired = 'resolved-change-required';

    /** Deferred */
    case deferred = 'deferred';

    /** Duplicate */
    case duplicate = 'duplicate';

    /** Applied */
    case applied = 'applied';

    /** Published */
    case published = 'published';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
