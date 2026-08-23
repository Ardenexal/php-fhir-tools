<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * Why a manifest case was not compared.
 *
 * Skips are counted per reason rather than as one total because the dangerous direction is silent:
 * a case that starts crashing after a change leaves the comparison set and lands here, which reads
 * as "one fewer ABOVE case" — an apparent improvement. Holding the categories separate means a
 * post-change run can be checked against the baseline arithmetically.
 */
enum SkipReason: string
{
    /** The manifest declares no Java outcome, so there is nothing to compare against. */
    case NoOracle = 'no-oracle';

    /** Not a .json or .xml payload, or the referenced file is absent from the vendored corpus. */
    case Unreadable = 'unreadable';

    /** The deserializer rejected the payload; we never got a resource to validate. */
    case DeserializeThrew = 'deserialize-threw';

    /** Validation itself blew up. Always suspicious — this is the category that must never grow. */
    case ValidateCrashed = 'validate-crashed';

    /**
     * The manifest configures the reference validator with an option we do not model, so its recorded
     * outcome is not a like-for-like comparison against our default run.
     *
     * Distinct from the exclusions in `ComparisonHarness::selectCases()`, which drop a case before it
     * is ever selected and therefore leave no trace in the arithmetic. A case that would otherwise be
     * compared must remain visible when it leaves the set — see this enum's own reasoning about silent
     * departures reading as improvements.
     */
    case UnmodelledOption = 'unmodelled-option';
}
