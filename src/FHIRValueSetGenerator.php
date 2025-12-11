<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Ardenexal\FHIRTools\Exception\GenerationException;
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
 * @package Ardenexal\FHIRTools
 */
class FHIRValueSetGenerator
{
    /**
     * Builder context for managing generated types and namespaces
     *
     * @var BuilderContext
     */
    private BuilderContext $builderContext;

    /**
     * String slugger for normalizing concept names into valid PHP identifiers
     *
     * @var AsciiSlugger
     */
    private AsciiSlugger $slugger;

    /**
     * Construct a new FHIRValueSetGenerator with required dependencies
     *
     * @param BuilderContext $builderContext Context for managing generated types and namespaces
     */
    public function __construct(
        BuilderContext $builderContext
    ) {
        $this->builderContext = $builderContext;
        $this->slugger        = new AsciiSlugger('en', [
            'en' => [
                '=' => 'equals',
                '<' => 'less_than',
                '>' => 'greater_than',
                '&' => 'and',
            ],
        ]);
    }

    /**
     * @param array<string,mixed> $valueSet
     * @param string              $version
     *
     * @return EnumType
     */
    public function generateEnum(array $valueSet, string $version): EnumType
    {
        $className = BuilderContext::DEFAULT_CLASS_PREFIX . u($valueSet['name'])->pascal();
        $enumType  = new EnumType($className, $this->builderContext->getEnumNamespace($version));
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
     * @param array<string,mixed> $include
     * @param EnumType            $enum
     *
     * @return void
     */
    private function addConceptsFromCodeSystem(array $include, EnumType $enum): void
    {
        $codeSystem = $this->builderContext->getDefinition($include['system']);
        if ($codeSystem !== null && isset($codeSystem['concept']) && is_array($codeSystem['concept'])) {
            foreach ($codeSystem['concept'] as $concept) {
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
                // Skip if already exists
                if (array_any($enum->getCases(), fn ($case) => $case->getName() === $enumName)) {
                    continue;
                }
                $enum->addCase($enumName, $code)
                     ->addComment($concept['display'] ?? '');
            }
        }

        if (isset($include['concept']) && is_array($include['concept'])) {
            foreach ($include['concept'] as $concept) {
                if (!is_array($concept) || !isset($concept['code']) || !is_string($concept['code'])) {
                    continue;
                }
                $display  = $concept['display'] ?? $concept['code'];
                $enumName = u($display)->upper()->snake()->toString();
                // Skip if already exists
                if (array_any($enum->getCases(), fn ($case) => $case->getName() === $enumName)) {
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

    /**
     * Validate a ValueSet before processing
     *
     * @param array<string, mixed> $valueSet
     * @param ErrorCollector       $errorCollector
     *
     * @return bool
     */
    public function validateValueSet(array $valueSet, ErrorCollector $errorCollector): bool
    {
        $isValid = true;
        $url     = $valueSet['url'] ?? 'unknown';

        // Check required fields
        $requiredFields = ['resourceType', 'name', 'url'];
        foreach ($requiredFields as $field) {
            if (!isset($valueSet[$field])) {
                $errorCollector->addError(
                    "Missing required field: {$field}",
                    $url,
                    'MISSING_REQUIRED_FIELD',
                );
                $isValid = false;
            }
        }

        // Validate resource type
        if (isset($valueSet['resourceType']) && $valueSet['resourceType'] !== 'ValueSet') {
            $errorCollector->addError(
                "Invalid resource type: expected 'ValueSet', got '{$valueSet['resourceType']}'",
                $url,
                'INVALID_RESOURCE_TYPE',
            );
            $isValid = false;
        }

        // Validate compose structure
        if (isset($valueSet['compose'])) {
            if (!isset($valueSet['compose']['include']) || !is_array($valueSet['compose']['include'])) {
                $errorCollector->addWarning(
                    "ValueSet compose missing or invalid 'include' array",
                    $url,
                    ['compose_structure' => $valueSet['compose']],
                );
            }
        } else {
            $errorCollector->addWarning(
                "ValueSet missing 'compose' section",
                $url,
            );
        }

        return $isValid;
    }

    /**
     * Enhanced enum generation with error collection
     *
     * @param array<string, mixed> $valueSet
     * @param string               $version
     * @param ErrorCollector       $errorCollector
     *
     * @return EnumType|null
     */
    public function generateEnumWithErrorHandling(array $valueSet, string $version, ErrorCollector $errorCollector): ?EnumType
    {
        try {
            if (!$this->validateValueSet($valueSet, $errorCollector)) {
                return null;
            }

            return $this->generateEnum($valueSet, $version);
        } catch (GenerationException $e) {
            $errorCollector->addError(
                $e->getMessage(),
                $valueSet['url'] ?? 'unknown',
                'ENUM_GENERATION_EXCEPTION',
                'error',
                $e->getContext(),
            );

            return null;
        } catch (\Throwable $e) {
            $errorCollector->addError(
                "Unexpected error during enum generation: {$e->getMessage()}",
                $valueSet['url'] ?? 'unknown',
                'UNEXPECTED_ENUM_ERROR',
                'error',
                [
                    'exception_class' => get_class($e),
                    'file'            => $e->getFile(),
                    'line'            => $e->getLine(),
                ],
            );

            return null;
        }
    }
}
