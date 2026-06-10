<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpFHIRTerminologyClientFactory implements FHIRTerminologyClientFactoryInterface
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function createForServer(string $baseUrl): FHIRTerminologyClientInterface
    {
        return new HttpFHIRTerminologyClient($this->httpClient, $baseUrl);
    }
}
