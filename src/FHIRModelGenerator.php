<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Ardenexal\FHIRTools\Attributes\FhirResource;
use Ardenexal\FHIRTools\Exception\GenerationException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\String\u;

/**
 * Generates PHP model classes from FHIR StructureDefinitions
 *
 * This class is responsible for converting FHIR StructureDefinitions into
 * PHP classes with proper type hints, validation constraints, and documentation.
 * It handles:
 *
 * - Class generation from StructureDefinitions
 * - Property creation with appropriate PHP types
 * - Constructor generation with promoted properties
 * - Inheritance relationships between FHIR types
 * - Validation constraint application
 * - Nested element processing and class creation
 * - Content reference resolution
 * - Enhanced error handling and validation
 *
 * The generator produces PSR-12 compliant code with comprehensive PHPDoc
 * annotations and Symfony validation constraints.
 *
 * @phpstan-type GenerationContext array{targetNamespace: PhpNamespace, classPrefix: string}
 * @phpstan-type ElementProperties array{
 *      path: string,
 *      id?: string,
 *      base?: array{path: string},
 *      short?: string,
 *      description?: string,
 *      comment?: string,
 *      definition?: string,
 *      min?: int,
 *      max?: string,
 *      contentReference?: string,
 *      type?: array<int,array{extension?: array<int,array{url: string, valueUrl: string}>, code: string}>,
 *      binding?: array{strength: string, valueSet: string}
 *  }
 * @phpstan-type NestedElementArray array<string, mixed>
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools
 */
class FHIRModelGenerator
{
    /**
     * Builder context for managing generated types and namespaces
     *
     * @var BuilderContext
     */
    private BuilderContext $builderContext;

    /**
     * Construct a new FHIRModelGenerator with required dependencies
     *
     * @param BuilderContext $builderContext Context for managing generated types and namespaces
     */
    public function __construct(
        BuilderContext $builderContext
    ) {
        $this->builderContext = $builderContext;
    }

    /**
     * @param mixed[] $structureDefinition
     * @param string  $version
     *
     * @return ClassType
     */
    public function generateModelClass(array $structureDefinition, string $version): ClassType
    {
        $className = BuilderContext::DEFAULT_CLASS_PREFIX . u($structureDefinition['name'])->pascal();
        $namespace = $this->builderContext->getElementNamespace($version);
        $class     = new ClassType($className, $namespace);

        if ($structureDefinition['abstract'] === true) {
            $class->setAbstract();
        }
        if (isset($structureDefinition['baseDefinition'])) {
            $parent = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $structureDefinition['baseDefinition']);
            $class->setExtends($namespace->getName() . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($parent)->pascal());
        }

        if ($structureDefinition['kind'] === 'resource') {
            $class->addAttribute(FhirResource::class, [
                'type'        => $structureDefinition['name'],
                'version'     => $structureDefinition['version'],
                'url'         => $structureDefinition['url'],
                'fhirVersion' => $version,
            ]);
        }

        if (isset($structureDefinition['publisher'])) {
            $class->addComment('@author ' . $structureDefinition['publisher']);
        }
        $class->addComment('@see ' . $structureDefinition['url']);
        if (isset($structureDefinition['snapshot']['element'][0]['definition']) === true) {
            $class->addComment('@description ' . $structureDefinition['snapshot']['element'][0]['definition']);
        }
        $class->addMethod('__construct');
        $parentParameters = [];

        if (isset($structureDefinition['snapshot']) === true) {
            $elements = $this->nestElements($structureDefinition['snapshot']['element']);

            foreach ($elements['_properties'] as $property) {
                $element = $property['_element'];
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->createForElement($class, $property['_element'], $property['_properties'], $version);
            }
        }

