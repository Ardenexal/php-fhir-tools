<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\MessageHeader;

/**
 * @description The source application from which this message originated.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.source', fhirVersion: 'R4B')]
class MessageHeaderSource extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'name' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'software' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'version' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'contact' => [
			'fhirType' => 'ContactPoint',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'endpoint' => [
			'fhirType' => 'url',
			'propertyKind' => 'primitive',
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string name Name of system */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string software Name of software running the system */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $software = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string version Version of software running */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint contact Human contact for problems */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'ContactPoint', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint $contact = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive endpoint Actual message source address or id */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'url', propertyKind: 'primitive', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive $endpoint = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
