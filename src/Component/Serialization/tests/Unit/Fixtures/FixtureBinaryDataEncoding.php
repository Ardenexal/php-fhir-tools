<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures;

/**
 * Stand-in for a generated CDA ValueSet enum (e.g. CdaModels\Enum\BinaryDataEncoding).
 *
 * The CDA models ship as their own Composer package (ADR-009), so the Serialization component
 * cannot reference them in its own tests. This reproduces the only property that matters to the
 * XML normalizer: a string-backed enum whose backing value differs from its case name.
 */
enum FixtureBinaryDataEncoding: string
{
    case base64_encodedtext = 'B64';
    case plaintext          = 'TXT';
}
