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
     * populate service filters by code, status, and link period, and selects the most recent itself.
     *
     * @return list<object> version-specific `Observation` model objects
     */
    public function observations(): array;
}
