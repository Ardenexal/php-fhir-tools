<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRChoiceVariantReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Checks each occurrence of a profile-bound element against its value set.
 *
 * The check itself is FHIRValueSetBinding's, run through the context's validator so it uses the
 * same generated enums and terminology client as every other binding. Its findings are reported
 * again here, at the element's path, so they are attributed to the profile.
 *
 * The check looks enums up in the release of the class it runs on. A value set keeps its URL across
 * releases while its codes change, so an R5 element must not be checked against the R4 enum.
 *
 * @author Ardenexal
 */
final class FHIRProfileBindingValidator extends ConstraintValidator
{
    private readonly FHIRChoiceVariantReader $reader;

    public function __construct(
        PropertyAccessorInterface $propertyAccessor,
        ?FHIRChoiceVariantReader $reader = null,
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
        $this->reader = $reader ?? new FHIRChoiceVariantReader($propertyAccessor);
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRProfileBinding) {
            throw new UnexpectedTypeException($constraint, FHIRProfileBinding::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        $binding = new FHIRValueSetBinding($constraint->valueSetUrl, $constraint->strength, fromProfile: true, release: $this->releaseOf($value));

        // One group per parent the element hangs off; an absent ancestor yields none
        foreach ($this->reader->readGroups($value, $constraint->path) as $group) {
            if ($group['value'] === null || $group['value'] === []) {
                continue;
            }

            foreach ($this->context->getValidator()->validate($group['value'], $binding, ['Default']) as $violation) {
                $inner = $violation->getPropertyPath();
                $path  = match (true) {
                    $inner    === ''  => $group['path'],
                    $inner[0] === '[' => $group['path'] . $inner,
                    default           => $group['path'] . '.' . $inner,
                };

                $this->context->buildViolation($violation->getMessageTemplate(), $violation->getParameters())
                    ->atPath($path)
                    ->setInvalidValue($violation->getInvalidValue())
                    ->setCode($violation->getCode() ?? FHIRViolationCode::ERROR)
                    ->addViolation();
            }
        }
    }

    /**
     * The FHIR release ('R4', 'R4B', 'R5') of a generated resource or data type, or null when the
     * class does not say, in which case every configured release is searched.
     */
    private function releaseOf(object $value): ?string
    {
        $resource = $this->attributes->classAttributesInHierarchy($value, FhirResource::class);
        if ($resource !== []) {
            return $resource[0]->fhirVersion;
        }

        $type = $this->attributes->classAttributesInHierarchy($value, FHIRComplexType::class);

        return $type !== [] ? $type[0]->fhirVersion : null;
    }
}
