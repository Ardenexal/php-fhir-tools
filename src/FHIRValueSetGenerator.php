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
                    $this->addConceptsFromCodeSystem($include, $enumType);
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
     * @param array{
     *     system: string,
     *     concept?: array{code: string, display: string}
     * } $include
     * @param EnumType $enum
     *
     * @return void
     */
    private function addConceptsFromCodeSystem(array $include, EnumType $enum): void
    {
        $codeSystem = $this->builderContext->getDefinition($include['system']);
        if($codeSystem !== null){
            foreach ($codeSystem['concept'] as $concept) {
                $enum->addCase(u($concept['display'])->upper()->snake()->toString(), $concept['code'])
                     ->addComment($concept['display'] ?? '');
            }
        }

        if(isset($include['concept']) && is_array($include['concept'])){
            foreach ($include['concept'] as $concept) {
                $display = $concept['display'] ?? $concept['code'];
                $enum->addCase(u($display)->upper()->snake()->toString(), $concept['code'])
                    ->addComment($display ?? '');
                foreach ($concept['extension'] ?? [] as $extension) {
                    if ($extension['url'] !== 'http://hl7.org/fhir/StructureDefinition/valueset-concept-definition' && isset($extension['valueString'])) {
                        $enum->addComment($extension['valueString']);
                    }
                }
            }
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
