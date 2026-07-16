<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\Sdc\Contract\PopulationDataProviderInterface;
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
 * Supported population mechanisms: `launchContext` + `initialExpression`, root/item `variable` chains,
 * `itemPopulationContext` repeating groups, and observation-based population (`observationLinkPeriod`,
 * via {@see $dataProvider}). Non-FHIRPath expression languages (CQL, `x-fhir-query`) and StructureMap-
 * based population are out of scope — see the component README and the sdc-populate backlog.
 */
final class PopulateContext
{
    /**
     * @param FhirVersion                          $fhirVersion            model namespace the produced
     *                                                                     QuestionnaireResponse belongs to
     *                                                                     (`R4`/`R4B`/`R5`)
     * @param array<string, object>                $launchContextResources launch-context resources keyed by
     *                                                                     SDC launchContext name (e.g.
     *                                                                     `patient` => Patient model); each
     *                                                                     is bound as the FHIRPath external
     *                                                                     constant `%<name>`
     * @param string|null                          $subject                reference for
     *                                                                     `QuestionnaireResponse.subject`
     *                                                                     (e.g. `Patient/123`), or null to
     *                                                                     leave it unset — the SDC guidance
     *                                                                     sets it, but it is optional (0..1).
     *                                                                     When set, it also scopes
     *                                                                     observation-based population: only
     *                                                                     `Observation`s confirmably about
     *                                                                     this subject are eligible (see
     *                                                                     {@see ObservationSelector})
     * @param PopulationDataProviderInterface|null $dataProvider           supplies candidate `Observation`s
     *                                                                     for observation-based population
     *                                                                     (`observationLinkPeriod`), or null
     *                                                                     to disable it; offline-first, the
     *                                                                     caller pre-fetches the data (see
     *                                                                     {@see BundlePopulationDataProvider})
     */
    public function __construct(
        public readonly FhirVersion $fhirVersion = FhirVersion::R4,
        public readonly array $launchContextResources = [],
        public readonly ?string $subject = null,
        public readonly ?PopulationDataProviderInterface $dataProvider = null,
    ) {
    }
}
