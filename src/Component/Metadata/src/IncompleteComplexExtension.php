<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * A complex extension the deserializer could not build as its typed class.
 *
 * A typed complex extension's constructor needs every required sub-extension, so a document that
 * omits one cannot become that class. FHIR treats the omission as a validation rule, not a parsing
 * precondition: the reference validator reads `patient-extension-complex-bad1.xml` and reports
 * `Slice 'Extension.extension:species': a matching slice is required, but not found`. The
 * deserializer therefore reads the element as the plain `Extension` it still is, and records this
 * against that object for validation to report.
 */
final readonly class IncompleteComplexExtension
{
    /**
     * @param class-string $extensionClass the typed complex extension class the element resolved to
     * @param string|null  $sliceName      the required sub-extension that was absent or held the wrong
     *                                     value type, when the failure named one; null when the class
     *                                     refused the sub-extensions for a reason it did not attribute
     *                                     to a slice
     * @param string       $reason         the class's own explanation, kept for the finding when no
     *                                     slice is named
     */
    public function __construct(
        public string $extensionClass,
        public ?string $sliceName,
        public string $reason = '',
    ) {
    }
}
