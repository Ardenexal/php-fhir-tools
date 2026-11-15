<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Sdc\BundlePopulationDataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Which Bundle entries {@see BundlePopulationDataProvider} surfaces as Observations.
 */
final class BundlePopulationDataProviderTest extends TestCase
{
    public function testSurfacesObservationsAndProfiledObservationSubclassesOnly(): void
    {
        $observation = new ObservationResource(id: 'plain');
        // A profiled class generated on top of the core model keeps ObservationResource as an ancestor.
        $profiled = new class (id: 'profiled') extends ObservationResource {
        };
        $patient = new PatientResource(id: 'not-an-observation');

        $bundle = (object) ['entry' => [
            (object) ['resource' => $observation],
            (object) ['resource' => $patient],
            (object) ['resource' => $profiled],
        ]];

        self::assertSame([$observation, $profiled], (new BundlePopulationDataProvider($bundle))->observations());
    }
}
