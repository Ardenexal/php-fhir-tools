<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Resource Types
 * URL: http://hl7.org/fhir/ValueSet/resource-types
 * Version: 5.0.0
 * Description: Concrete FHIR Resource Types
 */
enum ResourceType: string
{
	/** Base */
	case base = 'Base';

	/** Account */
	case account = 'Account';

	/** ActivityDefinition */
	case activitydefinition = 'ActivityDefinition';

	/** ActorDefinition */
	case actordefinition = 'ActorDefinition';

	/** AdministrableProductDefinition */
	case administrableproductdefinition = 'AdministrableProductDefinition';

	/** AdverseEvent */
	case adverseevent = 'AdverseEvent';

	/** AllergyIntolerance */
	case allergyintolerance = 'AllergyIntolerance';

	/** Appointment */
	case appointment = 'Appointment';

	/** AppointmentResponse */
	case appointmentresponse = 'AppointmentResponse';

	/** ArtifactAssessment */
	case artifactassessment = 'ArtifactAssessment';

	/** AuditEvent */
	case auditevent = 'AuditEvent';

	/** Basic */
	case basic = 'Basic';

	/** Binary */
	case binary = 'Binary';

	/** BiologicallyDerivedProduct */
	case biologicallyderivedproduct = 'BiologicallyDerivedProduct';

	/** BiologicallyDerivedProductDispense */
	case biologicallyderivedproductdispense = 'BiologicallyDerivedProductDispense';

	/** BodyStructure */
	case bodystructure = 'BodyStructure';

	/** Bundle */
	case bundle = 'Bundle';

	/** CapabilityStatement */
	case capabilitystatement = 'CapabilityStatement';

	/** CarePlan */
	case careplan = 'CarePlan';

	/** CareTeam */
	case careteam = 'CareTeam';

	/** ChargeItem */
	case chargeitem = 'ChargeItem';

	/** ChargeItemDefinition */
	case chargeitemdefinition = 'ChargeItemDefinition';

	/** Citation */
	case citation = 'Citation';

	/** Claim */
	case claim = 'Claim';

	/** ClaimResponse */
	case claimresponse = 'ClaimResponse';

	/** ClinicalImpression */
	case clinicalimpression = 'ClinicalImpression';

	/** ClinicalUseDefinition */
	case clinicalusedefinition = 'ClinicalUseDefinition';

	/** CodeSystem */
	case codesystem = 'CodeSystem';

	/** Communication */
	case communication = 'Communication';

	/** CommunicationRequest */
	case communicationrequest = 'CommunicationRequest';

	/** CompartmentDefinition */
	case compartmentdefinition = 'CompartmentDefinition';

	/** Composition */
	case composition = 'Composition';

	/** ConceptMap */
	case conceptmap = 'ConceptMap';

	/** Condition */
	case condition = 'Condition';

	/** ConditionDefinition */
	case conditiondefinition = 'ConditionDefinition';

	/** Consent */
	case consent = 'Consent';

	/** Contract */
	case contract = 'Contract';

	/** Coverage */
	case coverage = 'Coverage';

	/** CoverageEligibilityRequest */
	case coverageeligibilityrequest = 'CoverageEligibilityRequest';

	/** CoverageEligibilityResponse */
	case coverageeligibilityresponse = 'CoverageEligibilityResponse';

	/** DetectedIssue */
	case detectedissue = 'DetectedIssue';

	/** Device */
	case device = 'Device';

	/** DeviceAssociation */
	case deviceassociation = 'DeviceAssociation';

	/** DeviceDefinition */
	case devicedefinition = 'DeviceDefinition';

	/** DeviceDispense */
	case devicedispense = 'DeviceDispense';

	/** DeviceMetric */
	case devicemetric = 'DeviceMetric';

	/** DeviceRequest */
	case devicerequest = 'DeviceRequest';

	/** DeviceUsage */
	case deviceusage = 'DeviceUsage';

	/** DiagnosticReport */
	case diagnosticreport = 'DiagnosticReport';

	/** DocumentReference */
	case documentreference = 'DocumentReference';

	/** Encounter */
	case encounter = 'Encounter';

