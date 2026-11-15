<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRPathResourceContext;
use PHPUnit\Framework\TestCase;

/**
 * A resolved `%resource` is reused only within the scope it was resolved in.
 *
 * The invariant validators pass Symfony's execution context as the scope, which lasts one
 * `validate()` call. A model changed between two validations must be resolved again in the second.
 */
final class FHIRPathResourceContextTest extends TestCase
{
    public function testANewScopeResolvesAgainAfterTheModelChanges(): void
    {
        $first   = new ObservationResource();
        $patient = new PatientResource(contained: [$first]);

        $firstContext = FHIRPathResourceContext::at($patient, 'contained[0].code', new \stdClass());
        self::assertSame($first, $firstContext->getRootResource());
        self::assertSame($patient, $firstContext->getContainerResource());

        $second             = new ObservationResource();
        $patient->contained = [$second];

        self::assertSame($second, FHIRPathResourceContext::at($patient, 'contained[0].code', new \stdClass())->getRootResource());
    }

    /**
     * Within one scope the path is resolved once, so a later answer is the first one.
     */
    public function testTheSameScopeReusesTheFirstResolution(): void
    {
        $first   = new ObservationResource();
        $patient = new PatientResource(contained: [$first]);
        $scope   = new \stdClass();

        FHIRPathResourceContext::at($patient, 'contained[0].code', $scope);
        $patient->contained = [new ObservationResource()];

        self::assertSame($first, FHIRPathResourceContext::at($patient, 'contained[0].code', $scope)->getRootResource());
        self::assertNotSame($first, FHIRPathResourceContext::at($patient, 'contained[0].code')->getRootResource(), 'no scope resolves every time');
    }
}
