<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: FHIRDefinedType
 * URL: http://hl7.org/fhir/ValueSet/defined-types
 * Version: 4.3.0
 * Description: A list of all the concrete types defined in this version of the FHIR specification - Data Types and Resource Types.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/defined-types', version: '4.3.0')]
enum FHIRDefinedType: string
{
    /** Address */
    case address = 'Address';

    /** Age */
    case age = 'Age';

    /** Annotation */
    case annotation = 'Annotation';

    /** Attachment */
    case attachment = 'Attachment';

    /** BackboneElement */
    case backboneelement = 'BackboneElement';

    /** CodeableConcept */
    case codeableconcept = 'CodeableConcept';

    /** CodeableReference */
    case codeablereference = 'CodeableReference';

    /** Coding */
    case coding = 'Coding';

    /** ContactDetail */
    case contactdetail = 'ContactDetail';

    /** ContactPoint */
    case contactpoint = 'ContactPoint';

    /** Contributor */
    case contributor = 'Contributor';

    /** Count */
    case count = 'Count';

    /** DataRequirement */
    case datarequirement = 'DataRequirement';

    /** Distance */
    case distance = 'Distance';

    /** Dosage */
    case dosage = 'Dosage';

    /** Duration */
    case duration = 'Duration';

    /** Element */
    case element = 'Element';

    /** ElementDefinition */
    case elementdefinition = 'ElementDefinition';

    /** Expression */
    case expression = 'Expression';

    /** Extension */
    case extension = 'Extension';

    /** HumanName */
    case humanname = 'HumanName';

    /** Identifier */
    case identifier = 'Identifier';

    /** MarketingStatus */
    case marketingstatus = 'MarketingStatus';

    /** Meta */
    case meta = 'Meta';

    /** Money */
    case money = 'Money';

    /** MoneyQuantity */
    case moneyquantity = 'MoneyQuantity';

    /** Narrative */
    case narrative = 'Narrative';

    /** ParameterDefinition */
    case parameterdefinition = 'ParameterDefinition';

    /** Period */
    case period = 'Period';

    /** Population */
    case population = 'Population';

    /** ProdCharacteristic */
    case prodcharacteristic = 'ProdCharacteristic';

    /** ProductShelfLife */
    case productshelflife = 'ProductShelfLife';

    /** Quantity */
    case quantity = 'Quantity';

    /** Range */
    case range = 'Range';

    /** Ratio */
    case ratio = 'Ratio';

    /** RatioRange */
    case ratiorange = 'RatioRange';

    /** Reference */
    case reference = 'Reference';

    /** RelatedArtifact */
    case relatedartifact = 'RelatedArtifact';

    /** SampledData */
    case sampleddata = 'SampledData';

    /** Signature */
    case signature = 'Signature';

    /** SimpleQuantity */
    case simplequantity = 'SimpleQuantity';

    /** Timing */
    case timing = 'Timing';

    /** TriggerDefinition */
    case triggerdefinition = 'TriggerDefinition';

    /** UsageContext */
    case usagecontext = 'UsageContext';

    /** base64Binary */
    case base64_binary = 'base64Binary';

    /** boolean */
    case boolean = 'boolean';

    /** canonical */
    case canonical = 'canonical';

    /** code */
    case code = 'code';

    /** date */
    case date = 'date';

    /** dateTime */
    case datetime = 'dateTime';

    /** decimal */
    case decimal = 'decimal';

    /** id */
    case id = 'id';

    /** instant */
    case instant = 'instant';

    /** integer */
    case integer = 'integer';

    /** markdown */
    case markdown = 'markdown';

    /** oid */
    case oid = 'oid';

    /** positiveInt */
    case positiveint = 'positiveInt';

    /** string */
    case string = 'string';

    /** time */
    case time = 'time';

    /** unsignedInt */
    case unsignedint = 'unsignedInt';

    /** uri */
    case uri = 'uri';

    /** url */
    case url = 'url';

    /** uuid */
    case uuid = 'uuid';

    /** XHTML */
    case xhtml = 'xhtml';

    /** Resource */
    case resource = 'Resource';

    /** Binary */
    case binary = 'Binary';

    /** Bundle */
    case bundle = 'Bundle';

    /** DomainResource */
    case domainresource = 'DomainResource';

    /** Account */
    case account = 'Account';

    /** ActivityDefinition */
    case activitydefinition = 'ActivityDefinition';

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

    /** AuditEvent */
    case auditevent = 'AuditEvent';

    /** Basic */
    case basic = 'Basic';

    /** BiologicallyDerivedProduct */
    case biologicallyderivedproduct = 'BiologicallyDerivedProduct';

    /** BodyStructure */
    case bodystructure = 'BodyStructure';

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

    /** Ingredient */
    case ingredient = 'Ingredient';

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

    /** ManufacturedItemDefinition */
    case manufactureditemdefinition = 'ManufacturedItemDefinition';

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

    /** RegulatedAuthorization */
    case regulatedauthorization = 'RegulatedAuthorization';

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

    /** Parameters */
    case parameters = 'Parameters';
}
