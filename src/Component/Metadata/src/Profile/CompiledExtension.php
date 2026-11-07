<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

/**
 * What an extension's StructureDefinition says about where the extension may appear and what it holds.
 *
 * Its `contexts` are the placement rules: the definition's contexts, then its context invariants.
 * A generated extension class carries the same facts in two forms: its contexts and context
 * invariants as class attributes, and its value type and sub-extensions in its constructor and
 * `fromSubExtensions()`. Runtime validation of a plain `Extension` reads them from here instead,
 * so both forms come from one translation.
 *
 * @author Ardenexal
 */
final readonly class CompiledExtension
{
    /**
     * @param string                  $url           Canonical URL of the extension
     * @param list<CompiledAttribute> $contexts      FHIRExtensionContext then FHIRContextInvariant attributes, in generator order
     * @param list<string>            $valueTypes    Type codes `Extension.value[x]` allows; empty when the value is prohibited
     * @param list<ExtensionSlice>    $subExtensions The `Extension.extension` slices, in snapshot order
     */
    public function __construct(
        public string $url,
        public array $contexts,
        public array $valueTypes,
        public array $subExtensions,
    ) {
    }

    /**
     * Whether the extension is built from sub-extensions rather than a value.
     *
     * Matches the generator: one sub-extension slice that may occur makes it complex.
     */
    public function isComplex(): bool
    {
        foreach ($this->subExtensions as $slice) {
            // The generator counts a slice with no stated maximum as prohibited here
            if (($slice->max ?? '0') !== '0') {
                return true;
            }
        }

        return false;
    }
}
