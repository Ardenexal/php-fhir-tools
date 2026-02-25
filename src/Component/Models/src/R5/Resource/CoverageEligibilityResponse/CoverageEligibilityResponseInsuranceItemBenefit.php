<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\CoverageEligibilityResponse;

/**
 * @description Benefits used to date.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'CoverageEligibilityResponse',
	elementPath: 'CoverageEligibilityResponse.insurance.item.benefit',
	fhirVersion: 'R5',
)]
class CoverageEligibilityResponseInsuranceItemBenefit extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement
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
		'allowed' => [
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
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
					'jsonKey' => 'allowedUnsignedInt',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
					'jsonKey' => 'allowedString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Money',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
					'jsonKey' => 'allowedMoney',
					'isBuiltin' => false,
				],
			],
		],
		'used' => [
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
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
					'jsonKey' => 'usedUnsignedInt',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
					'jsonKey' => 'usedString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Money',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept type Benefit classification */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Money allowed Benefits allowed */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'unsignedInt',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
				'jsonKey' => 'allowedUnsignedInt',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
				'jsonKey' => 'allowedString',
			],
			[
				'fhirType' => 'Money',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
				'jsonKey' => 'allowedMoney',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Money|null $allowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Money used Benefits used */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'unsignedInt',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive',
				'jsonKey' => 'usedUnsignedInt',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
				'jsonKey' => 'usedString',
			],
			[
				'fhirType' => 'Money',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Money',
				'jsonKey' => 'usedMoney',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Money|null $used = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
