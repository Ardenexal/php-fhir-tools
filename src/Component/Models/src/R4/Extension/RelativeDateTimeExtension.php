<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-relativeDateTime
 * @description A date/time value that is determined based on a duration offset from a target event. DEPRECATED: This extension has been deprecated in favor of the new relative-time extension.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-relativeDateTime', fhirVersion: 'R4')]
class RelativeDateTimeExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference target Relative to what event */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $target,
		/** @var StringPrimitive targetPath Relative to which element on the event */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $targetPath,
		/** @var CodePrimitive relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $relationship,
		/** @var Duration offset How long */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $offset,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'target', value: $this->target);
		$subExtensions[] = new Extension(url: 'targetPath', value: $this->targetPath);
		$subExtensions[] = new Extension(url: 'relationship', value: $this->relationship);
		$subExtensions[] = new Extension(url: 'offset', value: $this->offset);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-relativeDateTime',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$target = null;
		$targetPath = null;
		$relationship = null;
		$offset = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'target' && $ext->value instanceof Reference) {
		        $target = $ext->value;
		    }
		    if ($extUrl === 'targetPath' && $ext->value instanceof StringPrimitive) {
		        $targetPath = $ext->value;
		    }
		    if ($extUrl === 'relationship' && $ext->value instanceof CodePrimitive) {
		        $relationship = $ext->value;
		    }
		    if ($extUrl === 'offset' && $ext->value instanceof Duration) {
		        $offset = $ext->value;
		    }
		}

		return new static($target, $targetPath, $relationship, $offset, $id);
	}
}
