<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

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
 * @author  FHIR Tools
 *
 * @since   1.0.0
 *
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class FHIRModelGenerator implements GeneratorInterface
{
    /**
     * Check if this generator can handle the given definition
     *
     * @param array<string, mixed> $definition The FHIR definition to check
     *
     * @return bool True if this generator can handle the definition
     */
    public function canGenerate(array $definition): bool
    {
        return isset($definition['resourceType']) && $definition['resourceType'] === 'StructureDefinition';
    }

    /**
     * Get the priority of this generator (higher numbers = higher priority)
     *
     * @return int The priority value
     */
    public function getPriority(): int
    {
        return 100; // High priority for structure definitions
    }

    /**
     * Generate PHP code from FHIR definition
     *
     * @param array<string, mixed>    $definition The FHIR definition to generate code from
     * @param string                  $version    The FHIR version
     * @param BuilderContextInterface $context    The builder context for managing generated types
     *
     * @return ClassLike The generated PHP class
     *
     * @throws GenerationException When generation fails
     */
    public function generate(array $definition, string $version, BuilderContextInterface $context): ClassLike
    {
        return $this->generateModelClass($definition, $version, $context);
    }

    /**
     * Generate model class with error handling
     *
     * @param array<string, mixed> $structureDefinition The FHIR StructureDefinition
     * @param string               $version             The FHIR version
     * @param ErrorCollector       $errorCollector      Error collector for validation errors
     *
     * @return ClassType|null The generated class or null if generation failed
     */
    public function generateModelClassWithErrorHandling(
        array $structureDefinition,
        string $version,
        ErrorCollector $errorCollector,
        ?BuilderContextInterface $builderContext = null
    ): ?ClassType {
        try {
            // Validate required fields
            if (! isset($structureDefinition['name'])) {
                $errorCollector->addError(
                    'StructureDefinition missing required field: name',
                    $structureDefinition['url'] ?? 'unknown',
                    'MISSING_REQUIRED_FIELD',
                );

                return null;
            }

            if (! isset($structureDefinition['kind'])) {
                $errorCollector->addError(
                    'StructureDefinition missing required field: kind',
                    $structureDefinition['url'] ?? 'unknown',
                    'MISSING_REQUIRED_FIELD',
                );

                return null;
            }

            // Use provided BuilderContext or create a temporary one
            if ($builderContext === null) {
                $builderContext     = new BuilderContext();
                $elementNamespace   = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource");
                $enumNamespace      = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Enum");
                $primitiveNamespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Primitive");
                $datatypeNamespace  = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\DataType");
                $builderContext->addElementNamespace($version, $elementNamespace);
                $builderContext->addEnumNamespace($version, $enumNamespace);
                $builderContext->addPrimitiveNamespace($version, $primitiveNamespace);
                $builderContext->addDatatypeNamespace($version, $datatypeNamespace);
            }

            return $this->generateModelClass($structureDefinition, $version, $builderContext);
        } catch (GenerationException $e) {
            $errorCollector->addError(
                $e->getMessage(),
                $structureDefinition['url'] ?? 'unknown',
                'GENERATION_ERROR',
                'error',
                $e->getContext(),
            );

            return null;
        } catch (\Throwable $e) {
            $errorCollector->addError(
                "Unexpected error during generation: {$e->getMessage()}",
                $structureDefinition['url'] ?? 'unknown',
                'UNEXPECTED_ERROR',
            );

            return null;
        }
    }

    /**
     * Generate a code type class for an enum
     *
     * @param EnumType                $enumType       The enum type to create a code type for
     * @param string                  $version        The FHIR version
     * @param BuilderContextInterface $builderContext The builder context
     *
     * @return ClassType The generated code type class
     */
    public function generateModelCodeType(
        EnumType $enumType,
        string $version,
        BuilderContextInterface $builderContext
    ): ClassType {
        // Enum names already have the FHIR prefix, so don't add it again
        $enumName  = $enumType->getName();
        $className = $enumName . 'Type';
        // Code type wrappers should be in DataType namespace since they extend FHIRCode (a primitive)
        $namespace = $builderContext->getDatatypeNamespace($version);
        $class     = new ClassType($className, $namespace);

        // Extend FHIRCode base type
        $codeTypeParent = $builderContext->getType('http://hl7.org/fhir/StructureDefinition/code');
        if ($codeTypeParent === null) {
            throw new \RuntimeException('FHIRCode base type not found. Ensure primitive types are generated first.');
        }
        $class->setExtends($codeTypeParent->fqcn);

        // Add documentation
        $class->addComment('@fhir-code-type ' . $enumType->getName());
        $class->addComment('@description Code type wrapper for ' . $enumType->getName() . ' enum');
        // Add constructor with enum value parameter
        // Note: We accept string|null (same as parent) for PHP compatibility, but document the expected enum
        $constructor   = $class->addMethod('__construct');
        $enumNamespace = $builderContext->getEnumNamespace($version)->getName();
        // Enum name already includes FHIR prefix
        $enumFullName = '\\' . $enumNamespace . '\\' . $enumName;

        // Accept string|null to match parent, but document the expected enum type
        $constructor->addParameter('value', null)
            ->setType('string|null')
            ->addComment('@param ' . $enumFullName . '|string|null $value The code value (enum or string)');

        // Call parent constructor with the value
        $constructor->setBody('parent::__construct(value: $value);');

        return $class;
    }

    /**
     * @param array<string, mixed>    $structureDefinition
     * @param string                  $version
     * @param BuilderContextInterface $builderContext
     *
     * @return ClassType
     */
    public function generateModelClass(array $structureDefinition, string $version, BuilderContextInterface $builderContext): ClassType
    {
        $className = ClassNameResolver::resolveClassName($structureDefinition['url'], $structureDefinition['name']);

        // Determine the correct namespace based on the structure definition kind
        $kind = $structureDefinition['kind'] ?? 'unknown';
        switch ($kind) {
            case 'resource':
                $className .= 'Resource';
                $namespace = $builderContext->getElementNamespace($version);
                break;
            case 'complex-type':
                $namespace = $builderContext->getDatatypeNamespace($version);
                break;
            case 'primitive-type':
                $className .= 'Primitive';
                $namespace = $builderContext->getPrimitiveNamespace($version);
                break;
            default:
                $namespace = $builderContext->getElementNamespace($version);
                break;
        }

        $class = new ClassType($className, $namespace);
        $builderContext->addType($structureDefinition['url'], $namespace->getName(), $class);
        if ($structureDefinition['abstract'] === true) {
            $class->setAbstract();
        }
        if (isset($structureDefinition['baseDefinition'])) {
            $parent          = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $structureDefinition['baseDefinition']);
            $parentNamespace = $this->getNamespaceForFhirType($parent, $version, $builderContext);
            $parentClass     = $builderContext->getType($structureDefinition['baseDefinition']);
            if ($parentClass === null) {
                throw new \RuntimeException(sprintf('Parent type "%s" not found for "%s". Ensure parent types are generated first.', $structureDefinition['baseDefinition'], $structureDefinition['url']));
            }
            $parentFqcn = $parentClass->fqcn;
            $class->setExtends($parentFqcn);
            // Add use statement for the parent class only if it's in a different namespace
            if ($parentNamespace !== $namespace->getName()) {
                $namespace->addUse($parentFqcn);
            }
        }

        // Add appropriate FHIR attributes based on the structure definition kind
        if ($structureDefinition['kind'] === 'resource') {
            $class->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource', [
                'type'        => $structureDefinition['name'],
                'version'     => $structureDefinition['version'] ?? '1.0.0',
                'url'         => $structureDefinition['url'],
                'fhirVersion' => $version,
            ]);
        } elseif ($structureDefinition['kind'] === 'primitive-type') {
            $class->addAttribute(FHIRPrimitive::class, [
                'primitiveType' => $structureDefinition['name'],
                'fhirVersion'   => $version,
            ]);
        } elseif ($structureDefinition['kind'] === 'complex-type') {
            // Check if this is a backbone element by looking at the base definition
            $isBackboneElement = isset($structureDefinition['baseDefinition'])
                                 && str_contains($structureDefinition['baseDefinition'], 'BackboneElement');

            if ($isBackboneElement) {
                // Extract parent resource and element path from the structure definition name
                $elementPath    = $structureDefinition['name'];
                $parentResource = explode('.', $elementPath)[0];
                $class->addAttribute(FHIRBackboneElement::class, [
                    'parentResource' => $parentResource,
                    'elementPath'    => $elementPath,
                    'fhirVersion'    => $version,
                ]);
            } else {
                $class->addAttribute(FHIRComplexType::class, [
                    'typeName'    => $structureDefinition['name'],
                    'fhirVersion' => $version,
                ]);
            }
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
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->createForElement($class, $property['_element'], $property['_properties'], $version, $builderContext);
            }
        }

        return $class;
    }

    /**
     * @param ClassType                          $classType
     * @param array<string, mixed>               $classElement
     * @param array<string,array<string, mixed>> $propertyElements
     * @param string                             $version
     * @param BuilderContextInterface            $builderContext
     *
     * @return ClassType
     */
    public function createForElement(ClassType $classType, array $classElement, array $propertyElements, string $version, BuilderContextInterface $builderContext): ClassType
    {
        $constructor      = $classType->getMethod('__construct');
        $parentParameters = [];
        $classNamespace   = $classType->getNamespace();

        if ($classNamespace === null) {
            throw GenerationException::invalidElementPath('ClassType has no namespace');
        }

        foreach ($propertyElements as $propertyElement) {
            // This is a primitive type
            if (! array_key_exists('_properties', $propertyElement) || count($propertyElement['_properties']) === 0) {
                if ($propertyElement['_element']['max'] === '0') {
                    continue;
                }
                $element = $propertyElement['_element'];

                // Track ValueSet dependencies for primitive elements with bindings
                $this->trackValueSetDependencies($element, $builderContext);

                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($propertyElement['_element'], $constructor, $version, $builderContext, $classNamespace);
            } else {
                $element = $propertyElement['_element'];

                // Track ValueSet dependencies for complex elements with bindings
                $this->trackValueSetDependencies($element, $builderContext);

                $className = u($element['path'])->pascal()->toString();

                // Determine if this is a backbone element or regular element
                $isBackboneElement = isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'BackboneElement';
                $isElement         = isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'Element';

                // Backbone elements in a Resource namespace need a sub-namespace matching
                // the parent resource, so that the namespace aligns with the subdirectory
                // structure used by getModelsComponentOutputPath (PSR-4).
                // DataType children remain flat (no subdirectory is created for them).
                $currentNamespaceName = $classNamespace->getName();
                $namespaceParts       = explode('\\', $currentNamespaceName);
                $inResourceContext    = in_array('Resource', $namespaceParts, true);

                if ($isBackboneElement && $inResourceContext) {
                    $parentResourceName = explode('.', $element['path'])[0];
                    // Avoid double-nesting for deeply nested backbone elements
                    if (! str_ends_with($currentNamespaceName, '\\' . $parentResourceName)) {
                        $namespace = new PhpNamespace($currentNamespaceName . '\\' . $parentResourceName);
                    } else {
                        $namespace = $classNamespace;
                    }
                } else {
                    $namespace = $classNamespace;
                }

                $childClass = new ClassType($className, $namespace);
                $childClass->addMethod('__construct');
                $builderContext->addType($element['path'], $namespace->getName(), $childClass);

                if ($isBackboneElement) {
                    // Add FHIRBackboneElement attribute for backbone elements
                    $elementPath    = $element['path'];
                    $parentResource = explode('.', $elementPath)[0];
                    $childClass->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement', [
                        'parentResource' => $parentResource,
                        'elementPath'    => $elementPath,
                        'fhirVersion'    => $version,
                    ]);
                    $backboneElementNamespace = $this->getNamespaceForFhirType('BackboneElement', $version, $builderContext);
                    $backboneElementFqcn      = $backboneElementNamespace . '\\BackboneElement';
                    $childClass->setExtends(name: $backboneElementFqcn);
                    // Add use statement for the parent class only if it's in a different namespace
                    if ($backboneElementNamespace !== $namespace->getName()) {
                        $namespace->addUse($backboneElementFqcn);
                    }
                } elseif ($isElement) {
                    // Add comment for regular complex elements
                    $childClass->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType', [
                        'typeName'    => $element['path'],
                        'fhirVersion' => $version,
                    ]);
                    $elementNamespace = $this->getNamespaceForFhirType('Element', $version, $builderContext);
                    $elementFqcn      = $elementNamespace . '\\Element';
                    $childClass->setExtends($elementFqcn);
                    // Add use statement for the parent class only if it's in a different namespace
                    if ($elementNamespace !== $namespace->getName()) {
                        $namespace->addUse($elementFqcn);
                    }
                }

                if (isset($element['definition'])) {
                    $childClass->addComment('@description ' . $element['definition']);
                }
                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version, $builderContext, $classNamespace);

                if (isset($propertyElement['_properties'])) {
                    // Recursively process nested elements for ValueSet dependencies
                    $this->createForElement($childClass, $element, $propertyElement['_properties'], $version, $builderContext);
                }
            }
        }

        if ($classType->getExtends() !== null && count($parentParameters) > 0) {
            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
        }

        return $classType;
    }

    /**
     * Track ValueSet dependencies for an element
     *
     * Recursively processes element bindings to track ValueSet dependencies for:
     * - Backbone elements with bindings (Requirement 6.1)
     * - Complex types with ValueSet references (Requirement 6.2)
     * - Nested elements with bindings (Requirement 6.3)
     * - Choice elements (value[x]) with bindings (Requirement 6.4)
     * - Extension definitions containing bindings (Requirement 6.5)
     *
     * @param array<string, mixed>    $element        The FHIR element to process
     * @param BuilderContextInterface $builderContext The builder context for tracking dependencies
     *
     * @return void
     */
    private function trackValueSetDependencies(array $element, BuilderContextInterface $builderContext): void
    {
        // Process direct bindings on the element
        if (isset($element['binding']['valueSet'])) {
            $bindingStrength = $element['binding']['strength'] ?? 'extensible';

            // Only track dependencies for required binding strength
            if ($this->shouldGenerateEnumForBinding($bindingStrength)) {
                $valueSetUrl     = $element['binding']['valueSet'];
                $baseValueSetUrl = $this->extractBaseValueSetUrl($valueSetUrl);

                // Try to resolve ValueSet definition
                $valueSetData = $this->resolveValueSetDefinition($baseValueSetUrl, $builderContext);

                if ($valueSetData !== null) {
                    /** @var class-string $enumClassName */
                    $enumClassName = ClassNameResolver::resolveClassName($baseValueSetUrl, $valueSetData['name']);
                    /** @var class-string $codeTypeClassName */
                    $codeTypeClassName = $enumClassName . 'Type';

                    // Add this ValueSet as a pending enum to be generated
                    $builderContext->addPendingEnum($baseValueSetUrl, $enumClassName);
                    $builderContext->addPendingType($baseValueSetUrl, $codeTypeClassName);
                }
            }
        }

        // Process bindings in element types (handles choice elements like value[x])
        if (isset($element['type'])) {
            foreach ($element['type'] as $type) {
                // Handle extensions that may contain bindings
                if (isset($type['extension'])) {
                    $this->trackExtensionBindings($type['extension'], $builderContext);
                }

                // Handle profile references that may define additional bindings
                if (isset($type['profile'])) {
                    foreach ($type['profile'] as $profileUrl) {
                        $this->trackProfileBindings($profileUrl, $builderContext);
                    }
                }
            }
        }

        // Process extension definitions that may contain bindings
        if (isset($element['extension'])) {
            $this->trackExtensionBindings($element['extension'], $builderContext);
        }
    }

    /**
     * Track ValueSet dependencies in extension definitions
     *
     * Processes extension definitions to find nested bindings that reference ValueSets.
     * This handles Requirement 6.5 for extension definitions containing bindings.
     *
     * @param array<int, array<string, mixed>> $extensions     Array of extension definitions
     * @param BuilderContextInterface          $builderContext The builder context for tracking dependencies
     *
     * @return void
     */
    private function trackExtensionBindings(array $extensions, BuilderContextInterface $builderContext): void
    {
        foreach ($extensions as $extension) {
            // Check if extension has a binding
            if (isset($extension['binding']['valueSet'])) {
                $bindingStrength = $extension['binding']['strength'] ?? 'extensible';

                if ($this->shouldGenerateEnumForBinding($bindingStrength)) {
                    $valueSetUrl     = $extension['binding']['valueSet'];
                    $baseValueSetUrl = $this->extractBaseValueSetUrl($valueSetUrl);

                    $valueSetData = $this->resolveValueSetDefinition($baseValueSetUrl, $builderContext);

                    if ($valueSetData !== null) {
                        /** @var class-string $enumClassName */
                        $enumClassName = ClassNameResolver::resolveClassName($baseValueSetUrl, $valueSetData['name']);
                        /** @var class-string $codeTypeClassName */
                        $codeTypeClassName = $enumClassName . 'Type';

                        $builderContext->addPendingEnum($baseValueSetUrl, $enumClassName);
                        $builderContext->addPendingType($baseValueSetUrl, $codeTypeClassName);
                    }
                }
            }

            // Recursively process nested extensions
            if (isset($extension['extension'])) {
                $this->trackExtensionBindings($extension['extension'], $builderContext);
            }
        }
    }

    /**
     * Track ValueSet dependencies in profile references
     *
     * Processes profile URLs to find StructureDefinitions that may contain additional bindings.
     * This helps ensure comprehensive dependency tracking for profiled elements.
     *
     * @param string                  $profileUrl     The profile URL to process
     * @param BuilderContextInterface $builderContext The builder context for tracking dependencies
     *
     * @return void
     */
    private function trackProfileBindings(string $profileUrl, BuilderContextInterface $builderContext): void
    {
        // Try to resolve the profile StructureDefinition
        $profileDefinition = $builderContext->getDefinition($profileUrl);

        if ($profileDefinition !== null && isset($profileDefinition['snapshot']['element'])) {
            // Process all elements in the profile for additional bindings
            foreach ($profileDefinition['snapshot']['element'] as $profileElement) {
                $this->trackValueSetDependencies($profileElement, $builderContext);
            }
        }
    }

    /**
     * @param array<string, mixed>    $element
     * @param Method                  $method
     * @param string                  $version
     * @param BuilderContextInterface $builderContext
     * @param PhpNamespace            $namespace
     * @param EnumType|null           $enum
     *
     * @return void
     */
    private function addElementAsProperty(array $element, Method $method, string $version, BuilderContextInterface $builderContext, PhpNamespace $namespace, ?EnumType $enum = null): void
    {
        $types = [];
        if (! isset($element['type']) && isset($element['contentReference'])) {
            $contentRef = preg_replace('/^.*#/', '', $element['contentReference']);
            if ($contentRef === null) {
                throw GenerationException::invalidElementPath($element['contentReference']);
            }
            $relatedClass = $builderContext->getType($contentRef);
            if ($relatedClass === null) {
                throw GenerationException::missingContentReference($element['contentReference'], $element['path']);
            }
            $relatedNamespace = $relatedClass->namespace;
            $types[]          = '\\' . $relatedNamespace . '\\' . $relatedClass->asClassType()->getName();
        } elseif (isset($element['type'])) {
            $types = $this->resolveClassFromType($element, $builderContext, $version, $types, $enum, $namespace);
        }

        $parameterName = $this->convertToMethodName($element['path']);

        $maxValue = $element['max'] ?? '1';
        $minValue = $element['min'] ?? 0;

        $isArray    = ! in_array($maxValue, ['1', '0'], true);
        $isNullable = $minValue === 0 && $isArray === false;

        if ($maxValue !== '0') {
            $shortDescription = $element['short'] ?? '';
            if ($isArray) {
                // Handle forward references for special types like Extension
                if (count($types) === 0 && $parameterName === 'extension') {
                    // Extension is a known FHIR type that should always be in DataType namespace
                    $dataTypeNamespace = $builderContext->getDatatypeNamespace($version)->getName();
                    $typeHint          = '\\' . $dataTypeNamespace . '\\Extension';
                } else {
                    $typeHint = count($types) > 0 ? implode('|', $types) : 'mixed';
                }
                $method->addPromotedParameter($parameterName, [])
                    ->setNullable(false)
                    ->setType('array')
                    ->addComment('@var  array<' . $typeHint . '> ' . $parameterName . ' ' . $shortDescription);
            } else {
                $parameter = $method->addPromotedParameter($parameterName, null)
                    ->setType(implode('|', $types))
                    ->addComment('@var null|' . implode('|', $types) . ' ' . $parameterName . ' ' . $shortDescription);
                if ($isNullable === false) {
                    $parameter->addAttribute(NotBlank::class);
                }
            }
        }
    }

    /**
     * Determine the correct namespace for a FHIR type
     *
     * @param string                  $code           The FHIR type code
     * @param string                  $version        The FHIR version
     * @param BuilderContextInterface $builderContext The builder context
     *
     * @return string The fully qualified namespace for the type
     */
    private function getNamespaceForFhirType(string $code, string $version, BuilderContextInterface $builderContext): string
    {
        // First, check if this type has already been generated and stored in the builder context
        // This ensures we use the actual namespace where the type was generated
        $className  = u($code)->pascal()->toString();
        $storedType = $builderContext->getType($className);
        if ($storedType !== null) {
            return $storedType->namespace;
        }

        // Special logical types that changed namespace between FHIR versions
        // In R4/R4B: These types are in Resource namespace (logical/backbone types)
        // In R5: Some moved to DataType namespace, others stayed in Resource namespace
        $typesMovedToDataTypeInR5 = [
            'Dosage',           // Moved to DataType in R5
            'Timing',           // Moved to DataType in R5
            'ElementDefinition', // Moved to DataType in R5
            'ProductShelfLife',  // Moved to DataType in R5
            'MarketingStatus',   // Moved to DataType in R5
        ];

        // Types that remain in Resource namespace across all versions
        $typesAlwaysInResource = [
            'SubstanceAmount',    // Stays in Resource even in R5
            'ProdCharacteristic', // Stays in Resource even in R5
            'Population',         // Stays in Resource even in R5
        ];

        if (in_array($code, $typesMovedToDataTypeInR5, true)) {
            // In R4 and R4B, these are logical types (Resource-like structures)
            // In R5, they moved to DataType namespace
            if (in_array($version, ['R4', 'R4B'], true)) {
                return $builderContext->getElementNamespace($version)->getName();
            }
            // In R5 and later, they are in DataType namespace
            try {
                return $builderContext->getDatatypeNamespace($version)->getName();
            } catch (GenerationException) {
                // Fallback to element namespace if datatype namespace is not available
                return $builderContext->getElementNamespace($version)->getName();
            }
        }

        if (in_array($code, $typesAlwaysInResource, true)) {
            // These types always stay in Resource namespace
            return $builderContext->getElementNamespace($version)->getName();
        }

        // List of known FHIR primitive types
        $primitiveTypes = [
            'boolean',
            'integer',
            'integer64',  // Added in R5 for very large whole numbers
            'string',
            'decimal',
            'uri',
            'url',
            'canonical',
            'base64Binary',
            'instant',
            'date',
            'dateTime',
            'time',
            'code',
            'oid',
            'id',
            'markdown',
            'unsignedInt',
            'positiveInt',
            'uuid',
            'xhtml',
        ];

        // Check if it's a primitive type
        if (in_array($code, $primitiveTypes, true)) {
            return $builderContext->getPrimitiveNamespace($version)->getName();
        }

        // Base FHIR types that belong to the DataType namespace
        // These are fundamental building blocks in FHIR that all other complex types extend from.
        // Element: Base definition for all elements in a resource
        // BackboneElement: Base for all elements defined inside a resource (not at root level)
        // Note: While these types are foundational, they are physically located in the DataType
        // directory structure, not the Resource directory, hence they must use getDatatypeNamespace()
        $dataTypes = [
            'Element',
            'BackboneElement',
        ];

        // Check if it's a base data type
        if (in_array($code, $dataTypes, true)) {
            try {
                return $builderContext->getDatatypeNamespace($version)->getName();
            } catch (GenerationException $e) {
                // This should not happen in normal operation as the DataType namespace
                // is always initialized. If it does occur, it indicates a configuration issue.
                // Log the error and fall back to element namespace to avoid generation failure.
                error_log(
                    sprintf(
                        'Warning: DataType namespace not available for version %s when resolving base type %s. ' .
                        'Falling back to Element namespace. This may cause incorrect import statements. ' .
                        'Exception: %s',
                        $version,
                        $code,
                        $e->getMessage(),
                    ),
                );

                return $builderContext->getElementNamespace($version)->getName();
            }
        }

        // List of known FHIR resource types
        $resourceTypes = [
            'Patient',
            'Observation',
            'Practitioner',
            'Organization',
            'Encounter',
            'Condition',
            'Procedure',
            'MedicationRequest',
            'DiagnosticReport',
            'AllergyIntolerance',
            'CarePlan',
            'Goal',
            'Immunization',
            'Location',
            'Device',
            'Medication',
            'Substance',
            'Specimen',
            'BodyStructure',
            'ImagingStudy',
            'Media',
            'DocumentReference',
            'Composition',
            'Bundle',
            'MessageHeader',
            'OperationOutcome',
            'Parameters',
            'Binary',
            'Basic',
            'DomainResource',
            'Resource',
        ];

        // Check if it's a resource type
        if (in_array($code, $resourceTypes, true)) {
            return $builderContext->getElementNamespace($version)->getName();
        }

        // For complex data types and backbone elements, try to use datatype namespace if available
        try {
            return $builderContext->getDatatypeNamespace($version)->getName();
        } catch (GenerationException) {
            // Fallback to element namespace if datatype namespace is not available
            return $builderContext->getElementNamespace($version)->getName();
        }
    }

    /**
     * Determine if binding strength warrants enum generation
     *
     * Only required binding strength should generate enums. All other binding
     * strengths (extensible, preferred, example) should use string types.
     * Missing binding strength is treated as extensible (no enum generation).
     *
     * @param string $bindingStrength The binding strength value
     *
     * @return bool True if enum should be generated, false otherwise
     */
    private function shouldGenerateEnumForBinding(string $bindingStrength): bool
    {
        return $bindingStrength === 'required';
    }

    /**
     * Resolve ValueSet URL to appropriate code type
     *
     * Handles ValueSet resolution with versioned URLs, fallback to string type
     * when ValueSet cannot be resolved, and proper enum/code type generation.
     *
     * @param string                  $valueSetUrl            The ValueSet URL (may include version)
     * @param BuilderContextInterface $builderContext         The builder context
     * @param string                  $version                The FHIR version
     * @param string                  $targetElementNamespace The target element namespace
     *
     * @return string The resolved code type (class name or 'string')
     */
    private function resolveValueSetCodeType(
        string $valueSetUrl,
        BuilderContextInterface $builderContext,
        string $version,
        string $targetElementNamespace
    ): string {
        // Code type wrappers are in DataType namespace since they extend FHIRCode
        $dataTypeNamespace = $builderContext->getDatatypeNamespace($version)->getName();

        // Handle versioned ValueSet URLs by extracting base URL for resolution
        // This must be done FIRST before any lookups since types are stored with base URL
        $baseValueSetUrl = $this->extractBaseValueSetUrl($valueSetUrl);

        // Check if code type already exists (first priority - most reliable)
        $codeType = $builderContext->getType($baseValueSetUrl);
        if ($codeType !== null) {
            return $codeType->fqcn;
        }

        // Check if enum already exists
        $enum = $builderContext->getEnum($baseValueSetUrl);
        if ($enum !== null) {
            // Use ClassNameResolver to get the correct name (handles duplicates like Use -> ClaimUse)
            $enumClassName = ClassNameResolver::resolveClassName($enum->fhirUrl, $enum->getClassName());

            return '\\' . $dataTypeNamespace . '\\' . $enumClassName . 'Type';
        }

        // Try to resolve ValueSet definition from BuilderContext
        $valueSetData = $this->resolveValueSetDefinition($baseValueSetUrl, $builderContext);

        if ($valueSetData !== null) {
            /** @var class-string $enumClassName */
            $enumClassName     = ClassNameResolver::resolveClassName($baseValueSetUrl, $valueSetData['name']);
            /** @var class-string $codeTypeClassName */
            $codeTypeClassName = $enumClassName . 'Type';

            // Add this ValueSet as a pending enum to be generated
            $builderContext->addPendingEnum($baseValueSetUrl, $enumClassName);
            $builderContext->addPendingType($baseValueSetUrl, $codeTypeClassName);

            return '\\' . $dataTypeNamespace . '\\' . $codeTypeClassName;
        }

        // Fallback to string type when ValueSet cannot be resolved
        return 'string';
    }

    /**
     * Extract base URL from versioned ValueSet URL
     *
     * Handles ValueSet URLs that may include version information in the format:
     * http://example.com/ValueSet/MyValueSet|1.0.0
     *
     * @param string $valueSetUrl The ValueSet URL (may include version)
     *
     * @return string The base ValueSet URL without version information
     */
    private function extractBaseValueSetUrl(string $valueSetUrl): string
    {
        // Split on pipe character to separate URL from version
        $urlParts = explode('|', $valueSetUrl);

        return $urlParts[0];
    }

    /**
     * Resolve ValueSet definition from BuilderContext
     *
     * Attempts to find the ValueSet definition using various resolution strategies:
     * 1. Direct lookup by URL
     * 2. Fallback strategies for common URL patterns
     *
     * @param string                  $valueSetUrl    The ValueSet URL to resolve
     * @param BuilderContextInterface $builderContext The builder context
     *
     * @return array<string, mixed>|null The ValueSet definition or null if not found
     */
    private function resolveValueSetDefinition(string $valueSetUrl, BuilderContextInterface $builderContext): ?array
    {
        // Try direct lookup first
        $valueSetData = $builderContext->getDefinition($valueSetUrl);
        if ($valueSetData !== null) {
            return $valueSetData;
        }

        // Try alternative URL patterns if direct lookup fails
        // Some FHIR packages may use different URL formats
        $alternativeUrls = $this->generateAlternativeValueSetUrls($valueSetUrl);

        foreach ($alternativeUrls as $alternativeUrl) {
            $valueSetData = $builderContext->getDefinition($alternativeUrl);
            if ($valueSetData !== null) {
                return $valueSetData;
            }
        }

        return null;
    }

    /**
     * Generate alternative ValueSet URLs for resolution fallback
     *
     * Creates alternative URL patterns that might be used in different FHIR packages
     * to improve ValueSet resolution success rate.
     *
     * @param string $originalUrl The original ValueSet URL
     *
     * @return array<string> Array of alternative URLs to try
     */
    private function generateAlternativeValueSetUrls(string $originalUrl): array
    {
        $alternatives = [];

        // If URL contains 'ValueSet/', try without it
        if (str_contains($originalUrl, 'ValueSet/')) {
            $alternatives[] = str_replace('ValueSet/', '', $originalUrl);
        }

        // If URL doesn't contain 'ValueSet/', try adding it
        if (! str_contains($originalUrl, 'ValueSet/')) {
            $lastSlash = strrpos($originalUrl, '/');
            if ($lastSlash !== false) {
                $alternatives[] = substr($originalUrl, 0, $lastSlash) . '/ValueSet/' . substr($originalUrl, $lastSlash + 1);
            }
        }

        return $alternatives;
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
     * Nests elements by their dot-separated path into a multi-dimensional array.
     * Each part of the path becomes a key at the corresponding array depth.
     * The element details are stored in a reserved '_element' key at the deepest level.
     * Child elements are nested under '_properties'.
     *
     * @param array<int,array<string, mixed>> $elements
     *
     * @return array{_properties: array<string, array<string, mixed>>}
     */
    private function nestElements(array $elements): array
    {
        /** @var array{_properties: array<string, array<string, mixed>>} $nestedArray */
        $nestedArray = ['_properties' => []];
        foreach ($elements as $item) {
            $pathParts = explode('.', $item['path']);
            $current   = &$nestedArray;
            foreach ($pathParts as $part) {
                if (! array_key_exists('_properties', $current)) {
                    $current['_properties'] = [];
                }
                if (! array_key_exists($part, $current['_properties'])) {
                    $current['_properties'][$part] = [];
                }
                $current = &$current['_properties'][$part];
            }
            $current['_element'] = $item;
            if (! array_key_exists('_properties', $current)) {
                $current['_properties'] = [];
            }
        }

        return $nestedArray;
    }

    /**
     * @param array<string, mixed>    $element
     * @param BuilderContextInterface $builderContext
     * @param string                  $version
     * @param array<string>           $types
     * @param EnumType|null           $enum
     * @param PhpNamespace            $namespace
     *
     * @return array<string>
     */
    public function resolveClassFromType(array $element, BuilderContextInterface $builderContext, string $version, array $types, ?EnumType $enum, PhpNamespace $namespace): array
    {
        foreach ($element['type'] as $type) {
            $code = $type['code'];

            $targetElementNamespace = $builderContext->getElementNamespace($version)->getName();
            $targetEnumNamespace    = $builderContext->getEnumNamespace($version)->getName();
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
            if ($code === 'http://hl7.org/fhirpath/System.Boolean' || $code === 'boolean') {
                $types[] = 'bool';

                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Integer' || $code === 'integer') {
                $types[] = 'int';

                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Decimal' || $code === 'decimal') {
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
                $correctNamespace = $this->getNamespaceForFhirType($code, $version, $builderContext);
                $types[]          = '\\' . $correctNamespace . '\\' . u($code)->pascal() . 'Primitive';
                $types[]          = 'string';

                continue;
            }

            if ($code === 'Element') {
                // Look up the element from the builder context to get its actual namespace
                $storedElement = $builderContext->getType($element['path']);
                if ($storedElement !== null) {
                    // Use the namespace from the stored element
                    $types[] = $storedElement->fqcn;
                }

                continue;
            }

            if ($code === 'BackboneElement') {
                // Look up the backbone element from the builder context to get its actual namespace
                $storedElement = $builderContext->getType($element['path']);
                if ($storedElement !== null) {
                    // Use the namespace from the stored backbone element
                    $types[] = $storedElement->fqcn;
                }

                continue;
            }

            if ($code === 'code' && isset($element['binding']['valueSet'])) {
                $bindingStrength = $element['binding']['strength'] ?? 'extensible';

                // Only generate enums for required binding strength
                if ($this->shouldGenerateEnumForBinding($bindingStrength)) {
                    $valueSetUrl = $element['binding']['valueSet'];
                    $codeType    = $this->resolveValueSetCodeType($valueSetUrl, $builderContext, $version, $targetElementNamespace);
                } else {
                    // For extensible, preferred, and example bindings, use string type
                    $codeType = 'string';
                }

                $types[] = $codeType;

                continue;
            }
            // Normalize type code to URL for lookup
            $typeUrl = str_starts_with($code, 'http://') || str_starts_with($code, 'https://')
                ? $code
                : 'http://hl7.org/fhir/StructureDefinition/' . $code;

            $typeFound = $builderContext->getType($typeUrl);
            if ($typeFound !== null) {
                $types[] = $typeFound->fqcn;
            } else {
                // Fallback: construct FQCN manually using namespace resolution
                // This handles cases where the type hasn't been registered yet or lookup failed
                try {
                    $correctNamespace = $this->getNamespaceForFhirType($code, $version, $builderContext);
                    $className        = u($code)->pascal()->toString();
                    $types[]          = '\\' . $correctNamespace . '\\' . $className;
                } catch (\Throwable $e) {
                    // Log the error but don't fail generation - the type may be resolved later
                    error_log(
                        sprintf(
                            'Warning: Could not resolve type "%s" for element "%s". Type will be omitted. Error: %s',
                            $code,
                            $element['path'] ?? 'unknown',
                            $e->getMessage(),
                        ),
                    );
                }
            }
        }

        return $types;
    }
}
