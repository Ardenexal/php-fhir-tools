<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationRequest;

/**
 * @description Indicates whether or not substitution can or should be part of the dispense. In some cases, substitution must happen, in other cases substitution must not happen. This block explains the prescriber's intent. If nothing is specified substitution may be done.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.substitution', fhirVersion: 'R4B')]
class MedicationRequestSubstitution extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'allowedX' => [
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
					'jsonKey' => 'allowedBoolean',
					'isBuiltin' => true,
				],
				[
					'fhirType' => 'CodeableConcept',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
					'jsonKey' => 'allowedCodeableConcept',
					'isBuiltin' => false,
				],
			],
		],
		'reason' => [
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
		/** @var null|bool|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept allowedX Whether substitution is allowed or not */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isRequired: true,
			isChoice: true,
			variants: [
			['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'allowedBoolean'],
			[
				'fhirType' => 'CodeableConcept',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
				'jsonKey' => 'allowedCodeableConcept',
			],
		],
		)]
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public bool|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept|null $allowedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept reason Why should (not) substitution be made */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $reason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
