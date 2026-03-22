<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Care Plan Activity Kind
 * URL: http://hl7.org/fhir/ValueSet/care-plan-activity-kind
 * Version: 4.0.1
 * Description: Resource types defined as part of FHIR that can be represented as in-line definitions of a care plan activity.
 */
enum CarePlanActivityKind: string
{
    /** Account */
    case account = 'Account';

    /** ActivityDefinition */
    case activitydefinition = 'ActivityDefinition';

    /** AdverseEvent */
    case adverseevent = 'AdverseEvent';

    /** AllergyIntolerance */
    case allergyintolerance = 'AllergyIntolerance';

    /** Appointment */
    case appointment = 'Appointment';

    /** AppointmentResponse */
    case appointmentresponse = 'AppointmentResponse';

    /** AuditEvent */
    case auditevent = 'AuditEvent';

    /** Basic */
    case basic = 'Basic';

    /** Binary */
    case binary = 'Binary';

    /** BiologicallyDerivedProduct */
    case biologicallyderivedproduct = 'BiologicallyDerivedProduct';

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

    /** CatalogEntry */
    case catalogentry = 'CatalogEntry';

    /** ChargeItem */
    case chargeitem = 'ChargeItem';

    /** ChargeItemDefinition */
    case chargeitemdefinition = 'ChargeItemDefinition';

    /** Claim */
    case claim = 'Claim';

    /** ClaimResponse */
    case claimresponse = 'ClaimResponse';

    /** ClinicalImpression */
    case clinicalimpression = 'ClinicalImpression';

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

    /** DeviceDefinition */
    case devicedefinition = 'DeviceDefinition';

    /** DeviceMetric */
    case devicemetric = 'DeviceMetric';

    /** DeviceRequest */
    case devicerequest = 'DeviceRequest';

    /** DeviceUseStatement */
    case deviceusestatement = 'DeviceUseStatement';

    /** DiagnosticReport */
    case diagnosticreport = 'DiagnosticReport';

    /** DocumentManifest */
    case documentmanifest = 'DocumentManifest';

    /** DocumentReference */
    case documentreference = 'DocumentReference';

    /** DomainResource */
    case domainresource = 'DomainResource';

    /** EffectEvidenceSynthesis */
    case effectevidencesynthesis = 'EffectEvidenceSynthesis';

    /** Encounter */
    case encounter = 'Encounter';

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

    /** InsurancePlan */
    case insuranceplan = 'InsurancePlan';

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

    /** Measure */
    case measure = 'Measure';

    /** MeasureReport */
    case measurereport = 'MeasureReport';

    /** Media */
    case media = 'Media';

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

    /** MessageDefinition */
    case messagedefinition = 'MessageDefinition';

    /** MessageHeader */
    case messageheader = 'MessageHeader';

    /** MolecularSequence */
    case molecularsequence = 'MolecularSequence';

    /** NamingSystem */
    case namingsystem = 'NamingSystem';

    /** NutritionOrder */
    case nutritionorder = 'NutritionOrder';

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

    /** Parameters */
    case parameters = 'Parameters';

    /** Patient */
    case patient = 'Patient';

    /** PaymentNotice */
    case paymentnotice = 'PaymentNotice';

    /** PaymentReconciliation */
    case paymentreconciliation = 'PaymentReconciliation';

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

    /** RelatedPerson */
    case relatedperson = 'RelatedPerson';

    /** RequestGroup */
    case requestgroup = 'RequestGroup';

    /** ResearchDefinition */
    case researchdefinition = 'ResearchDefinition';

    /** ResearchElementDefinition */
    case researchelementdefinition = 'ResearchElementDefinition';

    /** ResearchStudy */
    case researchstudy = 'ResearchStudy';

    /** ResearchSubject */
    case researchsubject = 'ResearchSubject';

    /** Resource */
    case resource = 'Resource';

    /** RiskAssessment */
    case riskassessment = 'RiskAssessment';

    /** RiskEvidenceSynthesis */
    case riskevidencesynthesis = 'RiskEvidenceSynthesis';

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

    /** Substance */
    case substance = 'Substance';

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

    /** SubstanceSpecification */
    case substancespecification = 'SubstanceSpecification';

    /** SupplyDelivery */
    case supplydelivery = 'SupplyDelivery';

    /** SupplyRequest */
    case supplyrequest = 'SupplyRequest';

    /** Task */
    case task = 'Task';

    /** TerminologyCapabilities */
    case terminologycapabilities = 'TerminologyCapabilities';

    /** TestReport */
    case testreport = 'TestReport';

    /** TestScript */
    case testscript = 'TestScript';

    /** ValueSet */
    case valueset = 'ValueSet';

    /** VerificationResult */
    case verificationresult = 'VerificationResult';

    /** VisionPrescription */
    case visionprescription = 'VisionPrescription';
}
