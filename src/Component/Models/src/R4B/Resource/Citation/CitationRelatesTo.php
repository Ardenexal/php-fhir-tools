<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Citation;

/**
 * @description Artifact related to the Citation Resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.relatesTo', fhirVersion: 'R4B')]
class CitationRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'relationshipType' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'targetClassifier' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'targetX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'uri',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive',
					'jsonKey' => 'targetUri',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Identifier',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
					'jsonKey' => 'targetIdentifier',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Reference',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
					'jsonKey' => 'targetReference',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Attachment',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment',
					'jsonKey' => 'targetAttachment',
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept relationshipType How the Citation resource relates to the target artifact */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $relationshipType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept> targetClassifier The clasification of the related artifact */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
		public array $targetClassifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment targetX The article or artifact that the Citation Resource is related to */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isRequired: true,
			isChoice: true,
			variants: [
			[
				'fhirType' => 'uri',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive',
				'jsonKey' => 'targetUri',
			],
			[
				'fhirType' => 'Identifier',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
				'jsonKey' => 'targetIdentifier',
			],
			[
				'fhirType' => 'Reference',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
				'jsonKey' => 'targetReference',
			],
			[
				'fhirType' => 'Attachment',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment',
				'jsonKey' => 'targetAttachment',
			],
		],
		)]
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment|null $targetX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
