<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\SubstanceDefinition;

/**
 * @description Moiety, for structural modifications.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.moiety', fhirVersion: 'R4B')]
class SubstanceDefinitionMoiety extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'modifierExtension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'modifierExtension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'role' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'identifier' => [
			'fhirType' => 'Identifier',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'name' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'stereochemistry' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'opticalActivity' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'molecularFormula' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'amountX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'Quantity',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
					'jsonKey' => 'amountQuantity',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
					'jsonKey' => 'amountString',
					'isBuiltin' => false,
				],
			],
		],
		'measurementType' => [
			'fhirType' => 'CodeableConcept',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept role Role that the moiety is playing */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier identifier Identifier by which this moiety substance is known */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string name Textual name for this moiety substance */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept stereochemistry Stereochemistry type */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $stereochemistry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept opticalActivity Optical activity type */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $opticalActivity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string molecularFormula Molecular formula for this moiety (e.g. with the Hill system) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $molecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string amountX Quantitative value for this moiety */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'Quantity',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
				'jsonKey' => 'amountQuantity',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'amountString',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept measurementType The measurement type of the quantitative value */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $measurementType = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
