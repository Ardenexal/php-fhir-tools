<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\CodeSystem;

/**
 * @description A property value for this concept.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.concept.property', fhirVersion: 'R4B')]
class CodeSystemConceptProperty extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'code' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'valueX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'code',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive',
					'jsonKey' => 'valueCode',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Coding',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
					'jsonKey' => 'valueCoding',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
					'jsonKey' => 'valueString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'integer',
					'propertyKind' => 'scalar',
					'phpType' => 'int',
					'jsonKey' => 'valueInteger',
					'isBuiltin' => true,
				],
				[
					'fhirType' => 'boolean',
					'propertyKind' => 'scalar',
					'phpType' => 'bool',
					'jsonKey' => 'valueBoolean',
					'isBuiltin' => true,
				],
				[
					'fhirType' => 'dateTime',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
					'jsonKey' => 'valueDateTime',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'decimal',
					'propertyKind' => 'scalar',
					'phpType' => 'float',
					'jsonKey' => 'valueDecimal',
					'isBuiltin' => true,
				],
			],
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive code Reference to CodeSystem.property.code */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|int|bool|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive|float valueX Value of the property for this concept */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isRequired: true,
			isChoice: true,
			variants: [
			[
				'fhirType' => 'code',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive',
				'jsonKey' => 'valueCode',
			],
			[
				'fhirType' => 'Coding',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
				'jsonKey' => 'valueCoding',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'valueString',
			],
			['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
			['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
			[
				'fhirType' => 'dateTime',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
				'jsonKey' => 'valueDateTime',
			],
			['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'float', 'jsonKey' => 'valueDecimal'],
		],
		)]
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|int|bool|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive|float|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
