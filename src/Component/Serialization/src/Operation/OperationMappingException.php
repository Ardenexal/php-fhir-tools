<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Operation;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRToolsException;

/**
 * Thrown when a typed operation payload cannot be mapped to or from a `Parameters` resource.
 *
 * Distinct from a serialization failure: by the time this is thrown the object graph is intact and
 * the problem is the operation contract — a missing required parameter, a value the parameter's
 * declared types cannot accept, or metadata that does not describe the class it is attached to.
 *
 * @author Ardenexal
 */
final class OperationMappingException extends FHIRToolsException
{
    public static function missingRequiredParameter(string $wireName, string $class): self
    {
        return new self(sprintf(
            'Operation parameter "%s" is required (min >= 1) but was not set on %s. '
            . 'Emitting it would produce invalid FHIR.',
            $wireName,
            $class,
        ));
    }

    public static function ambiguousPolymorphicValue(string $wireName, string $given): self
    {
        return new self(sprintf(
            'Operation parameter "%s" is polymorphic, so the intended type cannot be inferred from a '
            . 'bare %s. Pass the typed variant (e.g. a CodePrimitive or Coding) instead.',
            $wireName,
            $given,
        ));
    }

    public static function unmappableValue(string $wireName, string $fhirType, string $given): self
    {
        return new self(sprintf(
            'Operation parameter "%s" is declared as FHIR type "%s" but was given a %s, which has no '
            . 'mapping onto a Parameters value slot.',
            $wireName,
            $fhirType,
            $given,
        ));
    }

    public static function notAnOperationPayload(string $class): self
    {
        return new self(sprintf(
            '%s carries no #[FhirOperationParameter] attributes, so it is not an operation payload class.',
            $class,
        ));
    }

    public static function notAnOperationHolder(string $class): self
    {
        return new self(sprintf(
            '%s carries no #[FhirOperation] attribute, so the response shape is unknown. Pass the '
            . 'generated operation holder class, not the payload class.',
            $class,
        ));
    }

    public static function unexpectedResponseType(string $expected, string $given, string $shape): self
    {
        return new self(sprintf(
            'This operation declares output shape "%s", so the response body must be a %s — got %s. '
            . 'A server that wrapped the resource in Parameters is not conformant here.',
            $shape,
            $expected,
            $given,
        ));
    }

    public static function missingNamedOutputParameter(string $wireName): self
    {
        return new self(sprintf(
            'The response carries no parameter named "%s", which this operation declares as its sole '
            . 'resource-typed output.',
            $wireName,
        ));
    }

    public static function unresolvableType(string $fhirType): self
    {
        return new self(sprintf(
            'The type resolver returned no class for "%s". Either this FHIR version does not ship it, '
            . 'or the resolver is scoped to a version that does not.',
            $fhirType,
        ));
    }
}
