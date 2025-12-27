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
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class FHIRModelGenerator implements GeneratorInterface
{
    /**
     * Default prefix for all generated FHIR classes
     */
    public const DEFAULT_CLASS_PREFIX = 'FHIR';

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
            if (!isset($structureDefinition['name'])) {
                $errorCollector->addError(
                    'StructureDefinition missing required field: name',
                    $structureDefinition['url'] ?? 'unknown',
                    'MISSING_REQUIRED_FIELD',
                );

                return null;
            }

            if (!isset($structureDefinition['kind'])) {
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
                $elementNamespace   = new PhpNamespace("Ardenexal\\FHIRTools\\FHIR\\{$version}\\Element");
                $enumNamespace      = new PhpNamespace("Ardenexal\\FHIRTools\\FHIR\\{$version}\\Enum");
                $primitiveNamespace = new PhpNamespace("Ardenexal\\FHIRTools\\FHIR\\{$version}\\Primitive");
                $datatypeNamespace  = new PhpNamespace("Ardenexal\\FHIRTools\\FHIR\\{$version}\\DataType");
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
        $className = self::DEFAULT_CLASS_PREFIX . $enumType->getName() . 'Type';
        $namespace = $builderContext->getElementNamespace($version);
        $class     = new ClassType($className, $namespace);

        // Extend FHIRCode base type
        $codeNamespace = $this->getNamespaceForFhirType('code', $version, $builderContext);
        $class->setExtends($codeNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . 'Code');

        // Add documentation
        $class->addComment('@fhir-code-type ' . $enumType->getName());
        $class->addComment('@description Code type wrapper for ' . $enumType->getName() . ' enum');

        // Add constructor with enum value parameter
        $constructor   = $class->addMethod('__construct');
        $enumNamespace = $builderContext->getEnumNamespace($version)->getName();
        $constructor->addPromotedParameter('value', null)
            ->setType('\\' . $enumNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $enumType->getName() . '|string|null')
            ->addComment('@var \\' . $enumNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $enumType->getName() . '|string|null $value The code value');

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
        $className = self::DEFAULT_CLASS_PREFIX . u($structureDefinition['name'])->pascal();
        $namespace = $builderContext->getElementNamespace($version);
        $class     = new ClassType($className, $namespace);

        if ($structureDefinition['abstract'] === true) {
            $class->setAbstract();
        }
        if (isset($structureDefinition['baseDefinition'])) {
            $parent          = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $structureDefinition['baseDefinition']);
            $parentNamespace = $this->getNamespaceForFhirType($parent, $version, $builderContext);
            $class->setExtends($parentNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($parent)->pascal());
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
            $class->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive', [
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
                $class->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement', [
                    'parentResource' => $parentResource,
                    'elementPath'    => $elementPath,
                    'fhirVersion'    => $version,
                ]);
            } else {
                $class->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType', [
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
                    && !in_array($element['path'], $parentParameters, true)
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

        foreach ($propertyElements as $key => $propertyElement) {
            // This is a primitive type
            if (!array_key_exists('_properties', $propertyElement) || count($propertyElement['_properties']) === 0) {
                if ($propertyElement['_element']['max'] === '0') {
                    continue;
                }
                $element = $propertyElement['_element'];

                // Track ValueSet dependencies for primitive elements with bindings
                $this->trackValueSetDependencies($element, $builderContext);

                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($propertyElement['_element'], $constructor, $version, $builderContext);
            } else {
                $element = $propertyElement['_element'];

                // Track ValueSet dependencies for complex elements with bindings
                $this->trackValueSetDependencies($element, $builderContext);

                $className  = self::DEFAULT_CLASS_PREFIX . u($element['path'])->pascal();
                $namespace  = $builderContext->getElementNamespace($version);
                $childClass = new ClassType($className, $namespace);
                $childClass->addMethod('__construct');
                $builderContext->addType($element['path'], $childClass);

                // Determine if this is a backbone element or regular element
                $isBackboneElement = isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'BackboneElement';
                $isElement         = isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'Element';

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
                    $childClass->setExtends(name: $backboneElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . 'BackboneElement');
                } elseif ($isElement) {
                    // Add comment for regular complex elements
                    $childClass->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType', [
                        'typeName'    => $element['path'],
                        'fhirVersion' => $version,
                    ]);
                    $elementNamespace = $this->getNamespaceForFhirType('Element', $version, $builderContext);
                    $childClass->setExtends($elementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . 'Element');
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
                $this->addElementAsProperty($element, $constructor, $version, $builderContext);

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
                    $enumClassName     = self::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal();
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
                        $enumClassName     = self::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal();
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
     * @param EnumType|null           $enum
     *
     * @return void
     */
    private function addElementAsProperty(array $element, Method $method, string $version, BuilderContextInterface $builderContext, ?EnumType $enum = null): void
    {
        $types = [];
        if (!isset($element['type']) && isset($element['contentReference'])) {
            $contentRef = preg_replace('/^.*#/', '', $element['contentReference']);
            if ($contentRef === null) {
                throw GenerationException::invalidElementPath($element['contentReference']);
            }
            $relatedClass = $builderContext->getType($contentRef);
            if ($relatedClass === null) {
                throw GenerationException::missingContentReference($element['contentReference'], $element['path']);
            }
            $types[] = '\\' . $builderContext->getElementNamespace($version)->getName() . '\\' . $relatedClass->getName();
        } elseif (isset($element['type'])) {
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
                    $correctNamespace = $this->getNamespaceForFhirType($code, $version, $builderContext);
                    $types[]          = '\\' . $correctNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($code)->pascal();
                    $types[]          = 'string';
                    continue;
                }

                if ($code === 'Element') {
                    $elementClass = u($element['path'])->pascal()->toString();
                    $types[]      = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $elementClass;
                    continue;
                }

                if ($code === 'BackboneElement') {
                    $elementClass = u($element['path'])->pascal()->toString();
                    $types[]      = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $elementClass;
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

                $correctNamespace = $this->getNamespaceForFhirType($code, $version, $builderContext);
                $types[]          = '\\' . $correctNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($code)->pascal();
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
                    ->addComment('@var  array<' . implode('|', $types) . '> ' . $parameterName . ' ' . $shortDescription);
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
        // List of known FHIR primitive types
        $primitiveTypes = [
            'boolean',
            'integer',
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

        // Base types that should use the DataType namespace
        $dataTypes = [
            'Element',
            'BackboneElement',
        ];

        // Check if it's a base data type
        if (in_array($code, $dataTypes, true)) {
            try {
                return $builderContext->getDatatypeNamespace($version)->getName();
            } catch (GenerationException) {
                // Fallback to element namespace if datatype namespace is not available
                return $builderContext->getElementNamespace($version)->getName();
            }
        }

        // For complex data types, try to use datatype namespace if available
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
        // Check if enum already exists
        $enum = $builderContext->getEnum($valueSetUrl);
        if ($enum !== null) {
            return '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $enum->getName() . 'Type';
        }

        // Handle versioned ValueSet URLs by extracting base URL for resolution
        $baseValueSetUrl = $this->extractBaseValueSetUrl($valueSetUrl);

        // Try to resolve ValueSet definition from BuilderContext
        $valueSetData = $this->resolveValueSetDefinition($baseValueSetUrl, $builderContext);

        if ($valueSetData !== null) {
            $enumClassName     = self::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal();
            $codeTypeClassName = $enumClassName . 'Type';

            // Add this ValueSet as a pending enum to be generated
            $builderContext->addPendingEnum($baseValueSetUrl, $enumClassName);
            $builderContext->addPendingType($baseValueSetUrl, $codeTypeClassName);

            return '\\' . $targetElementNamespace . '\\' . $codeTypeClassName;
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
        if (!str_contains($originalUrl, 'ValueSet/')) {
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
}
