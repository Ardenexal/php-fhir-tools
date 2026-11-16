<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit;

use Ardenexal\FHIRTools\Component\HttpClient\NullFHIRHttpClient;
use PHPUnit\Framework\TestCase;

final class NullFHIRHttpClientTest extends TestCase
{
    public function testSearchReturnsNull(): void
    {
        self::assertNull((new NullFHIRHttpClient())->search('Observation?subject=Patient/1', 'R5'));
    }

    public function testRequestReturnsNull(): void
    {
        self::assertNull((new NullFHIRHttpClient())->request('GET', 'metadata'));
    }
}
