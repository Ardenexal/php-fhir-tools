<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire;

/**
 * @description One of the permitted answers for a "choice" or "open-choice" question.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.answerOption', fhirVersion: 'R4B')]
class QuestionnaireItemAnswerOption extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'valueX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
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
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive',
					'jsonKey' => 'valueDate',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'time',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive',
					'jsonKey' => 'valueTime',
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
					'fhirType' => 'Coding',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
					'jsonKey' => 'valueCoding',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Reference',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
					'jsonKey' => 'valueReference',
					'isBuiltin' => false,
				],
			],
		],
		'initialSelected' => [
			'fhirType' => 'boolean',
			'propertyKind' => 'scalar',
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
		/** @var null|int|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference valueX Answer value */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isRequired: true,
			isChoice: true,
			variants: [
			['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
			[
				'fhirType' => 'date',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive',
				'jsonKey' => 'valueDate',
			],
			[
				'fhirType' => 'time',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive',
				'jsonKey' => 'valueTime',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'valueString',
			],
			[
				'fhirType' => 'Coding',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
				'jsonKey' => 'valueCoding',
			],
			[
				'fhirType' => 'Reference',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
				'jsonKey' => 'valueReference',
			],
		],
		)]
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public int|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference|null $valueX = null,
		/** @var null|bool initialSelected Whether option is selected by default */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $initialSelected = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
