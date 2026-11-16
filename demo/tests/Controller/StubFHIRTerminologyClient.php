<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Ardenexal\FHIRTools\Component\Metadata\Contract\CodingValidationResult;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;

/** Test double with a fixed valid/invalid verdict — used to prove the "configured" branch of Check code. */
final class StubFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    public function __construct(
        private readonly bool $valid,
    ) {
    }

    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        return $this->valid;
    }

    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        return $this->valid;
    }

    public function validateCodingWithDisplay(
        string $valueSetUrl,
        string $system,
        string $code,
        string $display,
    ): CodingValidationResult {
        return new CodingValidationResult($this->valid, null);
    }
}
