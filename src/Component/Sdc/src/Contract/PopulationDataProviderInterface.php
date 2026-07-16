<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Contract;

use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;

/**
 * Supplies the candidate resources observation-based population draws on.
 *
 * This is the named data seam that keeps population **offline-first**: the caller pre-fetches the
 * relevant `Observation`s (e.g. into a `data` Bundle) and hands them over, so no live FHIR server or
 * `x-fhir-query` fetching happens inside the library. A future live-fetch provider can implement this
 * same interface without any change to {@see FHIRQuestionnairePopulateService} or {@see PopulateContext}.
 */
interface PopulationDataProviderInterface
{
    /**
     * All candidate `Observation` resources available for population. Order is not significant — the
     * populate service filters by code, status, link period, and (when a subject is stated) `subject`,
     * and selects the most recent itself.
     *
     * A subject filter is applied only when {@see PopulateContext::$subject} is set: the service then
     * excludes any Observation not confirmably about that subject, so a broad or mixed-subject Bundle
     * cannot leak another patient's value. When no subject is stated the caller remains responsible for
     * supplying only relevant Observations.
     *
     * @return list<object> version-specific `Observation` model objects
     */
    public function observations(): array;
}
