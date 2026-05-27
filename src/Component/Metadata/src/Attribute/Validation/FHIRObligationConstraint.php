<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Stub Symfony Constraint marking a property that carries a population-class FHIR obligation.
 *
 * Emitted by the generator alongside #[FHIRObligation] on constructor parameters whose
 * StructureDefinition element declares at least one obligation where
 * ObligationCode::isPopulationObligation() === true.
 *
 * No validator logic is attached yet — FHIRObligationConstraintValidator is implemented in M12.
 * This stub exists so generator output is valid PHP and downstream code can reference the class
 * before M12 is started.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRObligationConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
