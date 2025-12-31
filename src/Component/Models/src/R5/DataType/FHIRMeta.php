<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Meta
 * @description The metadata about a resource. This is content in the resource that is maintained by the infrastructure. Changes to the content might not always be associated with version changes to the resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Meta', fhirVersion: 'R5')]
class FHIRMeta extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId versionId Version specific identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $versionId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant lastUpdated When the resource version last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant $lastUpdated = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri source Identifies where the resource comes from */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $source = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> profile Profiles this resource claims to conform to */
		public array $profile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding> security Security Labels applied to this resource */
		public array $security = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding> tag Tags applied to this resource */
		public array $tag = [],
	) {
		parent::__construct($id, $extension);
	}
}
