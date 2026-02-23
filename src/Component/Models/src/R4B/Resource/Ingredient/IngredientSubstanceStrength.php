<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Ingredient;

/**
 * @description The quantity of substance in the unit of presentation, or in the volume (or mass) of the single pharmaceutical product or manufactured item. The allowed repetitions do not represent different strengths, but are different representations - mathematically equivalent - of a single strength.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Ingredient', elementPath: 'Ingredient.substance.strength', fhirVersion: 'R4B')]
class IngredientSubstanceStrength extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'presentationX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'Ratio',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
					'jsonKey' => 'presentationRatio',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'RatioRange',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange',
					'jsonKey' => 'presentationRatioRange',
					'isBuiltin' => false,
				],
			],
		],
		'textPresentation' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'concentrationX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'Ratio',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
					'jsonKey' => 'concentrationRatio',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'RatioRange',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange',
					'jsonKey' => 'concentrationRatioRange',
					'isBuiltin' => false,
				],
			],
		],
		'textConcentration' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'measurementPoint' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'country' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'referenceStrength' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange presentationX The quantity of substance in the unit of presentation */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'Ratio',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
				'jsonKey' => 'presentationRatio',
			],
			[
				'fhirType' => 'RatioRange',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange',
				'jsonKey' => 'presentationRatioRange',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange|null $presentationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string textPresentation Text of either the whole presentation strength or a part of it (rest being in Strength.presentation as a ratio) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $textPresentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange concentrationX The strength per unitary volume (or mass) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'Ratio',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
				'jsonKey' => 'concentrationRatio',
			],
			[
				'fhirType' => 'RatioRange',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange',
				'jsonKey' => 'concentrationRatioRange',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\RatioRange|null $concentrationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string textConcentration Text of either the whole concentration strength or a part of it (rest being in Strength.concentration as a ratio) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $textConcentration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string measurementPoint When strength is measured at a particular point or distance */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $measurementPoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept> country Where the strength range applies */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
		public array $country = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\Ingredient\IngredientSubstanceStrengthReferenceStrength> referenceStrength Strength expressed in terms of a reference substance */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
		public array $referenceStrength = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
