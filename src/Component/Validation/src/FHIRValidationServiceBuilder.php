<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Profile\StructureDefinitionRegistryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validation;

/**
 * Builds a fully wired {@see FHIRValidationService} without Symfony's container.
 *
 * The service needs a Symfony validator that reads PHP attributes and whose constraint validator
 * factory constructs every FHIR validator with its collaborators. Wiring that by hand is easy to get
 * silently wrong: a validator without attribute mapping finds nothing, and a slice validator without
 * the runtime attribute reader skips the slices of runtime profiles. This builder does it the way
 * FHIRBundle does.
 *
 * ```php
 * $service = FHIRValidationServiceBuilder::create()
 *     ->withPackageDirectories('/path/to/hl7.fhir.r4.core', '/path/to/hl7.fhir.au.base')
 *     ->build();
 * ```
 *
 * Each `with*()` call returns a new builder, so a configured builder can be shared and extended.
 * StructureDefinitions are searched in this order: files, then registries, then package
 * directories, each in the order given; the first match wins, so a local file overrides a
 * published definition. With none of them, the service validates generated profile classes only.
 *
 * @author Ardenexal
 */
final class FHIRValidationServiceBuilder
{
    /** @var list<string> */
    private array $packageDirectories = [];

    /** @var list<string> */
    private array $files = [];

    /** @var list<StructureDefinitionRegistryInterface> */
    private array $registries = [];

    private ?FHIRTerminologyClientInterface $terminologyClient = null;

    private FHIRReferenceResolverInterface $referenceResolver;

    private ?FHIRIGTypeRegistry $igTypeRegistry = null;

    private FHIRValidationMessageRegistry $messages;

    private function __construct()
    {
        $this->referenceResolver = new NullFHIRReferenceResolver();
        $this->messages          = new FHIRValidationMessageRegistry();
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Load profiles and extension definitions from extracted FHIR package directories.
     *
     * List the core package of your release too, and every package a profile depends on: a profile
     * is resolved through its parents up to the core definition.
     *
     * @param string ...$directories Folders holding `package.json`, or tarball roots holding `package/`
     */
    public function withPackageDirectories(string ...$directories): self
    {
        $clone                     = clone $this;
        $clone->packageDirectories = [...$this->packageDirectories, ...array_values($directories)];

        return $clone;
    }

    /**
     * Load single StructureDefinition JSON files. They take precedence over package definitions.
     *
     * @param string ...$paths Files each holding one StructureDefinition with its own `fhirVersion`
     */
    public function withStructureDefinitionFiles(string ...$paths): self
    {
        $clone        = clone $this;
        $clone->files = [...$this->files, ...array_values($paths)];

        return $clone;
    }

    /**
     * Look StructureDefinitions up in a registry of your own, such as one backed by a database.
     */
    public function withStructureDefinitionRegistry(StructureDefinitionRegistryInterface $registry): self
    {
        $clone               = clone $this;
        $clone->registries[] = $registry;

        return $clone;
    }

    /**
     * Check bindings no generated enum covers against a terminology server. Without one, such a
     * binding is reported as `fhir:unchecked-binding` INFO.
     */
    public function withTerminologyClient(FHIRTerminologyClientInterface $client): self
    {
        $clone                    = clone $this;
        $clone->terminologyClient = $client;

        return $clone;
    }

    /**
     * Resolve references for target-profile checks. Without one, those checks are skipped.
     */
    public function withReferenceResolver(FHIRReferenceResolverInterface $resolver): self
    {
        $clone                    = clone $this;
        $clone->referenceResolver = $resolver;

        return $clone;
    }

    /**
     * Resolve extension URLs to generated extension classes, which turns on the modifier-extension
     * checks and the reporting of unknown extensions.
     */
    public function withIgTypeRegistry(FHIRIGTypeRegistry $registry): self
    {
        $clone                 = clone $this;
        $clone->igTypeRegistry = $registry;

        return $clone;
    }

    /**
     * Override the messages violations are reported with.
     */
    public function withMessageRegistry(FHIRValidationMessageRegistry $messages): self
    {
        $clone           = clone $this;
        $clone->messages = $messages;

        return $clone;
    }

    /**
     * Build the service.
     *
     * Nothing is read from disk yet, apart from checking that each package directory exists: a
     * package's definitions are read the first time one is looked up.
     *
     * @throws \InvalidArgumentException When a package directory does not exist, or a file cannot be read as a StructureDefinition
     */
    public function build(): FHIRValidationService
    {
        $runtimeProfiles = $this->runtimeProfileResolver();
        $pathService     = new FHIRPathService();
        $accessor        = PropertyAccess::createPropertyAccessor();

        // The slice validator finds a profile's sibling slices by reading attributes off the
        // validated class; a resource checked against a runtime profile has none of its own
        $sliceAttributes = $runtimeProfiles === null ? new FHIRAttributeReader() : new RuntimeProfileAttributeReader($runtimeProfiles);

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory(new FHIRConstraintValidatorFactory(
                $accessor,
                $pathService,
                $this->messages,
                $sliceAttributes,
                $this->terminologyClient,
                $this->referenceResolver,
            ))
            ->getValidator();

        return new FHIRValidationService(
            $validator,
            $pathService,
            registry: $this->igTypeRegistry,
            runtimeProfiles: $runtimeProfiles,
        );
    }

    /**
     * One resolver over every configured source, or null when there is none.
     */
    private function runtimeProfileResolver(): ?RuntimeProfileResolver
    {
        $registries = [];
        if ($this->files !== []) {
            $registries[] = InMemoryStructureDefinitionRegistry::fromFiles($this->files);
        }
        array_push($registries, ...$this->registries);
        foreach ($this->packageDirectories as $directory) {
            $registries[] = new PackageDirectoryStructureDefinitionRegistry($directory);
        }

        return $registries === [] ? null : new RuntimeProfileResolver(new CompositeStructureDefinitionRegistry($registries));
    }
}
