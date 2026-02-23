<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\CatalogEntry;

/**
 * @description Used for example, to point to a substance, or to a device used to administer a medication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CatalogEntry', elementPath: 'CatalogEntry.relatedEntry', fhirVersion: 'R4B')]
class CatalogEntryRelatedEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'relationtype' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'item' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CatalogEntryRelationTypeType relationtype triggers | is-replaced-by */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CatalogEntryRelationTypeType $relationtype = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference item The reference to the related item */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $item = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
