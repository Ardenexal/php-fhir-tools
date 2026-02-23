<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: FHIRAllTypes
 * URL: http://hl7.org/fhir/ValueSet/all-types
 * Version: 4.3.0
 * Description: A list of all the concrete types defined in this version of the FHIR specification - Abstract Types, Data Types and Resource Types.
 */
enum FHIRAllTypes: string
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

    /** Type */
    case type = 'Type';

    /** Any */
    case any = 'Any';
}
