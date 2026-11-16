<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

/**
 * Inputs governing a single `QuestionnaireResponse/$extract` run.
 *
 * Carries the target FHIR version (which model namespace the produced resources belong to) and,
 * optionally, the source `Questionnaire` the response was completed against. The Questionnaire is
 * where the SDC extraction directives live (`observationExtract`, `definitionExtract`,
 * `templateExtract`); observation-based extraction cannot proceed without it, since a bare
 * `QuestionnaireResponse` carries neither the extract flags nor the `item.code` a produced
 * `Observation` needs.
 */
final class ExtractContext
{
    public function __construct(
        /** Model namespace the produced resources belong to (R4-only for M01). */
        public readonly FhirVersion $fhirVersion = FhirVersion::R4,
        /**
         * The source Questionnaire carrying the SDC extract directives, or null. When null,
         * observation-based extraction has nothing to key off and yields an empty transaction Bundle.
         */
        public readonly ?object $questionnaire = null,
        /**
         * When true, append a `Provenance` entry to the transaction Bundle attesting the extraction:
         * its `target` references every extracted resource and its `entity` (`role = source`) references
         * the source QuestionnaireResponse. Opt-in so the default output stays oracle-comparable. No
         * Provenance is emitted when nothing was extracted (a `Provenance.target` is 1..*).
         */
        public readonly bool $emitProvenance = false,
    ) {
    }
}
