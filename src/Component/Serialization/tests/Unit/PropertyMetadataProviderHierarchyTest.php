<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Stub base class with FhirProperty annotations on its constructor parameters.
 * Simulates the pattern used by base FHIR types like Extension.
 */
class HierarchyStubBase
{
    public function __construct(
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', isRequired: true)]
        public ?string $url = null,
    ) {
    }
}

/**
 * Stub child class that adds a new typed property and re-declares inherited params
 * WITHOUT re-adding #[FhirProperty] on them — exactly the pattern generated for
 * typed extension subclasses.
 */
class HierarchyStubChild extends HierarchyStubBase
{
    public function __construct(
        #[FhirProperty(fhirType: 'string', propertyKind: 'scalar')]
        public ?string $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(id: $id, extension: $extension, url: 'http://example.com/ext');
    }
}

/**
 * Stub grandchild that overrides a param from the base with its own #[FhirProperty].
 */
class HierarchyStubGrandchild extends HierarchyStubChild
{
    public function __construct(
        // Overrides 'id' from the grandparent — child annotation should win
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', isRequired: true)]
        public ?string $id = null,
    ) {
        parent::__construct(id: $id);
    }
}

/**
 * @covers \Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider
 */
class PropertyMetadataProviderHierarchyTest extends TestCase
{
    private PropertyMetadataProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new PropertyMetadataProvider();
    }

    public function testBaseClassMetadataIsResolvedNormally(): void
    {
        $meta = $this->provider->getPropertyMetadata(HierarchyStubBase::class);

        self::assertArrayHasKey('id', $meta);
        self::assertArrayHasKey('extension', $meta);
        self::assertArrayHasKey('url', $meta);
        self::assertCount(3, $meta);
    }

    public function testChildClassInheritsParentFhirPropertyAnnotations(): void
    {
        $meta = $this->provider->getPropertyMetadata(HierarchyStubChild::class);

        // Child's own property
        self::assertArrayHasKey('valueString', $meta);
        self::assertSame('string', $meta['valueString']->fhirType);

        // Inherited from parent — must be visible even though child constructor
        // re-declares $id and $extension without #[FhirProperty]
        self::assertArrayHasKey('id', $meta, 'Parent "id" param must be inherited');
        self::assertArrayHasKey('extension', $meta, 'Parent "extension" param must be inherited');
        self::assertArrayHasKey('url', $meta, 'Parent "url" param must be inherited');
    }

    public function testChildParamWithoutAttributeDoesNotOverrideParentAttribute(): void
    {
        $meta = $this->provider->getPropertyMetadata(HierarchyStubChild::class);

        // $id is re-declared in child WITHOUT #[FhirProperty] → parent's annotation must survive
        self::assertArrayHasKey('id', $meta);
        self::assertSame('scalar', $meta['id']->propertyKind);
    }

    public function testGrandchildAttributeOverridesGrandparentAttribute(): void
    {
        $meta = $this->provider->getPropertyMetadata(HierarchyStubGrandchild::class);

        // Grandchild redeclares $id WITH #[FhirProperty] (isRequired: true)
        // → grandchild annotation must win over grandparent's
        self::assertArrayHasKey('id', $meta);
        self::assertTrue($meta['id']->isRequired, 'Grandchild annotation should override grandparent');
    }

    public function testExtensionArrayPropertyIsInherited(): void
    {
        $meta = $this->provider->getPropertyMetadata(HierarchyStubChild::class);

        self::assertArrayHasKey('extension', $meta);
        self::assertTrue($meta['extension']->isArray);
        self::assertSame('extension', $meta['extension']->propertyKind);
    }
}
