<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: GuidePageGeneration
 * URL: http://hl7.org/fhir/ValueSet/guide-page-generation
 * Version: 4.0.1
 * Description: A code that indicates how the page is generated.
 */
enum GuidePageGeneration: string
{
    /** HTML */
    case html = 'html';

    /** Markdown */
    case markdown = 'markdown';

    /** XML */
    case xml = 'xml';

    /** Generated */
    case generated = 'generated';
}
