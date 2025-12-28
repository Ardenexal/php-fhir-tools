<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Version Independent Resource Types (All)
 * URL: http://hl7.org/fhir/ValueSet/version-independent-all-resource-types
 * Version: 5.0.0
 * Description: Current and past FHIR resource types (deleted or renamed), including abstract types
 */
enum FHIRVersionIndependentResourceTypesAll: string
{
	/** BodySite */
	case bodysite = 'BodySite';

	/** CatalogEntry */
	case catalogentry = 'CatalogEntry';

	/** Conformance */
	case conformance = 'Conformance';

	/** DataElement */
	case dataelement = 'DataElement';

	/** DeviceComponent */
	case devicecomponent = 'DeviceComponent';

	/** DeviceUseRequest */
	case deviceuserequest = 'DeviceUseRequest';

	/** DeviceUseStatement */
	case deviceusestatement = 'DeviceUseStatement';

	/** DiagnosticOrder */
	case diagnosticorder = 'DiagnosticOrder';

	/** DocumentManifest */
	case documentmanifest = 'DocumentManifest';

	/** EffectEvidenceSynthesis */
	case effectevidencesynthesis = 'EffectEvidenceSynthesis';

	/** EligibilityRequest */
	case eligibilityrequest = 'EligibilityRequest';

	/** EligibilityResponse */
	case eligibilityresponse = 'EligibilityResponse';

	/** ExpansionProfile */
	case expansionprofile = 'ExpansionProfile';

	/** ImagingManifest */
	case imagingmanifest = 'ImagingManifest';

	/** ImagingObjectSelection */
	case imagingobjectselection = 'ImagingObjectSelection';

	/** Media */
	case media = 'Media';

	/** MedicationOrder */
	case medicationorder = 'MedicationOrder';

	/** MedicationUsage */
	case medicationusage = 'MedicationUsage';

	/** MedicinalProduct */
	case medicinalproduct = 'MedicinalProduct';

	/** MedicinalProductAuthorization */
	case medicinalproductauthorization = 'MedicinalProductAuthorization';

	/** MedicinalProductContraindication */
	case medicinalproductcontraindication = 'MedicinalProductContraindication';

	/** MedicinalProductIndication */
	case medicinalproductindication = 'MedicinalProductIndication';

	/** MedicinalProductIngredient */
	case medicinalproductingredient = 'MedicinalProductIngredient';

	/** MedicinalProductInteraction */
	case medicinalproductinteraction = 'MedicinalProductInteraction';

	/** MedicinalProductManufactured */
	case medicinalproductmanufactured = 'MedicinalProductManufactured';

	/** MedicinalProductPackaged */
	case medicinalproductpackaged = 'MedicinalProductPackaged';

	/** MedicinalProductPharmaceutical */
	case medicinalproductpharmaceutical = 'MedicinalProductPharmaceutical';

	/** MedicinalProductUndesirableEffect */
	case medicinalproductundesirableeffect = 'MedicinalProductUndesirableEffect';

	/** Order */
	case order = 'Order';

	/** OrderResponse */
	case orderresponse = 'OrderResponse';

	/** ProcedureRequest */
	case procedurerequest = 'ProcedureRequest';

	/** ProcessRequest */
	case processrequest = 'ProcessRequest';

	/** ProcessResponse */
	case processresponse = 'ProcessResponse';

	/** ReferralRequest */
	case referralrequest = 'ReferralRequest';

	/** RequestGroup */
	case requestgroup = 'RequestGroup';

	/** ResearchDefinition */
	case researchdefinition = 'ResearchDefinition';

	/** ResearchElementDefinition */
	case researchelementdefinition = 'ResearchElementDefinition';

	/** RiskEvidenceSynthesis */
	case riskevidencesynthesis = 'RiskEvidenceSynthesis';

	/** Sequence */
	case sequence = 'Sequence';

	/** ServiceDefinition */
	case servicedefinition = 'ServiceDefinition';

	/** SubstanceSpecification */
	case substancespecification = 'SubstanceSpecification';
}
