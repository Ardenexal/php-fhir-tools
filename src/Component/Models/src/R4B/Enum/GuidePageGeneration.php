<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: GuidePageGeneration
 * URL: http://hl7.org/fhir/ValueSet/guide-page-generation
 * Version: 4.3.0
 * Description: A code that indicates how the page is generated.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/guide-page-generation', version: '4.3.0')]
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