        return $class;
    }

    /**
     * @param ClassType                        $classType
     * @param ElementProperties                $classElement
     * @param array<string,NestedElementArray> $propertyElements
     * @param string                           $version
     *
     * @return ClassType
     */
    public function createForElement(ClassType $classType, array $classElement, array $propertyElements, string $version): ClassType
    {
        $constructor      = $classType->getMethod('__construct');
        $parentParameters = [];

        foreach ($propertyElements as $key => $propertyElement) {
            // This is a primitive type
            if (!array_key_exists('_properties', $propertyElement) || count($propertyElement['_properties']) === 0) {
                if ($propertyElement['_element']['max'] === '0') {
                    continue;
                }
                $element = $propertyElement['_element'];
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($propertyElement['_element'], $constructor, $version);
            } else {
                $element    = $propertyElement['_element'];
                $className  = BuilderContext::DEFAULT_CLASS_PREFIX . u($element['path'])->pascal();
                $namespace  = $this->builderContext->getElementNamespace($version);
                $childClass = new ClassType($className, $namespace);
                $childClass->addMethod('__construct');
                $this->builderContext->addType($element['path'], $childClass);

                if (isset($element['base']) && isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'Element') {
                    $childClass->setExtends($namespace->getName() . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . 'Element');
                }

                if (isset($element['definition'])) {
                    $childClass->addComment('@description ' . $element['definition']);
                }
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version);

                if (isset($propertyElement['_properties'])) {
                    $this->createForElement($childClass, $element, $propertyElement['_properties'], $version);
                }
            }
        }

        if ($classType->getExtends() !== null) {
            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
        }

        return $classType;
    }

    /**
     * @param ElementProperties $element
     * @param Method            $method
     * @param string            $version
     * @param EnumType|null     $enum
     *
     * @return void
     */
    private function addElementAsProperty(array $element, Method $method, string $version, ?EnumType $enum = null): void
    {
        $types = [];
        if (!isset($element['type']) && isset($element['contentReference'])) {
            $contentRef = preg_replace('/^.*#/', '', $element['contentReference']);
            if ($contentRef === null) {
                throw GenerationException::invalidElementPath($element['contentReference']);
            }
            $relatedClass = $this->builderContext->getType($contentRef);
            if ($relatedClass === null) {
                throw GenerationException::missingContentReference($element['contentReference'], $element['path']);
            }
            $types[] = '\\' . $this->builderContext->getElementNamespace($version)->getName() . '\\' . $relatedClass->getName();
        } elseif (isset($element['type'])) {
            foreach ($element['type'] as $type) {
                $code = $type['code'];

                $targetElementNamespace = $this->builderContext->getElementNamespace($version)->getName();
                $targetEnumNamespace    = $this->builderContext->getEnumNamespace($version)->getName();
                if ($code === 'http://hl7.org/fhirpath/System.String') {
                    if (isset($element['base']['path']) && $element['base']['path'] === 'integer.value') {
                        $types[] = 'int';

                        continue;
                    }
                    $fhirTypeExtension = array_find($type['extension'] ?? [], fn ($ext) => $ext['url'] === 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type');
                    if ($enum !== null && $fhirTypeExtension !== null && $fhirTypeExtension['valueUrl'] === 'code') {
                        $types[] = '\\' . $targetEnumNamespace . '\\' . $enum->getName();

                        continue;
                    }

                    $types[] = 'string';

                    continue;
                }
                if ($code === 'http://hl7.org/fhirpath/System.Boolean') {
                    $types[] = 'bool';

                    continue;
                }
                if ($code === 'http://hl7.org/fhirpath/System.Integer') {
                    $types[] = 'int';

                    continue;
                }
                if ($code === 'http://hl7.org/fhirpath/System.Decimal') {
                    $types[] = 'float';

                    continue;
                }
                if ($code === 'http://hl7.org/fhirpath/System.DateTime') {
                    $types[] = '\\' . \DateTimeInterface::class;

                    continue;
                }
                if ($code === 'http://hl7.org/fhirpath/System.Date') {
                    $types[] = 'string';

                    continue;
                }
                if ($code === 'http://hl7.org/fhirpath/System.Time') {
                    $types[] = 'string';

                    continue;
                }

                if ($code === 'string') {
                    $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($code)->pascal();
                    $types[] = 'string';

                    continue;
                }

                if ($code === 'Element') {
                    $elementClass = u($element['path'])->pascal()->toString();

                    $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $elementClass;

                    continue;
                }

                if ($code === 'BackboneElement') {
                    $elementClass = u($element['path'])->pascal()->toString();

                    $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $elementClass;

                    continue;
                }


                if ($code === 'code' && isset($element['binding']['strength']) && $element['binding']['strength'] === 'required') {
                    $enum = $this->builderContext->getEnum($element['binding']['valueSet']);
                    if ($enum !== null) {
                        $codeType = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $enum->getName() . 'Type';
                    } else {
                        $valueSet = $element['binding']['valueSet'];
                        // TODO handle versioned value sets better
                        $valueSetData = $this->builderContext->getDefinition(explode('|', $valueSet)[0]);
                        /** @var class-string $codeType */
                        $codeType = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal() . 'Type';
                        $enumType = '\\' . $targetEnumNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal();
                        $this->builderContext->addPendingType($valueSet, $codeType);
                        /** @var class-string $enumType */
                        $this->builderContext->addPendingEnum($valueSetData['url'], $enumType);
                    }

                    $types[] = $codeType;

                    continue;
                }

                $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($code)->pascal();
            }
        }


        $parameterName = $this->convertToMethodName($element['path']);

        $maxValue = $element['max'] ?? '1';
        $minValue = $element['min'] ?? 0;

        $isArray    = !in_array($maxValue, ['1', '0'], true);
        $isNullable = $minValue === 0 && $isArray === false;

        if ($maxValue !== '0') {
            $shortDescription = $element['short'] ?? '';
            if ($isArray) {
                $method->addPromotedParameter($parameterName, [])
                       ->setNullable(false)
                       ->setType('array')
                       ->addComment('@var  array<' . implode('|', $types) . '> $' . $parameterName . ' ' . $shortDescription);
            } else {
                $parameter = $method->addPromotedParameter($parameterName, null)
                                    ->setType(implode('|', $types))
                                    ->addComment('@var null|' . implode('|', $types) . ' $' . $parameterName . ' ' . $shortDescription);
                if ($isNullable === false) {
                    $parameter->addAttribute(NotBlank::class);
                }
            }
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function convertToMethodName(string $path): string
    {
        $pathParts = u($path)->split('.');
        $lastPart  = array_last($pathParts);
        if ($lastPart === null) {
            throw GenerationException::invalidElementPath($path);
        }

        return lcfirst($lastPart->camel()->toString());
    }

    /**
     * @param EnumType $enumType
     * @param string   $version
     *
     * @return ClassType
     */
    public function generateModelCodeType(EnumType $enumType, string $version): ClassType
    {
        $elementNamespace    = $this->builderContext->getElementNamespace($version);
        $structureDefinition = $this->builderContext->getDefinition('http://hl7.org/fhir/StructureDefinition/code');
        $className           = $enumType->getName() . 'Type';
        $class               = new ClassType($className, $elementNamespace);

        $class->addComment('@author ' . $structureDefinition['publisher']);
        $class->addComment('@see ' . $structureDefinition['url']);
        $class->addComment('@description ' . $structureDefinition['snapshot']['element'][0]['definition']);

        $constructor      = $class->addMethod('__construct');
        $parentParameters = [];

        foreach ($structureDefinition['snapshot']['element'] as $element) {
            $nameParts = array_slice(explode('.', $element['path']), 1);
            // First level elements are properties of the class
            if (count($nameParts) === 1) {
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version, $enumType);
            }
        }


        return $class;
    }

    /**
     * Nests elements by their dot-separated path into a multi-dimensional array.
     * Each part of the path becomes a key at the corresponding array depth.
     * The element details are stored in a reserved '_element' key at the deepest level.
     * Child elements are nested under '_properties'.
     *
     * @param array<int,ElementProperties> $elements
     *
     * @return array{_properties: array<string, NestedElementArray>}
     */
    private function nestElements(array $elements): array
    {
        /** @var array{_properties: array<string, NestedElementArray>} $nestedArray */
        $nestedArray = ['_properties' => []];
        foreach ($elements as $item) {
            $pathParts = explode('.', $item['path']);
            $current   = &$nestedArray;
            foreach ($pathParts as $part) {
                if (!array_key_exists('_properties', $current)) {
                    $current['_properties'] = [];
                }
                if (!array_key_exists($part, $current['_properties'])) {
                    $current['_properties'][$part] = [];
                }
                $current = &$current['_properties'][$part];
            }
            $current['_element'] = $item;
            if (!array_key_exists('_properties', $current)) {
                $current['_properties'] = [];
            }
        }

        return $nestedArray;
    }

    /**
     * Validate a structure definition before processing
     *
     * @param array<string, mixed> $structureDefinition
     * @param ErrorCollector       $errorCollector
     *
     * @return bool
     */
    public function validateStructureDefinition(array $structureDefinition, ErrorCollector $errorCollector): bool
    {
        $isValid = true;
        $url     = $structureDefinition['url'] ?? 'unknown';

        // Check required fields
        $requiredFields = ['resourceType', 'name', 'kind', 'url'];
        foreach ($requiredFields as $field) {
            if (!isset($structureDefinition[$field])) {
                $errorCollector->addError(
                    "Missing required field: {$field}",
                    $url,
                    'MISSING_REQUIRED_FIELD',
                );
                $isValid = false;
            }
        }

        // Validate resource type
        if (isset($structureDefinition['resourceType']) && $structureDefinition['resourceType'] !== 'StructureDefinition') {
            $errorCollector->addError(
                "Invalid resource type: expected 'StructureDefinition', got '{$structureDefinition['resourceType']}'",
                $url,
                'INVALID_RESOURCE_TYPE',
            );
            $isValid = false;
        }

        // Validate kind
        if (isset($structureDefinition['kind'])) {
            $validKinds = ['primitive-type', 'complex-type', 'resource', 'logical'];
            if (!in_array($structureDefinition['kind'], $validKinds, true)) {
                $errorCollector->addWarning(
                    "Unusual kind value: '{$structureDefinition['kind']}'",
                    $url,
                    ['valid_kinds' => $validKinds],
                );
            }
        }

        // Validate snapshot elements if present
        if (isset($structureDefinition['snapshot']['element']) && is_array($structureDefinition['snapshot']['element'])) {
            foreach ($structureDefinition['snapshot']['element'] as $index => $element) {
                if (!isset($element['path'])) {
                    $errorCollector->addError(
                        "Element at index {$index} missing required 'path' field",
                        $url,
                        'MISSING_ELEMENT_PATH',
                        'error',
                        ['element_index' => $index],
                    );
                    $isValid = false;
                }
            }
        }

        return $isValid;
    }

    /**
     * Enhanced element processing with error collection
     *
     * @param array<string, mixed> $structureDefinition
     * @param string               $version
     * @param ErrorCollector       $errorCollector
     *
     * @return ClassType|null
     */
    public function generateModelClassWithErrorHandling(array $structureDefinition, string $version, ErrorCollector $errorCollector): ?ClassType
    {
        try {
            if (!$this->validateStructureDefinition($structureDefinition, $errorCollector)) {
                return null;
            }

            return $this->generateModelClass($structureDefinition, $version);
        } catch (GenerationException $e) {
            $errorCollector->addError(
                $e->getMessage(),
                $structureDefinition['url'] ?? 'unknown',
                'GENERATION_EXCEPTION',
                'error',
                $e->getContext(),
            );

            return null;
        } catch (\Throwable $e) {
            $errorCollector->addError(
                "Unexpected error during class generation: {$e->getMessage()}",
                $structureDefinition['url'] ?? 'unknown',
                'UNEXPECTED_ERROR',
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
