<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Related Artifact Type All
 * URL: http://hl7.org/fhir/ValueSet/related-artifact-type-all
 * Version: 5.0.0
 * Description: The type of relationship to the cited artifact.
 */
enum FHIRRelatedArtifactTypeExpanded: string
{
    /** Documentation */
    case documentation = 'documentation';

    /** Justification */
    case justification = 'justification';

    /** Citation */
    case citation = 'citation';

    /** Predecessor */
    case predecessor = 'predecessor';

    /** Successor */
    case successor = 'successor';

    /** Derived From */
    case derivedfrom = 'derived-from';

    /** Depends On */
    case dependson = 'depends-on';

    /** Composed Of */
    case composedof = 'composed-of';

    /** Part Of */
    case partof = 'part-of';

    /** Amends */
    case amends = 'amends';

    /** Amended With */
    case amendedwith = 'amended-with';

    /** Appends */
    case appends = 'appends';

    /** Appended With */
    case appendedwith = 'appended-with';

    /** Cites */
    case cites = 'cites';

    /** Cited By */
    case citedby = 'cited-by';

    /** Is Comment On */
    case iscommenton = 'comments-on';

    /** Has Comment In */
    case hascommentin = 'comment-in';

    /** Contains */
    case contains = 'contains';

    /** Contained In */
    case containedin = 'contained-in';

    /** Corrects */
    case corrects = 'corrects';

    /** Correction In */
    case correctionin = 'correction-in';

    /** Replaces */
    case replaces = 'replaces';

    /** Replaced With */
    case replacedwith = 'replaced-with';

    /** Retracts */
    case retracts = 'retracts';

    /** Retracted By */
    case retractedby = 'retracted-by';

    /** Signs */
    case signs = 'signs';

    /** Similar To */
    case similarto = 'similar-to';

    /** Supports */
    case supports = 'supports';

    /** Supported With */
    case supportedwith = 'supported-with';

    /** Transforms */
    case transforms = 'transforms';

    /** Transformed Into */
    case transformedinto = 'transformed-into';

    /** Transformed With */
    case transformedwith = 'transformed-with';

    /** Documents */
    case documents = 'documents';

    /** Specification Of */
    case specificationof = 'specification-of';

    /** Created With */
    case createdwith = 'created-with';

    /** Cite As */
    case citeas = 'cite-as';

    /** Reprint */
    case reprint = 'reprint';

    /** Reprint Of */
    case reprintof = 'reprint-of';
}
