<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyMembershipClientInterface;

/**
 * Asks a terminology client whether one code is in a value set.
 *
 * A client that implements {@see FHIRTerminologyMembershipClientInterface} can answer null when it
 * gave no verdict. Any other client answers with a bool only.
 *
 * @internal shared by {@see SliceDiscriminatorMatcher} and {@see Validator\FHIRValueSetBindingValidator}, not part of the public API
 */
final class TerminologyMembership
{
    /**
     * The client's verdict on one code, with its system when the coding carries one, or null when a
     * client that can say so gave none.
     */
    public static function check(FHIRTerminologyClientInterface $client, string $valueSetUrl, ?string $system, string $code): ?bool
    {
        if ($client instanceof FHIRTerminologyMembershipClientInterface) {
            return $system === null ? $client->checkCode($valueSetUrl, $code) : $client->checkCoding($valueSetUrl, $system, $code);
        }

        return $system === null ? $client->validateCode($valueSetUrl, $code) : $client->validateCoding($valueSetUrl, $system, $code);
    }

    private function __construct()
    {
    }
}
