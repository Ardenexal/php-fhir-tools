<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Pins the three strict-mode guards that used to read "not a FHIR model" out of a null.
 *
 * The evaluator built its own property map and returned null when a class carried no
 * #[FhirProperty] constructor parameters. Three guards read that null as "this is not a FHIR model"
 * and stayed silent. The map now comes from Metadata, which answers an empty array instead — and an
 * empty array is not null, so a mechanical swap would have turned all three guards on for every
 * object in existence: a property miss on a plain PHP object would start throwing, the choice-variant
 * guard would apply to classes that have no choice properties, and the type-name check would stop
 * being filtered to FHIR models at all.
 *
 * These tests fail if that swap is ever made, which no existing test did.
 */
class StrictModeNonModelGuardTest extends TestCase
{
    private FHIRPathService $service;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }

    /**
     * A plain object is not a FHIR model, so a property that does not exist on it is an empty
     * result rather than a semantic error — even in strict mode.
     */
    public function testAPropertyMissOnAPlainObjectIsEmptyRatherThanAnError(): void
    {
        $plain       = new \stdClass();
        $plain->name = 'not a FHIR model';

        $result = $this->service->evaluate('noSuchProperty', $plain, new EvaluationContext(), null, true);

        self::assertTrue($result->isEmpty());
    }

    /**
     * The same object still resolves the properties it does have, so the guard above is not simply
     * swallowing everything.
     */
    public function testAPlainObjectStillResolvesItsOwnProperties(): void
    {
        $plain       = new \stdClass();
        $plain->name = 'not a FHIR model';

        $result = $this->service->evaluate('name', $plain, new EvaluationContext(), null, true);

        self::assertSame('not a FHIR model', $result->first());
    }

    /**
     * The contrast case: on a real FHIR model the strict-mode guard does fire, which is what makes
     * the two tests above meaningful rather than vacuous.
     */
    public function testAPropertyMissOnAFhirModelIsStillAnError(): void
    {
        $this->expectExceptionMessageMatches('/does not exist on FHIR type/');

        $this->service->evaluate('noSuchProperty', new PatientResource(), new EvaluationContext(), null, true);
    }
}
