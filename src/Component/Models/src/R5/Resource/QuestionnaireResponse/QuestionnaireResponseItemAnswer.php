<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse;

/**
 * @description The respondent's answer(s) to the question.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'QuestionnaireResponse', elementPath: 'QuestionnaireResponse.item.answer', fhirVersion: 'R5')]
class QuestionnaireResponseItemAnswer extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement
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
		'value' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'boolean',
					'propertyKind' => 'scalar',
					'phpType' => 'bool',
					'jsonKey' => 'valueBoolean',
					'isBuiltin' => true,
				],
				[
					'fhirType' => 'decimal',
					'propertyKind' => 'scalar',
					'phpType' => 'float',
					'jsonKey' => 'valueDecimal',
					'isBuiltin' => true,
				],
				[
					'fhirType' => 'integer',
					'propertyKind' => 'scalar',
					'phpType' => 'int',
					'jsonKey' => 'valueInteger',
					'isBuiltin' => true,
				],
				[
					'fhirType' => 'date',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
					'jsonKey' => 'valueDate',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'dateTime',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
					'jsonKey' => 'valueDateTime',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'time',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
					'jsonKey' => 'valueTime',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
					'jsonKey' => 'valueString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'uri',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
					'jsonKey' => 'valueUri',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Attachment',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
					'jsonKey' => 'valueAttachment',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Coding',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
					'jsonKey' => 'valueCoding',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Quantity',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
					'jsonKey' => 'valueQuantity',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Reference',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
					'jsonKey' => 'valueReference',
					'isBuiltin' => false,
				],
			],
		],
		'item' => [
			'fhirType' => 'unknown',
			'propertyKind' => 'complex',
			'isArray' => true,
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|bool|float|int|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference value Single-valued answer to the question */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isRequired: true,
			isChoice: true,
			variants: [
			['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
			['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'float', 'jsonKey' => 'valueDecimal'],
			['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
			[
				'fhirType' => 'date',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
				'jsonKey' => 'valueDate',
			],
			[
				'fhirType' => 'dateTime',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
				'jsonKey' => 'valueDateTime',
			],
			[
				'fhirType' => 'time',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive',
				'jsonKey' => 'valueTime',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
				'jsonKey' => 'valueString',
			],
			[
				'fhirType' => 'uri',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
				'jsonKey' => 'valueUri',
			],
			[
				'fhirType' => 'Attachment',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
				'jsonKey' => 'valueAttachment',
			],
			[
				'fhirType' => 'Coding',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
				'jsonKey' => 'valueCoding',
			],
			[
				'fhirType' => 'Quantity',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
				'jsonKey' => 'valueQuantity',
			],
			[
				'fhirType' => 'Reference',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
				'jsonKey' => 'valueReference',
			],
		],
		)]
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public bool|float|int|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference|null $value = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem> item Child items of question */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'unknown', propertyKind: 'complex', isArray: true)]
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
