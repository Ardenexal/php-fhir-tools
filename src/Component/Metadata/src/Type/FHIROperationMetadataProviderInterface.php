<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

/**
 * Reads the operation attributes a generated operation class carries.
 *
 * Kept apart from {@see FHIRStructureKindProviderInterface} because operations are a different axis:
 * an operation holder is not a FHIR structure, and asking "what kind of structure is this" of one
 * would sensibly answer nothing. Folding the two together would mean one provider whose answers were
 * mutually exclusive in one dimension and unrelated in another.
 *
 * Every method answers null or an empty list for a class that carries no operation metadata, rather
 * than throwing. Callers that need an error decide what to raise, because the useful message differs
 * between "not an operation holder" and "not an operation payload".
 *
 * @author Ardenexal
 */
interface FHIROperationMetadataProviderInterface
{
    /**
     * The `#[FhirOperation]` a class declares.
     *
     * @param object|string $subject An instance or class name
     *
     * @return FhirOperation|null The attribute, or null when the class is not an operation holder
     */
    public function operationOf(object|string $subject): ?FhirOperation;

    /**
     * The `#[FhirOperationPayload]` a class declares.
     *
     * @param object|string $subject An instance or class name
     *
     * @return FhirOperationPayload|null The attribute, or null when the class is not a payload
     */
    public function payloadOf(object|string $subject): ?FhirOperationPayload;

    /**
     * Every `#[FhirOperationParameter]` declared on the class's properties, in declaration order.
     *
     * @param object|string $subject An instance or class name
     *
     * @return list<FhirOperationParameter> Parameter descriptors; empty when the class declares none
     */
    public function parametersOf(object|string $subject): array;
}
