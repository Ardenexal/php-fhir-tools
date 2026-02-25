<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Guide Page Generation
 * URL: http://hl7.org/fhir/ValueSet/guide-page-generation
 * Version: 5.0.0
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
