<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

/**
 * Inputs governing a single `Questionnaire/$populate` run.
 *
 * Population is **offline-first**: the caller supplies every launch-context resource up front (keyed by
 * its SDC `launchContext` name — `patient`, `encounter`, `user`, …), which the service binds as FHIRPath
 * external constants (`%patient`, `%encounter`, …). No live FHIR server, `x-fhir-query`, or
 * `dataEndpoint` fetching happens here — a live-fetch provider can be layered on later without changing
 * this seam.
 *
 * M01 scope: expression-based population from `launchContext` + `initialExpression` only. `variable`
 * chains, `itemPopulationContext`, and observation-based population arrive in M02.
 */
final class PopulateContext
{
    /**
     * @param array<string, object> $launchContextResources launch-context resources keyed by SDC
     *                                                      launchContext name (e.g. `patient` => Patient
     *                                                      model). Each is bound as the FHIRPath external
     *                                                      constant `%<name>`.
     */
    public function __construct(
        /** Model namespace the produced QuestionnaireResponse belongs to (R4-only for M01). */
        public readonly FhirVersion $fhirVersion = FhirVersion::R4,
        public readonly array $launchContextResources = [],
        /**
         * Reference string for `QuestionnaireResponse.subject` (e.g. `Patient/123`), or null to leave it
         * unset. The SDC populate guidance sets the subject; it is optional (0..1) on the QR.
         */
        public readonly ?string $subject = null,
        /**
         * Supplies candidate `Observation`s for observation-based population (`observationLinkPeriod`), or
         * null to disable it. Offline-first: the caller pre-fetches the data (see
         * {@see BundlePopulationDataProvider}); no live fetching happens in the library.
         */
        public readonly ?PopulationDataProviderInterface $dataProvider = null,
    ) {
    }
}
