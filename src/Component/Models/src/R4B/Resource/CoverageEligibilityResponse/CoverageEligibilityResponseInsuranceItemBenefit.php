<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\CoverageEligibilityResponse;

/**
 * @description Benefits used to date.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'CoverageEligibilityResponse',
	elementPath: 'CoverageEligibilityResponse.insurance.item.benefit',
	fhirVersion: 'R4B',
)]
class CoverageEligibilityResponseInsuranceItemBenefit extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'type' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'allowedX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'unsignedInt',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive',
					'jsonKey' => 'allowedUnsignedInt',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
					'jsonKey' => 'allowedString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Money',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
					'jsonKey' => 'allowedMoney',
					'isBuiltin' => false,
				],
			],
		],
		'usedX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'unsignedInt',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive',
					'jsonKey' => 'usedUnsignedInt',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
					'jsonKey' => 'usedString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Money',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
					'jsonKey' => 'usedMoney',
					'isBuiltin' => false,
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept type Benefit classification */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money allowedX Benefits allowed */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'unsignedInt',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive',
				'jsonKey' => 'allowedUnsignedInt',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'allowedString',
			],
			[
				'fhirType' => 'Money',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
				'jsonKey' => 'allowedMoney',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money|null $allowedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money usedX Benefits used */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'unsignedInt',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive',
				'jsonKey' => 'usedUnsignedInt',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'usedString',
			],
			[
				'fhirType' => 'Money',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
				'jsonKey' => 'usedMoney',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money|null $usedX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