	/** EncounterHistory */
	case encounterhistory = 'EncounterHistory';

	/** Endpoint */
	case endpoint = 'Endpoint';

	/** EnrollmentRequest */
	case enrollmentrequest = 'EnrollmentRequest';

	/** EnrollmentResponse */
	case enrollmentresponse = 'EnrollmentResponse';

	/** EpisodeOfCare */
	case episodeofcare = 'EpisodeOfCare';

	/** EventDefinition */
	case eventdefinition = 'EventDefinition';

	/** Evidence */
	case evidence = 'Evidence';

	/** EvidenceReport */
	case evidencereport = 'EvidenceReport';

	/** EvidenceVariable */
	case evidencevariable = 'EvidenceVariable';

	/** ExampleScenario */
	case examplescenario = 'ExampleScenario';

	/** ExplanationOfBenefit */
	case explanationofbenefit = 'ExplanationOfBenefit';

	/** FamilyMemberHistory */
	case familymemberhistory = 'FamilyMemberHistory';

	/** Flag */
	case flag = 'Flag';

	/** FormularyItem */
	case formularyitem = 'FormularyItem';

	/** GenomicStudy */
	case genomicstudy = 'GenomicStudy';

	/** Goal */
	case goal = 'Goal';

	/** GraphDefinition */
	case graphdefinition = 'GraphDefinition';

	/** Group */
	case group = 'Group';

	/** GuidanceResponse */
	case guidanceresponse = 'GuidanceResponse';

	/** HealthcareService */
	case healthcareservice = 'HealthcareService';

	/** ImagingSelection */
	case imagingselection = 'ImagingSelection';

	/** ImagingStudy */
	case imagingstudy = 'ImagingStudy';

	/** Immunization */
	case immunization = 'Immunization';

	/** ImmunizationEvaluation */
	case immunizationevaluation = 'ImmunizationEvaluation';

	/** ImmunizationRecommendation */
	case immunizationrecommendation = 'ImmunizationRecommendation';

	/** ImplementationGuide */
	case implementationguide = 'ImplementationGuide';

	/** Ingredient */
	case ingredient = 'Ingredient';

	/** InsurancePlan */
	case insuranceplan = 'InsurancePlan';

	/** InventoryItem */
	case inventoryitem = 'InventoryItem';

	/** InventoryReport */
	case inventoryreport = 'InventoryReport';

	/** Invoice */
	case invoice = 'Invoice';

	/** Library */
	case library = 'Library';

	/** Linkage */
	case linkage = 'Linkage';

	/** List */
	case list = 'List';

	/** Location */
	case location = 'Location';

	/** ManufacturedItemDefinition */
	case manufactureditemdefinition = 'ManufacturedItemDefinition';

	/** Measure */
	case measure = 'Measure';

	/** MeasureReport */
	case measurereport = 'MeasureReport';

	/** Medication */
	case medication = 'Medication';

	/** MedicationAdministration */
	case medicationadministration = 'MedicationAdministration';

	/** MedicationDispense */
	case medicationdispense = 'MedicationDispense';

	/** MedicationKnowledge */
	case medicationknowledge = 'MedicationKnowledge';

	/** MedicationRequest */
	case medicationrequest = 'MedicationRequest';

	/** MedicationStatement */
	case medicationstatement = 'MedicationStatement';

	/** MedicinalProductDefinition */
	case medicinalproductdefinition = 'MedicinalProductDefinition';

	/** MessageDefinition */
	case messagedefinition = 'MessageDefinition';

	/** MessageHeader */
	case messageheader = 'MessageHeader';

	/** MolecularSequence */
	case molecularsequence = 'MolecularSequence';

	/** NamingSystem */
	case namingsystem = 'NamingSystem';

	/** NutritionIntake */
	case nutritionintake = 'NutritionIntake';

	/** NutritionOrder */
	case nutritionorder = 'NutritionOrder';

	/** NutritionProduct */
	case nutritionproduct = 'NutritionProduct';

	/** Observation */
	case observation = 'Observation';

	/** ObservationDefinition */
	case observationdefinition = 'ObservationDefinition';

