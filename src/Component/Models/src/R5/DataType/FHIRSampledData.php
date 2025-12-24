<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUCUMCodesType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 *
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R5')]
class FHIRSampledData extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRQuantity|null origin Zero value and units */
        #[NotBlank]
        public ?FHIRQuantity $origin = null,
        /** @var FHIRDecimal|null interval Number of intervalUnits between samples */
        public ?FHIRDecimal $interval = null,
        /** @var FHIRUCUMCodesType|null intervalUnit The measurement unit of the interval between samples */
        #[NotBlank]
        public ?FHIRUCUMCodesType $intervalUnit = null,
        /** @var FHIRDecimal|null factor Multiply data by this before adding to origin */
        public ?FHIRDecimal $factor = null,
        /** @var FHIRDecimal|null lowerLimit Lower limit of detection */
        public ?FHIRDecimal $lowerLimit = null,
        /** @var FHIRDecimal|null upperLimit Upper limit of detection */
        public ?FHIRDecimal $upperLimit = null,
        /** @var FHIRPositiveInt|null dimensions Number of sample points at each time point */
        #[NotBlank]
        public ?FHIRPositiveInt $dimensions = null,
        /** @var FHIRCanonical|null codeMap Defines the codes used in the data */
        public ?FHIRCanonical $codeMap = null,
        /** @var FHIRString|string|null offsets Offsets, typically in time, at which data values were taken */
        public FHIRString|string|null $offsets = null,
        /** @var FHIRString|string|null data Decimal values with spaces, or "E" | "U" | "L", or another code */
        public FHIRString|string|null $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
