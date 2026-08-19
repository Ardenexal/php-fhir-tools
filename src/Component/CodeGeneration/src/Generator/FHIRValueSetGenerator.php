<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\String\Slugger\AsciiSlugger;

use function Symfony\Component\String\u;

/**
 * Generates PHP enums from FHIR ValueSets and CodeSystems
 *
 * This class is responsible for converting FHIR ValueSets and CodeSystems
 * into PHP enums with proper naming and documentation. It handles:
 *
 * - Enum generation from ValueSet definitions
 * - Code system concept processing
 * - Currency code integration for ISO 4217 systems
 * - Concept name normalization and collision handling
 * - Enhanced error handling and validation
 * - Support for various FHIR terminology systems
 *
 * The generator produces PSR-12 compliant enums with descriptive case names
 * and comprehensive documentation for each enum value.
 *
 * @phpstan-type GenerationContext array{targetNamespace: PhpNamespace, classPrefix: string}
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class FHIRValueSetGenerator implements GeneratorInterface
{
    /**
     * String slugger for normalizing concept names into valid PHP identifiers
     *
     * @var AsciiSlugger
     */
    private AsciiSlugger $slugger;

    /**
     * Construct a new FHIRValueSetGenerator
     */
    public function __construct()
    {
        $this->slugger = new AsciiSlugger('en', [
            'en' => [
                '=' => 'equals',
                '<' => 'less_than',
                '>' => 'greater_than',
                '&' => 'and',
            ],
        ]);
    }

    /**
     * Check if this generator can handle the given definition
     *
     * @param array<string, mixed> $definition The FHIR definition to check
     *
     * @return bool True if this generator can handle the definition
     */
    public function canGenerate(array $definition): bool
    {
        return isset($definition['resourceType']) && $definition['resourceType'] === 'ValueSet';
    }

    /**
     * Get the priority of this generator (higher numbers = higher priority)
     *
     * @return int The priority value
     */
    public function getPriority(): int
    {
        return 90; // High priority for value sets
    }

    /**
     * Generate PHP code from FHIR definition
     *
     * @param array<string, mixed>    $definition The FHIR definition to generate code from
     * @param string                  $version    The FHIR version
     * @param BuilderContextInterface $context    The builder context for managing generated types
     *
     * @return EnumType The generated PHP enum
     *
     * @throws GenerationException When generation fails
     */
    public function generate(array $definition, string $version, BuilderContextInterface $context): EnumType
    {
        return $this->generateEnum($definition, $version, $context);
    }

    /**
     * @param array<string,mixed>     $valueSet
     * @param string                  $version
     * @param BuilderContextInterface $builderContext
     *
     * @return EnumType
     */
    public function generateEnum(array $valueSet, string $version, BuilderContextInterface $builderContext): EnumType
    {
        $className = ClassNameResolver::resolveClassName($valueSet['url'], $valueSet['name']);
        $enumType  = new EnumType($className, $builderContext->getEnumNamespace($version));
        $enumType->addComment('ValueSet: ' . ($valueSet['title'] ?? $valueSet['name']));
        $enumType->addComment('URL: ' . ($valueSet['url'] ?? 'unknown'));
        $enumType->addComment('Version: ' . ($valueSet['version'] ?? 'unknown'));
        $enumType->addComment('Description: ' . ($valueSet['description'] ?? 'No description provided.'));

        // Record the source value set so a binding can verify it resolved to the right enum rather
        // than to one whose ClassNameResolver name merely collides. See FHIRValueSetSource.
        if (isset($valueSet['url']) && is_string($valueSet['url'])) {
            $sourceArgs = ['url' => $valueSet['url']];
            if (isset($valueSet['version']) && is_string($valueSet['version'])) {
                $sourceArgs['version'] = $valueSet['version'];
            }

            $enumType->addAttribute(FHIRValueSetSource::class, $sourceArgs);
        }

        foreach ($valueSet['compose']['include'] as $include) {
            if (isset($include['system'])) {
                if ($include['system'] === 'urn:iso:std:iso:4217') {
                    $this->addCurrencyCodes($enumType);
                } elseif ($include['system'] === 'urn:ietf:bcp:13') {
                    // There are too many mimetypes to include here, Use other valuesets to have a more manageable set
                    continue;
                } else {
                    $this->addConceptsFromCodeSystem($include, $enumType, $builderContext);
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
     * @param array<string,mixed>     $include
     * @param EnumType                $enum
     * @param BuilderContextInterface $builderContext
     *
     * @return void
     */
    /**
     * Add every concept in a CodeSystem subtree as an enum case, depth-first.
     *
     * Child concepts are full members of the value set, not annotations on their parent — `invalid`
     * has `structure`, `required`, `value` and `invariant` beneath it, and all five are valid codes.
     * Duplicate names are skipped rather than overwritten, matching the previous behaviour, so a code
     * reachable by more than one path is emitted once.
     *
     * @param array<int|string, mixed> $concepts
     */
    private function addConceptsRecursively(array $concepts, EnumType $enum): void
    {
        foreach ($concepts as $concept) {
            if (!is_array($concept) || !isset($concept['code']) || !is_string($concept['code'])) {
                continue;
            }

            $code     = $concept['code'];
            $enumName = $this->getEnumName($concept);
            if (empty($enumName)) {
                throw GenerationException::enumGenerationFailed($code, 'Could not generate valid enum name from concept data');
            }
            if (is_numeric($enumName[0])) {
                $enumName = 'CODE_' . $enumName;
            }

            // Deduplicate on BOTH name and backing value. A concept hierarchy can reach the same code
            // by more than one path, and the generated name is derived from `display`, so the same
            // code can arrive under two different names. A backed enum requires unique values, and a
            // collision is a fatal `Error: Duplicate value in enum` at class-load time — which takes
            // down validation of any resource touching that binding.
            $duplicate = array_any(
                $enum->getCases(),
                fn ($case) => $case->getName() === $enumName || $case->getValue() === $code,
            );

            if (!$duplicate) {
                $enum->addCase($enumName, $code)
                     ->addComment($concept['display'] ?? '');
            }

            if (isset($concept['concept']) && is_array($concept['concept'])) {
                $this->addConceptsRecursively($concept['concept'], $enum);
            }
        }
    }

    /**
     * @param array<string, mixed> $include A single ValueSet `compose.include` entry
     */
    private function addConceptsFromCodeSystem(array $include, EnumType $enum, BuilderContextInterface $builderContext): void
    {
        $codeSystem = $builderContext->getDefinition($include['system']);
        if ($codeSystem !== null && isset($codeSystem['concept']) && is_array($codeSystem['concept'])) {
            // Recurse: CodeSystem concepts nest via `concept.concept`, and a ValueSet that includes a
            // system without a filter includes every descendant, not just the top level. `issue-type`
            // declares 5 top-level codes with children beneath each — walking only the top level
            // generated a 5-case enum and made valid codes like `code-invalid` fail required-binding
            // validation.
            $this->addConceptsRecursively($codeSystem['concept'], $enum);
        }

        if (isset($include['concept']) && is_array($include['concept'])) {
            foreach ($include['concept'] as $concept) {
                if (!is_array($concept) || !isset($concept['code']) || !is_string($concept['code'])) {
                    continue;
                }
                $display  = $concept['display'] ?? $concept['code'];
                $enumName = u($display)->upper()->snake()->toString();

                // Skip if the name OR the backing value is already present. The two are separate
                // checks because this branch and the CodeSystem walk above name the same code
                // differently: the CodeSystem supplies a display ("Unrestricted" → `unrestricted`)
                // while an inline concept without one falls back to the code itself ("U" → `u`).
                // Both carry value 'U', and a backed enum with a duplicate value is a fatal at
                // class-load time, which took out nine R4 cases mid-validation.
                $duplicate = array_any(
                    $enum->getCases(),
                    fn ($case) => $case->getName() === $enumName || $case->getValue() === $concept['code'],
                );

                if ($duplicate) {
                    continue;
                }

                $enum->addCase($enumName, $concept['code'])
                     ->addComment($display);
                foreach ($concept['extension'] ?? [] as $extension) {
                    if (is_array($extension) && isset($extension['url']) && $extension['url'] !== 'http://hl7.org/fhir/StructureDefinition/valueset-concept-definition' && isset($extension['valueString'])) {
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

    /**
     * @param array<string,mixed> $concept
     *
     * @return string
     */
    public function getEnumName(array $concept): string
    {
        $name = $concept['display'] ?? $concept['code'];
        $name = $this->slugger->slug($name)->toString();

        return u($name)->upper()->snake()->toString();
    }
}
