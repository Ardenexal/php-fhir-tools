<?php

namespace Ardenexal\FHIRTools;

use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Intl\Currencies;

use function Symfony\Component\String\u;

/**
 * Class FHIRTools
 *
 * @phpstan-type GenerationContext array{targetNamespace: PhpNamespace, classPrefix: string}
 *
 * @package Ardenexal\FHIRTools
 */
class FHIRValueSetGenerator
{
    private BuilderContext $builderContext;

    /**
     * @param BuilderContext $builderContext
     */
    public function __construct(
        BuilderContext $builderContext
    ) {
        $this->builderContext = $builderContext;
    }

    /**
     * @param mixed[] $valueSet
     * @param string  $version
     *
     * @return EnumType
     */
    public function generateEnum(array $valueSet, string $version): EnumType
    {
        $className    = BuilderContext::DEFAULT_CLASS_PREFIX . u($valueSet['name'])->pascal();
        $enumType     = new EnumType($className, $this->builderContext->getEnumNamespace($version));
        $enumType->addComment('ValueSet: ' . ($valueSet['title'] ?? $valueSet['name']));
        $enumType->addComment('URL: ' . ($valueSet['url'] ?? 'unknown'));
        $enumType->addComment('Version: ' . ($valueSet['version'] ?? 'unknown'));
        $enumType->addComment('Description: ' . ($valueSet['description'] ?? 'No description provided.'));

        foreach ($valueSet['compose']['include'] as $include) {
            if (isset($include['system'])) {
                if ($include['system'] === 'urn:iso:std:iso:4217') {
                    $this->addCurrencyCodes($enumType);
                } elseif ($include['system'] === 'urn:ietf:bcp:13') {
                    // There are too many mimetypes to include here, Use other valuesets to have a more manageable set
                    continue;
                } else {
                    $this->addConceptsFromCodeSystem($include['system'], $enumType);
                }
                continue;
            }
            if (!isset($include['concept'])) {
                continue;
            }
            foreach ($include['concept'] as $concept) {
                $enumType->addCase(u($concept['code'])->upper()->snake()->toString(), $concept['code'])
                ->addComment($concept['display'] ?? '');
            }
        }


        return $enumType;
    }

    /**
     * @param string   $system
     * @param EnumType $enum
     *
     * @return void
     */
    private function addConceptsFromCodeSystem(mixed $system, EnumType $enum): void
    {
        $codeSystem = $this->builderContext->getDefinition($system);

        foreach ($codeSystem['concept'] as $concept) {
            $enum->addCase(u($concept['display'])->upper()->snake()->toString(), $concept['code'])
                 ->addComment($concept['display'] ?? '');
        }
    }

    /**
     * @param EnumType $enumType
     *
     * @return void
     */
    private function addCurrencyCodes(EnumType $enumType): void
    {
        foreach (Currencies::getCurrencyCodes() as $code) {
            $caseName = u(Currencies::getName($code))->upper()->snake()->toString();
            $enumType->addCase($caseName, $code)
                     ->addComment(Currencies::getName($code));
        }
    }
}