	/** OperationDefinition */
	case operationdefinition = 'OperationDefinition';

	/** OperationOutcome */
	case operationoutcome = 'OperationOutcome';

	/** Organization */
	case organization = 'Organization';

	/** OrganizationAffiliation */
	case organizationaffiliation = 'OrganizationAffiliation';

	/** PackagedProductDefinition */
	case packagedproductdefinition = 'PackagedProductDefinition';

	/** Parameters */
	case parameters = 'Parameters';

	/** Patient */
	case patient = 'Patient';

	/** PaymentNotice */
	case paymentnotice = 'PaymentNotice';

	/** PaymentReconciliation */
	case paymentreconciliation = 'PaymentReconciliation';

	/** Permission */
	case permission = 'Permission';

	/** Person */
	case person = 'Person';

	/** PlanDefinition */
	case plandefinition = 'PlanDefinition';

	/** Practitioner */
	case practitioner = 'Practitioner';

	/** PractitionerRole */
	case practitionerrole = 'PractitionerRole';

	/** Procedure */
	case procedure = 'Procedure';

	/** Provenance */
	case provenance = 'Provenance';

	/** Questionnaire */
	case questionnaire = 'Questionnaire';

	/** QuestionnaireResponse */
	case questionnaireresponse = 'QuestionnaireResponse';

	/** RegulatedAuthorization */
	case regulatedauthorization = 'RegulatedAuthorization';

	/** RelatedPerson */
	case relatedperson = 'RelatedPerson';

	/** RequestOrchestration */
	case requestorchestration = 'RequestOrchestration';

	/** Requirements */
	case requirements = 'Requirements';

	/** ResearchStudy */
	case researchstudy = 'ResearchStudy';

	/** ResearchSubject */
	case researchsubject = 'ResearchSubject';

	/** RiskAssessment */
	case riskassessment = 'RiskAssessment';

	/** Schedule */
	case schedule = 'Schedule';

	/** SearchParameter */
	case searchparameter = 'SearchParameter';

	/** ServiceRequest */
	case servicerequest = 'ServiceRequest';

	/** Slot */
	case slot = 'Slot';

	/** Specimen */
	case specimen = 'Specimen';

	/** SpecimenDefinition */
	case specimendefinition = 'SpecimenDefinition';

	/** StructureDefinition */
	case structuredefinition = 'StructureDefinition';

	/** StructureMap */
	case structuremap = 'StructureMap';

	/** Subscription */
	case subscription = 'Subscription';

	/** SubscriptionStatus */
	case subscriptionstatus = 'SubscriptionStatus';

	/** SubscriptionTopic */
	case subscriptiontopic = 'SubscriptionTopic';

	/** Substance */
	case substance = 'Substance';

	/** SubstanceDefinition */
	case substancedefinition = 'SubstanceDefinition';

	/** SubstanceNucleicAcid */
	case substancenucleicacid = 'SubstanceNucleicAcid';

	/** SubstancePolymer */
	case substancepolymer = 'SubstancePolymer';

	/** SubstanceProtein */
	case substanceprotein = 'SubstanceProtein';

	/** SubstanceReferenceInformation */
	case substancereferenceinformation = 'SubstanceReferenceInformation';

	/** SubstanceSourceMaterial */
	case substancesourcematerial = 'SubstanceSourceMaterial';

	/** SupplyDelivery */
	case supplydelivery = 'SupplyDelivery';

	/** SupplyRequest */
	case supplyrequest = 'SupplyRequest';

	/** Task */
	case task = 'Task';

	/** TerminologyCapabilities */
	case terminologycapabilities = 'TerminologyCapabilities';

	/** TestPlan */
	case testplan = 'TestPlan';

	/** TestReport */
	case testreport = 'TestReport';

	/** TestScript */
	case testscript = 'TestScript';

	/** Transport */
	case transport = 'Transport';

	/** ValueSet */
	case valueset = 'ValueSet';

	/** VerificationResult */
	case verificationresult = 'VerificationResult';

	/** VisionPrescription */
	case visionprescription = 'VisionPrescription';
}
