<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

interface FHIRTerminologyClientFactoryInterface
{
    public function createForServer(string $baseUrl): FHIRTerminologyClientInterface;
}
