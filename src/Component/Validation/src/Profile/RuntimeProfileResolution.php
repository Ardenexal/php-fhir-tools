<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;

/**
 * Outcome of resolving a profile URL: its compiled rule chain, or why it has none.
 *
 * @author Ardenexal
 */
final readonly class RuntimeProfileResolution
{
    /**
     * @param string                     $profileUrl  The URL that was asked for, as given
     * @param string                     $fhirVersion 'R4', 'R4B' or 'R5'
     * @param list<CompiledProfile>      $chain       Compiled rules, root-most ancestor first and the requested profile last;
     *                                                empty when the URL names a base definition rather than a constraint
     * @param string|null                $rootType    The type the chain constrains, e.g. 'Observation' or 'Extension'
     * @param string|null                $kind        The StructureDefinition kind: 'resource' or 'complex-type'
     * @param RuntimeProfileFailure|null $failure     Null when resolution succeeded
     * @param string|null                $failedUrl   The URL the failure is about; for unresolved-parent, the missing parent
     * @param string                     $message     A sentence explaining the failure; empty on success
     */
    private function __construct(
        public string $profileUrl,
        public string $fhirVersion,
        public array $chain,
        public ?string $rootType,
        public ?string $kind,
        public ?RuntimeProfileFailure $failure,
        public ?string $failedUrl,
        public string $message,
    ) {
    }

    /**
     * @param list<CompiledProfile> $chain Root-most ancestor first
     */
    public static function resolved(string $profileUrl, string $fhirVersion, array $chain, string $rootType, string $kind): self
    {
        return new self($profileUrl, $fhirVersion, $chain, $rootType, $kind, null, null, '');
    }

    public static function failed(
        string $profileUrl,
        string $fhirVersion,
        RuntimeProfileFailure $failure,
        string $failedUrl,
        string $message,
    ): self {
        return new self($profileUrl, $fhirVersion, [], null, null, $failure, $failedUrl, $message);
    }

    public function isResolved(): bool
    {
        return $this->failure === null;
    }
}
