<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

interface FHIRTerminologyClientFactoryInterface
{
    public function createForServer(string $baseUrl): FHIRTerminologyClientInterface;
}
