<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * A terminology client that can tell "not a member" apart from "no answer".
 *
 * {@see FHIRTerminologyClientInterface} answers membership with a bool, so a server that refused the
 * request, timed out or sent back something unreadable looks exactly like one that said the code is
 * outside the value set. A binding check built on that reports a finding the server never made.
 *
 * This is a separate, opt-in contract so existing implementations of the bool interface keep working.
 * A client that implements it lets {@see Validator\FHIRValueSetBindingValidator} report an unanswered
 * check as `fhir:unchecked-binding` instead of as an invalid code.
 */
interface FHIRTerminologyMembershipClientInterface extends FHIRTerminologyClientInterface
{
    /**
     * Whether a code with no known system is in the value set.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       The code; a string, int or BackedEnum
     *
     * @return bool|null the server's verdict, or null when it gave none (refused, unreachable or unreadable)
     */
    public function checkCode(string $valueSetUrl, mixed $value): ?bool;

    /**
     * Whether a system and code pair is in the value set.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI
     * @param string $code        The code within that system
     *
     * @return bool|null the server's verdict, or null when it gave none (refused, unreachable or unreadable)
     */
    public function checkCoding(string $valueSetUrl, string $system, string $code): ?bool;
}
