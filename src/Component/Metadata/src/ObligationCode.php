<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * FHIR obligation codes from http://hl7.org/fhir/CodeSystem/obligation.
 *
 * Covers all 46 codes: 43 leaf codes (SHALL/SHOULD/MAY:behavior) and 3 composite shorthand codes.
 * Composite codes (V2_RE, IHE_R2, STD) expand to multiple obligations — call isComposite() before
 * calling strength() on an unknown code.
 *
 * @author Ardenexal
 */
enum ObligationCode: string
{
    // --- Population obligations ---
    case SHALL_POPULATE              = 'SHALL:populate';
    case SHOULD_POPULATE             = 'SHOULD:populate';
    case SHALL_ABLE_TO_POPULATE      = 'SHALL:able-to-populate';
    case SHOULD_ABLE_TO_POPULATE     = 'SHOULD:able-to-populate';
    case MAY_ABLE_TO_POPULATE        = 'MAY:able-to-populate';
    case SHALL_POPULATE_IF_KNOWN     = 'SHALL:populate-if-known';
    case SHOULD_POPULATE_IF_KNOWN    = 'SHOULD:populate-if-known';

    // --- Display / presentation obligations ---
    case SHALL_DISPLAY               = 'SHALL:display';
    case SHOULD_DISPLAY              = 'SHOULD:display';
    case MAY_DISPLAY                 = 'MAY:display';
    case SHALL_IN_NARRATIVE          = 'SHALL:in-narrative';
    case SHOULD_IN_NARRATIVE         = 'SHOULD:in-narrative';
    case MAY_IN_NARRATIVE            = 'MAY:in-narrative';
    case SHALL_EXCLUDE_NARRATIVE     = 'SHALL:exclude-narrative';
    case SHOULD_EXCLUDE_NARRATIVE    = 'SHOULD:exclude-narrative';
    case SHALL_PRINT                 = 'SHALL:print';
    case SHOULD_PRINT                = 'SHOULD:print';
    case MAY_PRINT                   = 'MAY:print';
    case SHALL_EXPLAIN               = 'SHALL:explain';
    case SHOULD_EXPLAIN              = 'SHOULD:explain';

    // --- User interaction obligations ---
    case SHALL_USER_INPUT            = 'SHALL:user-input';
    case SHOULD_USER_INPUT           = 'SHOULD:user-input';
    case MAY_USER_INPUT              = 'MAY:user-input';

    // --- Storage / persistence obligations ---
    case SHALL_PERSIST               = 'SHALL:persist';
    case SHOULD_PERSIST              = 'SHOULD:persist';
    case MAY_PERSIST                 = 'MAY:persist';

    // --- Processing obligations ---
    case SHALL_HANDLE                = 'SHALL:handle';
    case SHOULD_HANDLE               = 'SHOULD:handle';
    case SHALL_PROCESS               = 'SHALL:process';
    case SHOULD_PROCESS              = 'SHOULD:process';
    case MAY_PROCESS                 = 'MAY:process';

    // --- Data integrity obligations ---
    case SHALL_NO_ALTER              = 'SHALL:no-alter';
    case SHOULD_NO_ALTER             = 'SHOULD:no-alter';
    case MAY_ALTER                   = 'MAY:alter';
    case SHALL_NO_ERROR              = 'SHALL:no-error';
    case SHOULD_NO_ERROR             = 'SHOULD:no-error';
    case SHALL_REJECT_INVALID        = 'SHALL:reject-invalid';
    case SHOULD_REJECT_INVALID       = 'SHOULD:reject-invalid';
    case SHALL_ACCEPT_INVALID        = 'SHALL:accept-invalid';
    case SHOULD_ACCEPT_INVALID       = 'SHOULD:accept-invalid';

    // --- Ignore obligation ---
    case SHALL_IGNORE                = 'SHALL:ignore';
    case SHOULD_IGNORE               = 'SHOULD:ignore';
    case MAY_IGNORE                  = 'MAY:ignore';

    // --- Composite shorthand codes (expand to multiple SHALL obligations) ---
    case V2_RE                       = 'v2-re';
    case IHE_R2                      = 'ihe-r2';
    case STD                         = 'std';

    /**
     * Returns true when this is a composite shorthand code (v2-re, ihe-r2, std).
     * Composite codes expand to multiple obligations and have no single strength.
     */
    public function isComposite(): bool
    {
        return !str_contains($this->value, ':');
    }

    /**
     * Returns the obligation strength: 'SHALL', 'SHOULD', or 'MAY'.
     *
     * @throws \LogicException when called on a composite code — check isComposite() first
     */
    public function strength(): string
    {
        $colonPos = strpos($this->value, ':');
        if ($colonPos === false) {
            throw new \LogicException("Composite obligation code '{$this->value}' has no single strength; call isComposite() before strength()");
        }

        return substr($this->value, 0, $colonPos);
    }

    /**
     * Returns true for obligations that require an application to produce or carry data
     * (i.e. machine-checkable from a resource instance at validation time).
     *
     * Covers: populate, able-to-populate, populate-if-known, and composite shorthand codes
     * that expand to these population-type obligations.
     */
    public function isPopulationObligation(): bool
    {
        if (in_array($this, [self::V2_RE, self::IHE_R2, self::STD], true)) {
            return true;
        }

        $behavior = $this->getBehavior();

        return in_array($behavior, ['populate', 'able-to-populate', 'populate-if-known'], true);
    }

    /**
     * Returns true for obligations that describe application behaviour only — not checkable
     * by inspecting a resource instance (e.g. display, persist, handle, process, print,
     * user-input, explain, in-narrative, ignore, no-alter).
     */
    public function isBehaviourOnly(): bool
    {
        return !$this->isPopulationObligation();
    }

    private function getBehavior(): string
    {
        $colonPos = strpos($this->value, ':');

        return $colonPos !== false ? substr($this->value, $colonPos + 1) : $this->value;
    }
}
