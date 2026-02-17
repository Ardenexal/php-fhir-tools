<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: GuideParameterCode
 * URL: http://hl7.org/fhir/ValueSet/guide-parameter-code
 * Version: 4.0.1
 * Description: Code of parameter that is input to the guide.
 */
enum GuideParameterCode: string
{
    /** Apply Metadata Value */
    case applymetadatavalue = 'apply';

    /** Resource Path */
    case resourcepath = 'path-resource';

    /** Pages Path */
    case pagespath = 'path-pages';

    /** Terminology Cache Path */
    case terminologycachepath = 'path-tx-cache';

    /** Expansion Profile */
    case expansionprofile = 'expansion-parameter';

    /** Broken Links Rule */
    case brokenlinksrule = 'rule-broken-links';

    /** Generate XML */
    case generatexml = 'generate-xml';

    /** Generate JSON */
    case generatejson = 'generate-json';

    /** Generate Turtle */
    case generateturtle = 'generate-turtle';

    /** HTML Template */
    case htmltemplate = 'html-template';
}
