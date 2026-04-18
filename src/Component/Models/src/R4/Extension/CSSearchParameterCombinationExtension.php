<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-combination
 *
 * @description This extension defines a possible search parameter combination by listing a set of search parameters and indicating whether they are required or optional.
 * - If a search combination is specified, clients should expect that they must submit a search that meets one of the required combinations or the search will be unsuccessful.
 * - If multiple search parameter combinations are specified, a client may pick between them, and supply the minimal required parameters for any of the combinations.
 * - If no parameters in the set of search parameters are listed as required, then *at least one* of the listed optional parameters must be present.  This shorthand method is the same as repeating the entire extension for each combination of optional and required search parameters.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-combination', fhirVersion: 'R4')]
class CSSearchParameterCombinationExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var array<StringPrimitive> required A required search parameter name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $required = [],
        /** @var array<StringPrimitive> optional An optional search parameter name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $optional = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        foreach ($this->required as $v) {
            $subExtensions[] = new Extension(url: 'required', value: $v);
        }
        foreach ($this->optional as $v) {
            $subExtensions[] = new Extension(url: 'optional', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-combination',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $required = [];
        $optional = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'required' && $ext->value instanceof StringPrimitive) {
                $required[] = $ext->value;
            }
            if ($extUrl === 'optional' && $ext->value instanceof StringPrimitive) {
                $optional[] = $ext->value;
            }
        }

        return new static($required, $optional, $id);
    }
}
