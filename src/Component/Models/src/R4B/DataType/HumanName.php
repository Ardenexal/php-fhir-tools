<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/HumanName
 * @description A human's name with the ability to identify parts and usage.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'HumanName', fhirVersion: 'R4B')]
class HumanName extends Element
{
	public const FHIR_PROPERTY_MAP = [
		'id' => [
			'fhirType' => 'http://hl7.org/fhirpath/System.String',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'extension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'extension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'use' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'text' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'family' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'given' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'prefix' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'suffix' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'period' => [
			'fhirType' => 'Period',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
	];

	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\NameUseType use usual | official | temp | nickname | anonymous | old | maiden */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?NameUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string text Text representation of the full name */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string family Family name (often called 'Surname') */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $family = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string> given Given names (not always 'first'). Includes middle names */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
		public array $given = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string> prefix Parts that come before the name */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
		public array $prefix = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string> suffix Parts that come after the name */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
		public array $suffix = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period period Time period when name was/is in use */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?Period $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
