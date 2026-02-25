<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/VirtualServiceDetail
 * @description Virtual Service Contact Details.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'VirtualServiceDetail', fhirVersion: 'R5')]
class VirtualServiceDetail extends DataType
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
		'channelType' => [
			'fhirType' => 'Coding',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'address' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'url',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
					'jsonKey' => 'addressUrl',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
					'jsonKey' => 'addressString',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'ContactPoint',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
					'jsonKey' => 'addressContactPoint',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'ExtendedContactDetail',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
					'jsonKey' => 'addressExtendedContactDetail',
					'isBuiltin' => false,
				],
			],
		],
		'additionalInfo' => [
			'fhirType' => 'url',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'maxParticipants' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'sessionKey' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding channelType Channel Type */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
		public ?Coding $channelType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint|\Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail address Contact address/number */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'url',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
				'jsonKey' => 'addressUrl',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
				'jsonKey' => 'addressString',
			],
			[
				'fhirType' => 'ContactPoint',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
				'jsonKey' => 'addressContactPoint',
			],
			[
				'fhirType' => 'ExtendedContactDetail',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
				'jsonKey' => 'addressExtendedContactDetail',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|ContactPoint|ExtendedContactDetail|null $address = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive> additionalInfo Address to see alternative connection details */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
		public array $additionalInfo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive maxParticipants Maximum number of participants supported by the virtual service */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive $maxParticipants = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string sessionKey Session Key required by the virtual service */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive|string|null $sessionKey = null,
	) {
		parent::__construct($id, $extension);
	}
}
