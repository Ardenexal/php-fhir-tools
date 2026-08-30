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
use Nette\PhpGenerator\PromotedParameter;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\ObligationExtensionParser;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligationConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRQuantityRange;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTemporalRange;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Metadata\ObligationCode;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;
use Ardenexal\FHIRTools\Component\CodeGeneration\Support\CanonicalUrl;
use Ardenexal\FHIRTools\Component\CodeGeneration\Support\StringCase;

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
                // Abstract resource bases (Resource, DomainResource, and R5's CanonicalResource /
                // MetadataResource) are named with an `Abstract` prefix instead of the concrete
                // `Resource` suffix. This avoids the doubled-word "ResourceResource" while keeping
                // the suffix convention for concrete resources (e.g. Patient -> PatientResource).
                if (($structureDefinition['abstract'] ?? false) === true) {
                    $className = 'Abstract' . $className;
                } else {
                    $className .= 'Resource';
                }
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
            $class->addAttribute(FhirResource::class, [
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
            // All generated primitive classes implement Stringable so callers can use
            // (string) $primitive instead of accessing ->value directly.
            $class->addImplement(\Stringable::class);
            $toString = $class->addMethod('__toString');
            $toString->setReturnType('string');
            if ($structureDefinition['name'] === 'boolean') {
                $toString->setBody("return \$this->value === null ? '' : (\$this->value ? 'true' : 'false');");
            } else {
                $toString->setBody("return \$this->value === null ? '' : (string) \$this->value;");
            }
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

        // Emit FHIRPathInvariant attributes from root element constraints.
        // Invariants with only an XPath expression (no `expression` field) are skipped.
        // Inherited invariants (source ≠ current SD URL) are skipped to avoid double-firing
        // because the parent PHP class already carries them.
        $sdUrl = $structureDefinition['url'];
        foreach ($structureDefinition['snapshot']['element'][0]['constraint'] ?? [] as $constraint) {
            $expression = $constraint['expression'] ?? '';
            if ($expression === '') {
                continue;
            }
            $constraintSource = $constraint['source'] ?? null;
            if ($constraintSource !== null && $constraintSource !== $sdUrl) {
                continue;
            }
            $class->addAttribute(FHIRPathInvariant::class, self::invariantAttributeArgs($constraint, $expression));
        }

        // Inject FHIRExtensionsTrait into Element and DomainResource base classes so that all
        // data types, complex types, backbone elements, and resources inherit extension helpers.
        if (in_array($structureDefinition['name'], ['Element', 'DomainResource'], true)) {
            $namespace->addUse(FHIRExtensionsTrait::class);
            $class->addTrait(FHIRExtensionsTrait::class);
        }

        // Extension must implement FHIRExtensionInterface so that findExtensionByUrl() can
        // type-safely match any Extension object regardless of the FHIR version.
        if ($structureDefinition['name'] === 'Extension') {
            $namespace->addUse(FHIRExtensionInterface::class);
            $class->addImplement(FHIRExtensionInterface::class);
            $getUrl = $class->addMethod('getExtensionUrl');
            $getUrl->setReturnType('?string');
            $getUrl->setBody('return $this->url;');
        }

        $class->addMethod('__construct');
        $parentParameters = [];

        // Build a set of param names the PHP parent constructor actually accepts.
        // This prevents passing CanonicalResource-inherited params to AbstractDomainResource
        // when a FHIR type (e.g. MetadataResource) lists both as ancestors via base.path.
        $validParentParamNames = [];
        if (isset($parentClass) && $parentClass->class instanceof ClassType) {
            try {
                $validParentParamNames = array_keys($parentClass->class->getMethod('__construct')->getParameters());
            } catch (\Throwable) {
                // Parent has no __construct — pass all params as before
            }
        }

        if (isset($structureDefinition['snapshot']) === true) {
            $elements = $this->nestElements($structureDefinition['snapshot']['element']);

            foreach ($elements['_properties'] as $property) {
                $element      = $property['_element'];
                $derivedParam = $this->convertToMethodName($element['base']['path']);
                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                    && ($validParentParamNames === [] || in_array($derivedParam, $validParentParamNames, true))
                ) {
                    $parentParameters[] = $derivedParam;
                }
                $this->createForElement($class, $property['_properties'], $version, $builderContext, $sdUrl, $validParentParamNames);
            }
        }

        return $class;
    }

    /**
     * @param ClassType                          $classType
     * @param array<string,array<string, mixed>> $propertyElements
     * @param string                             $version
     * @param BuilderContextInterface            $builderContext
     * @param list<string>                       $validParentParamNames when non-empty, only elements whose derived parameter
     *                                                                  name appears in this list are added to the parent::__construct() call
     *
     * @return ClassType
     */
    public function createForElement(ClassType $classType, array $propertyElements, string $version, BuilderContextInterface $builderContext, ?string $sdUrl = null, array $validParentParamNames = []): ClassType
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

                $derivedParam = $this->convertToMethodName($element['base']['path']);
                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                    && ($validParentParamNames === [] || in_array($derivedParam, $validParentParamNames, true))
                ) {
                    $parentParameters[] = $derivedParam;
                }
                $this->addElementAsProperty($propertyElement['_element'], $constructor, $version, $builderContext);
            } else {
                $element = $propertyElement['_element'];

                // Track ValueSet dependencies for complex elements with bindings
                $this->trackValueSetDependencies($element, $builderContext);

                $className = StringCase::pascal($element['path']);

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
                    $childClass->addAttribute(FHIRBackboneElement::class, [
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
                    $childClass->addAttribute(FHIRComplexType::class, [
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
                $derivedParam = $this->convertToMethodName($element['base']['path']);
                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                    && ($validParentParamNames === [] || in_array($derivedParam, $validParentParamNames, true))
                ) {
                    $parentParameters[] = $derivedParam;
                }
                $this->addElementAsProperty($element, $constructor, $version, $builderContext);

                // Emit FHIRPathInvariant attributes on child classes from their element constraints.
                foreach ($element['constraint'] ?? [] as $constraint) {
                    $expression = $constraint['expression'] ?? '';
                    if ($expression === '') {
                        continue;
                    }
                    $constraintSource = $constraint['source'] ?? null;
                    if ($constraintSource !== null && $constraintSource !== $sdUrl) {
                        continue;
                    }
                    $childClass->addAttribute(FHIRPathInvariant::class, self::invariantAttributeArgs($constraint, $expression));
                }

                if (isset($propertyElement['_properties'])) {
                    // Recursively process nested elements for ValueSet dependencies
                    $this->createForElement($childClass, $propertyElement['_properties'], $version, $builderContext, $sdUrl);
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
    /**
     * The generated enum class name backing a value set, or null when there is not one.
     *
     * Bindings carry a version-suffixed URL (`.../ValueSet/item-type|4.0.1`) while the context is
     * keyed by the bare URL, so the suffix is stripped before lookup.
     *
     * Only materialised enums count, and deliberately so. The pending register (`getPendingEnums()`)
     * is keyed by URL but holds a name derived through `ClassNameResolver`, and two different value
     * sets can resolve to the *same* name: `.../ValueSet/medication-statement-status` and
     * `.../ValueSet/medication-status` both yield `MedicationStatusCodes`, whose generated enum holds
     * only the latter's three codes. Trusting the pending name bound `MedicationStatement.status` to
     * the wrong enum and rejected the legal code `unknown` — a false positive that `ABOVE` could not
     * catch, because both affected cases were already `BELOW`.
     *
     * Resolution goes through the ValueSet *definition*, not the generated-enum register, because
     * `getEnum()` is still empty while models are being written — value sets are registered in the
     * command's own later loop. Definitions are loaded up front, so this is order-independent.
     *
     * The name this produces is **not** trusted on its own. `ClassNameResolver` can map two value sets
     * to one name (`medication-statement-status` and `medication-status` both give
     * `MedicationStatusCodes`), and `resolveValueSetDefinition()` may itself fall back to an
     * alternative URL. Both are caught at validation time by comparing the enum's own
     * `#[FHIRValueSetSource]` URL against the binding's, so a mismatch degrades to the existing
     * "no enum class generated" warning rather than rejecting legal codes.
     */
    private function resolveBoundEnumName(
        string $valueSetUrl,
        string $version,
        BuilderContextInterface $builderContext,
    ): ?string {
        $bareUrl    = $this->extractBaseValueSetUrl($valueSetUrl);
        $definition = $this->resolveValueSetDefinition($bareUrl, $builderContext);

        if ($definition === null || !isset($definition['name']) || !is_string($definition['name'])) {
            return null;
        }

        $className = ClassNameResolver::resolveClassName($bareUrl, $definition['name']);

        // Fully qualified, not a bare name. The validator is wired with every version's enum
        // namespace at once (services.yaml lists R4, R4B and R5), and probing them in order returns
        // whichever matches first — always R4. An R5 binding therefore resolved to R4's enum, where
        // the code sets genuinely differ: `coding` is legal in R5 and absent from R4 (so it was
        // rejected), while `choice` was removed in R5 and present in R4 (so it was accepted). The
        // corpus harness cannot see this, because its factory wires a single namespace per version.
        return $builderContext->getEnumNamespace($version)->getName() . '\\' . $className;
    }

    /**
     * @param array<string, mixed> $element
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
        // Definitions are indexed under the bare canonical URL. A versioned `type.profile`
        // reference misses silently, dropping every binding the profile declares.
        $profileUrl = CanonicalUrl::stripVersion($profileUrl);

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
     */
    private function addElementAsProperty(array $element, Method $method, string $version, BuilderContextInterface $builderContext, ?EnumType $enum = null): void
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
            $types = $this->resolveClassFromType($element, $builderContext, $version, $types, $enum);
        }

        $parameterName = $this->convertToMethodName($element['path']);

        $maxValue = $element['max'] ?? '1';
        $minValue = $element['min'] ?? 0;

        $isArray    = ! in_array($maxValue, ['1', '0'], true);
        $isNullable = $minValue === 0 && $isArray === false;

        // Build FHIR property metadata for #[FhirProperty] attribute
        $isChoice     = str_contains($element['path'], '[x]');
        $fhirType     = $isChoice ? 'choice' : ($element['type'][0]['code'] ?? 'unknown');
        $propertyKind = $this->resolvePropertyKind($parameterName, $element);
        $isRequired   = (int) ($element['min'] ?? 0) >= 1;
        $variants     = $isChoice ? $this->buildChoiceVariants($element, $version, $builderContext) : null;

        // Detect xmlAttr representation (element serialized as XML attribute on parent element)
        $xmlSerializedName = null;
        $representations   = $element['representation'] ?? [];
        if (in_array('xmlAttr', $representations, true)) {
            $xmlSerializedName = '@' . $parameterName;
        }

        // Attribute args: variants strip isBuiltin (not part of FhirProperty::$variants schema)
        $attributeArgs = ['fhirType' => $fhirType, 'propertyKind' => $propertyKind];
        if ($isArray) {
            $attributeArgs['isArray'] = true;
        }
        if ($isRequired) {
            $attributeArgs['isRequired'] = true;
        }
        if ($isChoice) {
            $attributeArgs['isChoice'] = true;
            $attributeArgs['variants'] = array_map(
                static fn (array $v): array => array_diff_key($v, ['isBuiltin' => true]),
                $variants ?? [],
            );
        }
        if ($xmlSerializedName !== null) {
            $attributeArgs['xmlSerializedName'] = $xmlSerializedName;
        }

        // Emit phpType in the attribute for complex/backbone/primitive array properties (non-choice)
        // so the serializer can denormalize array items into typed objects via attribute reflection.
        if ($isArray && ! $isChoice && count($types) > 0
                     && in_array($propertyKind, ['complex', 'backbone', 'primitive'], true)
        ) {
            $attributeArgs['phpType'] = ltrim($types[0], '\\');
        }

        if ($maxValue !== '0') {
            $shortDescription = $element['short'] ?? '';
            // For decimal-typed properties, PHPStan @var uses 'numeric-string' instead of 'string'
            // to express that the string is always a valid decimal number.
            $isDecimalElement = in_array($fhirType, ['http://hl7.org/fhirpath/System.Decimal', 'decimal'], true);
            $docblockTypes    = $isDecimalElement
                ? array_map(static fn (string $t): string => $t === 'string' ? 'numeric-string' : $t, $types)
                : $types;
            if ($isArray) {
                // Handle forward references for special types like Extension
                if (count($types) === 0 && $parameterName === 'extension') {
                    // Extension is a known FHIR type that should always be in DataType namespace
                    $dataTypeNamespace = $builderContext->getDatatypeNamespace($version)->getName();
                    $typeHint          = '\\' . $dataTypeNamespace . '\\Extension';
                } else {
                    $typeHint = count($docblockTypes) > 0 ? implode('|', array_unique($docblockTypes)) : 'mixed';
                }
                $param = $method->addPromotedParameter($parameterName, [])
                    ->setNullable(false)
                    ->setType('array')
                    ->addComment('@var  array<' . $typeHint . '> ' . $parameterName . ' ' . $shortDescription);
                $param->addAttribute(FhirProperty::class, $attributeArgs);
                $this->addCascadeIfNested($param, $propertyKind);
            } else {
                $param = $method->addPromotedParameter($parameterName, null)
                    ->setType(implode('|', $types))
                    ->addComment('@var null|' . implode('|', array_unique($docblockTypes)) . ' ' . $parameterName . ' ' . $shortDescription);
                $param->addAttribute(FhirProperty::class, $attributeArgs);
                $this->addCascadeIfNested($param, $propertyKind);
                if ($isNullable === false) {
                    // NotBlank treats `false`, `0` and `''` as blank, but for FHIR "required" means
                    // present, not truthy. A required boolean legitimately carries false —
                    // `Questionnaire.item.enableWhen.answerBoolean: false` is a valid answer — and
                    // NotBlank rejected every one of them. NotNull is the correct constraint wherever
                    // the property can hold a falsy scalar.
                    $param->addAttribute(self::requiresNotNullRatherThanNotBlank($types) ? NotNull::class : NotBlank::class);
                }
            }

            // contentReference elements have their value constraints on the referenced type —
            // skip all value constraint emission here.
            if (ElementDefinitionHelper::hasContentReference($element)) {
                return;
            }

            // Count constraints for array properties with bounded cardinality.
            if ($isArray) {
                $countMin  = (int) ($element['min'] ?? 0);
                $countMax  = $element['max'] ?? '*';
                $countArgs = [];
                if ($countMin > 0) {
                    $countArgs['min'] = $countMin;
                }
                if (is_numeric($countMax)) {
                    $countArgs['max'] = (int) $countMax;
                }
                if ($countArgs !== []) {
                    $param->addAttribute(Count::class, $countArgs);
                }
            }

            // Length constraint from maxLength field.
            if (isset($element['maxLength'])) {
                $param->addAttribute(Length::class, ['max' => (int) $element['maxLength']]);
            }

            // Range constraint from minValue[x] / maxValue[x] polymorphic fields.
            // Temporal types (date, dateTime, instant, time) use FHIRTemporalRange;
            // Quantity types use FHIRQuantityRange;
            // numeric types (decimal, integer, etc.) use Symfony's built-in Range.
            $rangeMin  = ElementDefinitionHelper::extractPolymorphicField($element, 'minValue');
            $rangeMax  = ElementDefinitionHelper::extractPolymorphicField($element, 'maxValue');
            if ($rangeMin !== null || $rangeMax !== null) {
                $temporalSuffixes = ['Date', 'DateTime', 'Instant', 'Time'];
                $minSuffix        = $rangeMin !== null ? $rangeMin['type'] : null;
                $maxSuffix        = $rangeMax !== null ? $rangeMax['type'] : null;
                $suffix           = $minSuffix ?? $maxSuffix;

                if ($suffix !== null && in_array($suffix, $temporalSuffixes, true)) {
                    $temporalTypeMap = [
                        'Date'     => 'date',
                        'DateTime' => 'dateTime',
                        'Instant'  => 'instant',
                        'Time'     => 'time',
                    ];
                    $param->addAttribute(FHIRTemporalRange::class, [
                        'minValue'     => $rangeMin !== null ? (string) $rangeMin['value'] : null,
                        'maxValue'     => $rangeMax !== null ? (string) $rangeMax['value'] : null,
                        'temporalType' => $temporalTypeMap[$suffix],
                    ]);
                } elseif ($suffix === 'Quantity') {
                    $minBound = null;
                    if ($rangeMin !== null && is_array($rangeMin['value'])) {
                        $minBound = [
                            'value'  => (float) ($rangeMin['value']['value'] ?? 0.0),
                            'system' => $rangeMin['value']['system'] ?? null,
                            'code'   => $rangeMin['value']['code']   ?? null,
                        ];
                    }
                    $maxBound = null;
                    if ($rangeMax !== null && is_array($rangeMax['value'])) {
                        $maxBound = [
                            'value'  => (float) ($rangeMax['value']['value'] ?? 0.0),
                            'system' => $rangeMax['value']['system'] ?? null,
                            'code'   => $rangeMax['value']['code']   ?? null,
                        ];
                    }
                    if ($minBound !== null || $maxBound !== null) {
                        $param->addAttribute(FHIRQuantityRange::class, [
                            'minValue' => $minBound,
                            'maxValue' => $maxBound,
                        ]);
                    }
                } else {
                    $rangeArgs = [];
                    if ($rangeMin !== null) {
                        $rangeArgs['min'] = (string) $rangeMin['value'];
                    }
                    if ($rangeMax !== null) {
                        $rangeArgs['max'] = (string) $rangeMax['value'];
                    }
                    $param->addAttribute(Range::class, $rangeArgs);
                }
            }

            // Regex pattern from primitive type extension.
            $regexPattern = $this->extractPrimitiveRegexPattern($element);
            if ($regexPattern !== null) {
                $param->addAttribute(Regex::class, ['pattern' => self::toDelimitedAnchoredPattern($regexPattern)]);
            } elseif (($element['path'] ?? null) === 'Resource.id') {
                // `Resource.id` is the one element whose lexical rule lives nowhere in its own
                // definition: its type is `http://hl7.org/fhirpath/System.String` with no `regex`
                // extension, so nothing above emits anything and every resource id was unconstrained.
                //
                // Constraining `IdPrimitive` instead does not work — the property is generated as a
                // bare `?string`, so the primitive wrapper class is never consulted.
                //
                // Matched on `path`, not `base.path`: `base.path` is `Resource.id` on *every* concrete
                // resource's `id` too (Patient.id, Observation.id, …), and Symfony merges a parent's
                // property constraints into a child that redeclares the property, so emitting on both
                // AbstractResource::$id and PatientResource::$id yields two violations where the
                // reference validator reports one. Matching `path` emits exactly once, on the
                // `Resource` StructureDefinition, and all 146 generated resource classes inherit it.
                //
                // Not to be confused with `Element.id` (Narrative.id, Coding.id, …), which has the
                // identical `System.String` shape but carries no such rule and must stay unconstrained.
                //
                // One constraint, not Regex + Length: the length limit is inside the quantifier, so an
                // over-long id fails exactly once, matching the reference validator's single issue.
                $param->addAttribute(Regex::class, [
                    'pattern' => self::toDelimitedAnchoredPattern(self::RESOURCE_ID_PATTERN),
                    // `{{ value }}` is already rendered quoted by Symfony — no quotes around it here.
                    'message' => 'Invalid Resource id: {{ value }} must be 1-64 characters of A-Z, a-z, 0-9, "-" or ".".',
                ]);
            }

            // FHIRValueSetBinding for required/extensible/preferred-strength bindings.
            // Skip when valueSet is absent — an empty URL causes the validator to emit a
            // misleading "no enum class found" violation for every non-null property value.
            if (isset($element['binding'])) {
                $bindingStrength = $element['binding']['strength'] ?? 'extensible';
                $valueSetUrl     = $element['binding']['valueSet'] ?? '';
                if ($valueSetUrl !== '' && $this->shouldEmitBindingAttribute($bindingStrength)) {
                    $args = ['valueSetUrl' => $valueSetUrl, 'strength' => $bindingStrength];

                    $maxValueSetUrl = $this->extractMaxValueSetUrl($element['binding']['extension'] ?? []);
                    if ($maxValueSetUrl !== null) {
                        $args['maxValueSetUrl'] = $maxValueSetUrl;
                    }

                    // Record which generated enum backs this value set. The validator cannot work it
                    // out from the URL: class names come from the ValueSet's `name` via
                    // ClassNameResolver, so `.../ValueSet/item-type` is `QuestionnaireItemType` and
                    // `http-verb` is `HTTPVerb`. Guessing from the slug missed 27 of 28 value sets and
                    // silently downgraded 19 core required bindings to an unenforced warning.
                    $enumClass = $this->resolveBoundEnumName($valueSetUrl, $version, $builderContext);
                    if ($enumClass !== null) {
                        $args['enumClass'] = $enumClass;
                    }

                    $param->addAttribute(FHIRValueSetBinding::class, $args);
                }
            }

            // FHIRFixedValue from fixed[x] polymorphic field (scalar values only).
            $fixedField = ElementDefinitionHelper::extractPolymorphicField($element, 'fixed');
            if ($fixedField !== null && is_scalar($fixedField['value'])) {
                $param->addAttribute(FHIRFixedValue::class, ['value' => $fixedField['value']]);
            }

            // FHIRPatternValue from pattern[x] polymorphic field (array values only).
            $patternField = ElementDefinitionHelper::extractPolymorphicField($element, 'pattern');
            if ($patternField !== null && is_array($patternField['value'])) {
                $param->addAttribute(FHIRPatternValue::class, ['pattern' => $patternField['value']]);
            }

            if (($element['mustSupport'] ?? false) === true) {
                $param->addAttribute(FHIRMustSupport::class);
            }

            if (($element['isModifier'] ?? false) === true) {
                $reason = $element['isModifierReason'] ?? null;
                $args   = $reason !== null ? ['reason' => $reason] : [];
                $param->addAttribute(FHIRIsModifier::class, $args);
            }

            // FHIRObligation from obligation extensions on snapshot elements.
            $obligations             = (new ObligationExtensionParser())->parse($element['extension'] ?? []);
            $hasPopulationObligation = false;
            foreach ($obligations as $obligation) {
                $args = ['code' => $obligation['code']];
                if ($obligation['actor'] !== null) {
                    $args['actor'] = $obligation['actor'];
                }
                if ($obligation['filter'] !== null) {
                    $args['filter'] = $obligation['filter'];
                }
                if ($obligation['documentation'] !== null) {
                    $args['documentation'] = $obligation['documentation'];
                }
                $param->addAttribute(FHIRObligation::class, $args);

                $obligationCode = ObligationCode::tryFrom($obligation['code']);
                if ($obligationCode !== null && $obligationCode->isPopulationObligation()) {
                    $hasPopulationObligation = true;
                }
            }
            if ($hasPopulationObligation) {
                $param->addAttribute(FHIRObligationConstraint::class);
            }

            // FHIRTargetProfile from type[].targetProfile arrays (Reference and canonical properties).
            $targetProfiles = [];
            foreach ($element['type'] ?? [] as $type) {
                foreach ($type['targetProfile'] ?? [] as $profileUrl) {
                    if (is_string($profileUrl) && $profileUrl !== '') {
                        $targetProfiles[] = $profileUrl;
                    }
                }
            }
            if ($targetProfiles !== []) {
                $param->addAttribute(FHIRTargetProfile::class, ['targetProfiles' => array_values(array_unique($targetProfiles))]);
            }
        }
    }

    /**
     * Extract the maxValueSet URL from a binding's extension array.
     *
     * Looks for `elementdefinition-maxValueSet` with a `valueCanonical` field.
     * Returns null when the extension is absent.
     *
     * @param array<int, array<string, mixed>> $extensions The binding.extension array
     */
    private function extractMaxValueSetUrl(array $extensions): ?string
    {
        foreach ($extensions as $ext) {
            if (($ext['url'] ?? '') === 'http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet') {
                return isset($ext['valueCanonical']) && is_string($ext['valueCanonical']) ? $ext['valueCanonical'] : null;
            }
        }

        return null;
    }

    /**
     * Corrections for `regex` extensions that ship defective in the published StructureDefinitions.
     *
     * Keyed on the **exact** upstream string, so a correction stops applying the moment HL7 ships a
     * package that no longer contains the defect. That is deliberate: a correction keyed on type name
     * would silently keep overriding a pattern that had since been fixed — or worse, revised — upstream.
     *
     * Every entry needs the defect named and the replacement sourced from something other than our own
     * judgement. Do not add one for a pattern that is merely stricter or looser than we would like.
     *
     * @var array<string, string>
     */
    private const REGEX_CORRECTIONS = [
        // R5 `decimal`. `hl7.fhir.r5.core#5.0.0`'s StructureDefinition-decimal.json carries a stray
        // closing brace in the exponent group — `[0-9]{1,9}}` — which is a literal `}` to PCRE, not a
        // typo it can see through. The emitted constraint therefore rejects the legal `1e1`, `1.0e-1`,
        // `0.1e11` and `0.12e3`, and accepts the malformed `1e1}`.
        //
        // The replacement is the HL7 Java reference validator's own decimal pattern, quoted verbatim
        // from its output in `outcomes/java` (search: `does not meet decimal regex`) rather than
        // hand-repaired here. It differs from upstream-minus-the-brace in one further respect: the
        // exponent may not carry a leading zero. That is not our embellishment — Java flags `1e09` on
        // `R5.primitive-good`, so spec-minus-the-brace would put the generated constraint in direct
        // conflict with both the reference validator and `PrimitiveFormatChecker`, which already
        // reports against this exact pattern.
        '-?(0|[1-9][0-9]{0,17})(\.[0-9]{1,17})?([eE][+-]?[0-9]{1,9}})?' => '-?(0|[1-9][0-9]{0,17})(\.[0-9]{1,17})?([eE](0|[+\-]?[1-9][0-9]{0,9}))?',
    ];

    /**
     * Extract a regex pattern from a primitive element's type extension.
     *
     * Looks for the `http://hl7.org/fhir/StructureDefinition/regex` extension on element.type[0].
     * Returns null when the extension is absent.
     *
     * A published pattern is not automatically a correct one — see {@see self::REGEX_CORRECTIONS}.
     *
     * @param array<string, mixed> $element The FHIR element definition
     */
    private function extractPrimitiveRegexPattern(array $element): ?string
    {
        foreach ($element['type'][0]['extension'] ?? [] as $ext) {
            if (($ext['url'] ?? '') === 'http://hl7.org/fhir/StructureDefinition/regex') {
                $pattern = $ext['valueString'] ?? null;

                if (!is_string($pattern)) {
                    return null;
                }

                return self::REGEX_CORRECTIONS[$pattern] ?? $pattern;
            }
        }

        return null;
    }

    /**
     * Delimiters tried, in order, when wrapping a FHIR regex for PCRE.
     *
     * None of these is a PCRE metacharacter, so an unescaped occurrence inside the pattern is always
     * a literal and the "first candidate absent from the pattern" rule is safe. `/` is deliberately
     * absent: two core patterns (`base64Binary`, and R5's `base64Binary`) contain it literally.
     *
     * @var list<string>
     */
    private const REGEX_DELIMITER_CANDIDATES = ['~', '#', '%', '!', '@'];

    /**
     * The lexical rule for `Resource.id`, identical in R4, R4B and R5.
     *
     * Sourced from the `id` primitive's own `regex` extension rather than invented here; the
     * `Resource.id` element itself carries no `regex` extension to read it from.
     */
    private const RESOURCE_ID_PATTERN = '[A-Za-z0-9\-\.]{1,64}';

    /**
     * Wrap a raw FHIR regex as a delimited, whole-value-anchored PCRE pattern.
     *
     * StructureDefinitions carry regexes in an undelimited dialect (`true|false`,
     * `(\s*([0-9a-zA-Z\+/=]){4}\s*)+`). Handing those straight to `Symfony\...\Regex` is a silent
     * inversion of the constraint: `preg_match()` raises ("Delimiter must not be alphanumeric…",
     * "Unknown modifier '+'") and returns `false`, which `RegexValidator` reads as "did not match",
     * so the constraint rejects *every* value including the valid ones.
     *
     * Anchored with `\A`/`\z` rather than `^`/`$`: FHIR regexes constrain the entire lexical value,
     * and `$` also matches immediately before a trailing newline, so `^…$` would accept `"abc\n"`
     * for `code`, `id`, `oid` and friends. The body is wrapped in a non-capturing group so a
     * top-level alternation (`true|false`) cannot escape the anchors.
     */
    private static function toDelimitedAnchoredPattern(string $fhirRegex): string
    {
        foreach (self::REGEX_DELIMITER_CANDIDATES as $candidate) {
            if (! str_contains($fhirRegex, $candidate)) {
                return $candidate . '\A(?:' . $fhirRegex . ')\z' . $candidate;
            }
        }

        // Every candidate occurs literally in the pattern. Escape the occurrences of the first one
        // rather than assuming this cannot happen — a future FHIR version only has to ship one regex
        // containing all five characters to turn an unchecked assumption into a silent inversion.
        $delimiter = self::REGEX_DELIMITER_CANDIDATES[0];
        $escaped   = '';
        $length    = strlen($fhirRegex);
        for ($i = 0; $i < $length; ++$i) {
            $char = $fhirRegex[$i];
            // Preserve existing escape sequences verbatim so `\~` is not turned into `\\~`.
            if ($char === '\\' && $i + 1 < $length) {
                $escaped .= $char . $fhirRegex[$i + 1];
                ++$i;
                continue;
            }
            if ($char === $delimiter) {
                $escaped .= '\\';
            }
            $escaped .= $char;
        }

        return $delimiter . '\A(?:' . $escaped . ')\z' . $delimiter;
    }

    /**
     * Resolve the propertyKind for a FHIR element based on its parameter name and type codes.
     *
     * @param string               $parameterName PHP parameter name (camelCase)
     * @param array<string, mixed> $element       FHIR element definition
     */
    private function resolvePropertyKind(string $parameterName, array $element): string
    {
        if ($parameterName === 'extension') {
            return 'extension';
        }

        if ($parameterName === 'modifierExtension') {
            return 'modifierExtension';
        }

        if (str_contains($element['path'], '[x]')) {
            return 'choice';
        }

        $types = $element['type'] ?? [];
        if (empty($types)) {
            return 'complex'; // contentReference or unresolved
        }

        return $this->resolvePropertyKindFromCode($types[0]['code'] ?? '');
    }

    /**
     * Whether a required property must use `NotNull` instead of `NotBlank`.
     *
     * Symfony's `NotBlank` rejects `false`, `0`, `'0'` and `''`. FHIR cardinality means *present*, not
     * *truthy*, so any property whose PHP type admits a falsy scalar needs `NotNull`. Booleans are the
     * common case — `enableWhen.answerBoolean: false` is a perfectly valid answer that `NotBlank`
     * rejected — and choice types are included because a `value[x]` can resolve to `bool` or `int`.
     *
     * @param array<int|string, string> $types PHP type names for the property
     */
    private static function requiresNotNullRatherThanNotBlank(array $types): bool
    {
        foreach ($types as $type) {
            if (in_array(ltrim($type, '?'), ['bool', 'int', 'float', 'mixed'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the `#[FHIRPathInvariant]` arguments for one StructureDefinition constraint.
     *
     * `bestPractice` is emitted only when true, so the vast majority of invariants keep their
     * existing three-argument form and the regen diff stays readable. The flag comes from the
     * `elementdefinition-bestpractice` extension, which marks a constraint as a recommendation
     * rather than a conformance rule — the HL7 Java reference validator does not report those by
     * default, and reporting them buried real findings (475 of our 767 R4 warnings were `dom-6`).
     * In R4 only `dom-6` and `con-3` carry it, over 189 declarations.
     *
     * @param array<string, mixed> $constraint
     *
     * @return array<string, mixed>
     */
    private static function invariantAttributeArgs(array $constraint, string $expression): array
    {
        $args = [
            'key'        => $constraint['key'],
            'severity'   => $constraint['severity'],
            'expression' => $expression,
            'human'      => $constraint['human'],
        ];

        foreach ($constraint['extension'] ?? [] as $extension) {
            if (!is_array($extension)) {
                continue;
            }

            $url = $extension['url'] ?? '';
            if (is_string($url)
                && str_ends_with($url, '/elementdefinition-bestpractice')
                && ($extension['valueBoolean'] ?? false) === true) {
                $args['bestPractice'] = true;
                break;
            }
        }

        return $args;
    }

    /**
     * Emit `#[Assert\Valid]` on properties holding nested FHIR objects, so their constraints run.
     *
     * Symfony's validator descends into a nested object **only** where the referring property carries
     * this attribute. Without it, every constraint declared on a backbone element or datatype is
     * unreachable when a resource is validated as a whole: measured before this was added, a
     * `Parameters.parameter` violating `inv-1` reported zero errors nested inside its
     * `ParametersResource` and one error when passed as the validation root. 117 / 123 / 179 invariant
     * declarations in R4 / R4B / R5 were dead for that reason, against 110 / 114 / 147 resource-level
     * ones that worked.
     *
     * The set is deliberately narrow, and each exclusion is load-bearing rather than cautious:
     *
     *  - **`choice`** — excluded because it is the one kind that can hold a raw scalar. A `value[x]`
     *    legitimately carries `bool`, `int` or `string`, and `Assert\Valid` on a non-object throws
     *    `NoSuchMetadataException` ("Cannot create metadata for non-objects"), verified directly. This
     *    would be a fatal error at validation time, not a missed check.
     *  - **`extension`** — excluded because `FHIRValidationService` already walks extensions itself
     *    (`validateExtensionContexts`, `validateModifierExtensions`). Cascading as well would report
     *    the same violation twice.
     *  - **`primitive`** — excluded because the primitive wrapper classes carry no constraints of their
     *    own (measured: 0 across `Primitive/` in R4), so cascading into them is pure traversal cost.
     *  - **`scalar`** — never an object.
     *
     * Verified before emitting: of 2560 `complex`/`backbone`/`resource` properties in the R4 tree,
     * **zero** declare a scalar in their PHP type, so this cannot hit the non-object throw.
     *
     * If a future FHIR version gives primitives or extensions real constraints, revisit — but revisit
     * `choice` only with a guard, because that exclusion is about a runtime exception, not coverage.
     */
    private function addCascadeIfNested(PromotedParameter $param, string $propertyKind): void
    {
        if (!in_array($propertyKind, ['backbone', 'complex', 'resource'], true)) {
            return;
        }

        $param->addAttribute(Valid::class);
    }

    /**
     * Map a single FHIR type code to a propertyKind string.
     *
     * @param string $code The FHIR type code (e.g. 'boolean', 'dateTime', 'HumanName')
     */
    private function resolvePropertyKindFromCode(string $code): string
    {
        // FHIRPath system types resolve to PHP scalar builtins
        if (str_starts_with($code, 'http://hl7.org/fhirpath/System.')) {
            return 'scalar';
        }

        if ($code === 'BackboneElement') {
            return 'backbone';
        }

        if (in_array($code, ['Resource', 'DomainResource'], true)) {
            return 'resource';
        }

        // boolean/integer/decimal resolve to PHP scalars via resolveClassFromType() early exits
        if (in_array($code, ['boolean', 'integer', 'decimal'], true)) {
            return 'scalar';
        }

        // Remaining FHIR primitive types resolve to FHIR primitive wrapper classes
        $primitiveTypes = [
            'integer64', 'string', 'uri', 'url', 'canonical', 'base64Binary', 'instant',
            'date', 'dateTime', 'time', 'code', 'oid', 'id',
            'markdown', 'unsignedInt', 'positiveInt', 'uuid', 'xhtml',
        ];

        if (in_array($code, $primitiveTypes, true)) {
            return 'primitive';
        }

        return 'complex';
    }

    /**
     * Resolve a FHIR type code to its PHP type string for use in variant metadata.
     *
     * Returns a PHP builtin ('bool', 'int', 'float', 'string') for scalar types,
     * or a FQCN without leading backslash for class types.
     *
     * @param string                  $code           FHIR type code
     * @param string                  $version        FHIR version
     * @param BuilderContextInterface $builderContext Builder context
     */
    private function resolvePhpTypeForCode(string $code, string $version, BuilderContextInterface $builderContext): string
    {
        // FHIRPath system types and FHIR scalars → PHP scalar builtins
        // (mirrors the early-exit logic in resolveClassFromType())
        switch ($code) {
            case 'http://hl7.org/fhirpath/System.Boolean':
            case 'boolean':
                return 'bool';
            case 'http://hl7.org/fhirpath/System.Integer':
            case 'integer':
                return 'int';
            case 'http://hl7.org/fhirpath/System.Decimal':
            case 'decimal':
                return 'string';
            case 'http://hl7.org/fhirpath/System.String':
                return 'string';
            case 'http://hl7.org/fhirpath/System.Date':
                return 'Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate';
            case 'http://hl7.org/fhirpath/System.Time':
                return 'Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime';
            case 'http://hl7.org/fhirpath/System.DateTime':
                return 'Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime';
        }

        // Normalize to HL7 URL for context lookup
        $typeUrl = str_starts_with($code, 'http://') || str_starts_with($code, 'https://')
            ? $code
            : 'http://hl7.org/fhir/StructureDefinition/' . $code;

        $typeFound = $builderContext->getType($typeUrl);
        if ($typeFound !== null) {
            return ltrim($typeFound->fqcn, '\\');
        }

        // Fallback: construct FQCN via namespace resolution
        try {
            $correctNamespace = $this->getNamespaceForFhirType($code, $version, $builderContext);
            $primitiveTypes   = [
                'boolean', 'integer', 'integer64', 'string', 'decimal', 'uri', 'url',
                'canonical', 'base64Binary', 'instant', 'date', 'dateTime', 'time', 'code',
                'oid', 'id', 'markdown', 'unsignedInt', 'positiveInt', 'uuid', 'xhtml',
            ];
            $suffix    = in_array($code, $primitiveTypes, true) ? 'Primitive' : '';
            $className = StringCase::pascal($code) . $suffix;

            return $correctNamespace . '\\' . $className;
        } catch (\Throwable) {
            return 'string';
        }
    }

    /**
     * Build the variants list for a choice element (value[x] / deceased[x]).
     *
     * Each variant maps one FHIR type code to its PHP type and the concrete JSON/XML element name.
     *
     * @param array<string, mixed>    $element        FHIR element definition (must have type[])
     * @param string                  $version        FHIR version
     * @param BuilderContextInterface $builderContext Builder context
     *
     * @return list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string, isBuiltin: bool}>
     */
    private function buildChoiceVariants(array $element, string $version, BuilderContextInterface $builderContext): array
    {
        $variants = [];
        $baseName = $this->getChoiceBaseName($element['path']);

        foreach ($element['type'] ?? [] as $type) {
            $code = $type['code'] ?? '';
            if ($code === '') {
                continue;
            }

            $propertyKind = $this->resolvePropertyKindFromCode($code);
            $phpType      = $this->resolvePhpTypeForCode($code, $version, $builderContext);
            $isBuiltin    = in_array($phpType, ['bool', 'int', 'float', 'string'], true);

            // Compute concrete JSON/XML element name: baseName + ucfirst(typeCode)
            // For URL-style codes take the last dot-separated segment
            if (str_contains($code, '/') || str_contains($code, '.')) {
                $segments  = preg_split('/[\/.]/', $code) ?: [];
                $typeLabel = $segments !== [] ? ucfirst((string) end($segments)) : ucfirst($code);
            } else {
                $typeLabel = ucfirst($code);
            }

            $variants[] = [
                'fhirType'     => $code,
                'propertyKind' => $propertyKind,
                'phpType'      => $phpType,
                'jsonKey'      => $baseName . $typeLabel,
                'isBuiltin'    => $isBuiltin,
            ];
        }

        return $variants;
    }

    /**
     * Extract the base element name from a choice element path.
     *
     * Strips the trailing '[x]' and path prefix.
     * E.g. 'Patient.deceased[x]' → 'deceased'
     *
     * @param string $elementPath FHIR element path
     */
    private function getChoiceBaseName(string $elementPath): string
    {
        $parts = explode('.', $elementPath);

        return str_replace('[x]', '', end($parts));
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
        $className  = StringCase::pascal($code);
        $storedType = $builderContext->getType($className);
        if ($storedType !== null) {
            return $storedType->namespace;
        }

        // Complex types that are generated into the DataType namespace in all FHIR versions.
        // The generator's `kind: complex-type` switch (see generateModelClass) places them
        // in getDatatypeNamespace, so getNamespaceForFhirType must agree.
        // (Previously this list was named "typesMovedToDataTypeInR5" with a wrong R4/R4B
        // special-case that returned getElementNamespace — that produced broken `use` statements.)
        $typesInDataTypeNamespace = [
            'Dosage',
            'Timing',
            'ElementDefinition',
            'ProductShelfLife',
            'MarketingStatus',
            'SubstanceAmount',    // complex-type in all FHIR versions; generated into DataType
            'ProdCharacteristic', // complex-type in all FHIR versions; generated into DataType
            'Population',         // complex-type in all FHIR versions; generated into DataType
        ];

        if (in_array($code, $typesInDataTypeNamespace, true)) {
            // These complex types live in DataType namespace in all FHIR versions
            try {
                return $builderContext->getDatatypeNamespace($version)->getName();
            } catch (GenerationException) {
                return $builderContext->getElementNamespace($version)->getName();
            }
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
     * Determine if binding strength warrants emitting a #[FHIRValueSetBinding] attribute.
     *
     * Required bindings get an enum-backed attribute; extensible and preferred bindings
     * get a weaker attribute for optional terminology-server validation. Example bindings
     * are documentation-only and produce no attribute.
     */
    private function shouldEmitBindingAttribute(string $bindingStrength): bool
    {
        return match ($bindingStrength) {
            'required', 'extensible', 'preferred' => true,
            default                               => false,
        };
    }

    /**
     * Resolve ValueSet URL to appropriate code type
     *
     * Handles ValueSet resolution with versioned URLs, fallback to string type
     * when ValueSet cannot be resolved, and proper enum/code type generation.
     *
     * @param string                  $valueSetUrl    The ValueSet URL (may include version)
     * @param BuilderContextInterface $builderContext The builder context
     * @param string                  $version        The FHIR version
     *
     * @return string The resolved code type (class name or 'string')
     */
    private function resolveValueSetCodeType(
        string $valueSetUrl,
        BuilderContextInterface $builderContext,
        string $version,
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
        return CanonicalUrl::stripVersion($valueSetUrl);
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
        $lastPart  = $pathParts === [] ? null : $pathParts[array_key_last($pathParts)];
        if ($lastPart === null) {
            throw GenerationException::invalidElementPath($path);
        }

        // Strip [x] suffix for choice elements (e.g., "value[x]" → "value")
        // The [x] is FHIR notation for polymorphism, not part of the property name
        $propertyName = str_replace('[x]', '', $lastPart->toString());

        return lcfirst(u($propertyName)->camel()->toString());
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
     *
     * @return array<string>
     */
    public function resolveClassFromType(array $element, BuilderContextInterface $builderContext, string $version, array $types, ?EnumType $enum): array
    {
        foreach ($element['type'] as $type) {
            $code = $type['code'];

            $targetEnumNamespace = $builderContext->getEnumNamespace($version)->getName();
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
                $types[] = 'string';

                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.DateTime') {
                // instant.value also maps to System.DateTime — distinguish by element path
                if (str_starts_with($element['path'] ?? '', 'instant.')) {
                    $types[] = '\\Ardenexal\\FHIRTools\\Component\\Models\\Primitive\\FHIRInstant';
                } else {
                    $types[] = '\\Ardenexal\\FHIRTools\\Component\\Models\\Primitive\\FHIRDateTime';
                }

                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Date') {
                $types[] = '\\Ardenexal\\FHIRTools\\Component\\Models\\Primitive\\FHIRDate';

                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Time') {
                $types[] = '\\Ardenexal\\FHIRTools\\Component\\Models\\Primitive\\FHIRTime';

                continue;
            }

            if ($code === 'string') {
                $correctNamespace = $this->getNamespaceForFhirType($code, $version, $builderContext);
                $types[]          = '\\' . $correctNamespace . '\\' . StringCase::pascal($code) . 'Primitive';
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
                    $codeType    = $this->resolveValueSetCodeType($valueSetUrl, $builderContext, $version);
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
                    $primitiveTypes   = [
                        'boolean', 'integer', 'integer64', 'string', 'decimal', 'uri', 'url',
                        'canonical', 'base64Binary', 'instant', 'date', 'dateTime', 'time', 'code',
                        'oid', 'id', 'markdown', 'unsignedInt', 'positiveInt', 'uuid', 'xhtml',
                    ];
                    $suffix    = in_array($code, $primitiveTypes, true) ? 'Primitive' : '';
                    $className = StringCase::pascal($code) . $suffix;
                    $types[]   = '\\' . $correctNamespace . '\\' . $className;
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

        return array_unique($types);
    }
}
