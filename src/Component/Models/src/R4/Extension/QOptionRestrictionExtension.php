<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-optionRestriction
 * @description Allows disabling certain questionnaire options for the containing item based on evaluating expressions.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-optionRestriction', fhirVersion: 'R4')]
class QOptionRestrictionExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var array<int> option Option to exclude */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
		public array $option = [],
		/** @var Expression expression Expression to trigger exclusion */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression $expression,
		?string $id = null,
	) {
		$subExtensions = [];
		foreach ($this->option as $v) {
		    $subExtensions[] = new Extension(url: 'option', value: $v);
		}
		$subExtensions[] = new Extension(url: 'expression', value: $this->expression);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-optionRestriction',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$option = [];
		$expression = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'option' && is_int($ext->value)) {
		        $option[] = $ext->value;
		    }
		    if ($extUrl === 'expression' && $ext->value instanceof Expression) {
		        $expression = $ext->value;
		    }
		}

		return new static($option, $expression, $id);
	}
}
