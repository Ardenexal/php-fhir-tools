<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

/**
 * A terminology client that wraps another, such as a cache in front of a server.
 *
 * A decorator answers whatever its inner client answers, so it cannot tell a caller whether a real
 * terminology server stands behind it. Exposing the inner client lets a caller look through the
 * wrapper: the Symfony bundle wraps the null client in a cache when no server is configured, and that
 * null client says yes to every code.
 */
interface FHIRTerminologyClientDecoratorInterface extends FHIRTerminologyClientInterface
{
    /**
     * The client this one wraps.
     */
    public function innerClient(): FHIRTerminologyClientInterface;
}
