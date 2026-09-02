<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * One element or property the deserializer read but could not place on the model.
 *
 * FHIR treats unknown input as a validation rule, not a parsing precondition: the reference validator
 * reads the whole document and reports one located error per unplaceable element. So a typo'd name is
 * a finding rather than grounds for refusing input the reader can otherwise read.
 *
 * JSON and XML carry different wording because the reference validator does, and matching it exactly
 * is the contract. {@see FORMAT_JSON} and {@see FORMAT_XML}.
 */
final readonly class UnknownInput
{
    /**
     * A JSON property no model property claimed. Reported without a path, because the reference
     * validator names only the property: `Unrecognized property 'other'`.
     */
    public const FORMAT_JSON = 'json';

    /**
     * An XML element no model property claimed. Reported with the element's owning path, because
     * the reference validator names both: `Undefined element 'mode1' at /f:List`.
     */
    public const FORMAT_XML = 'xml';

    /**
     * @param string $propertyName the element or property name as it appeared in the document,
     *                             never a name normalised towards a model property
     * @param string $format       self::FORMAT_JSON or self::FORMAT_XML
     */
    public function __construct(
        public string $propertyName,
        public string $format,
    ) {
    }
}
