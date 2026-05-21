<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that FHIRModelGenerator emits #[FHIRValueSetBinding] on properties for
 * required, extensible, and preferred binding strengths, and does not emit it for
 * example bindings.
 */
class FHIRBindingAttributeEmissionTest extends TestCase
{
    private const string TEST_NS = 'Ardenexal\\FHIRTools\\BindingAttributeEmissionTest';

    private const string VS_URL = 'http://hl7.org/fhir/ValueSet/languages';

    private FHIRModelGenerator $generator;

    private BuilderContext $context;

    protected function setUp(): void
    {
        $this->generator = new FHIRModelGenerator();
        $this->context   = new BuilderContext();
        $this->context->addElementNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Resource'));
        $this->context->addDatatypeNamespace('R4', new PhpNamespace(self::TEST_NS . '\\DataType'));
        $this->context->addPrimitiveNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Primitive'));
        $this->context->addEnumNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Enum'));
    }

    public function testRequiredBindingEmitsAttribute(): void
    {
        $fqcn  = $this->generateAndEval('BindingRequired', 'required');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('language')->getAttributes(FHIRValueSetBinding::class);

        self::assertNotEmpty($attrs, '#[FHIRValueSetBinding] must be emitted for required binding');
        self::assertSame('required', $attrs[0]->newInstance()->strength);
    }

    public function testExtensibleBindingEmitsAttribute(): void
    {
        $fqcn  = $this->generateAndEval('BindingExtensible', 'extensible');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('language')->getAttributes(FHIRValueSetBinding::class);

        self::assertNotEmpty($attrs, '#[FHIRValueSetBinding] must be emitted for extensible binding');
        self::assertSame('extensible', $attrs[0]->newInstance()->strength);
    }

    public function testPreferredBindingEmitsAttribute(): void
    {
        $fqcn  = $this->generateAndEval('BindingPreferred', 'preferred');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('language')->getAttributes(FHIRValueSetBinding::class);

        self::assertNotEmpty($attrs, '#[FHIRValueSetBinding] must be emitted for preferred binding');
        self::assertSame('preferred', $attrs[0]->newInstance()->strength);
    }

    public function testExampleBindingDoesNotEmitAttribute(): void
    {
        $fqcn  = $this->generateAndEval('BindingExample', 'example');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('language')->getAttributes(FHIRValueSetBinding::class);

        self::assertEmpty($attrs, '#[FHIRValueSetBinding] must NOT be emitted for example binding');
    }

    public function testAttributeValueSetUrlMatchesBindingElement(): void
    {
        $fqcn  = $this->generateAndEval('BindingUrl', 'extensible');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('language')->getAttributes(FHIRValueSetBinding::class);

        self::assertSame(self::VS_URL, $attrs[0]->newInstance()->valueSetUrl);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function generateAndEval(string $typeName, string $strength): string
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD($typeName, 'complex-type', $strength),
            'R4',
            $this->context,
        );

        return $this->evalClass($class, self::TEST_NS . '\\DataType');
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSD(string $name, string $kind, string $bindingStrength): array
    {
        return [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://hl7.org/fhir/StructureDefinition/' . $name,
            'name'         => $name,
            'kind'         => $kind,
            'abstract'     => false,
            'snapshot'     => [
                'element' => [
                    ['path' => $name, 'min' => 0, 'max' => '1', 'base' => ['path' => $name]],
                    [
                        'path'    => $name . '.language',
                        'min'     => 0,
                        'max'     => '1',
                        'type'    => [['code' => 'http://hl7.org/fhirpath/System.String']],
                        'base'    => ['path' => $name . '.language'],
                        'binding' => [
                            'strength' => $bindingStrength,
                            'valueSet' => self::VS_URL,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function evalClass(ClassType $class, string $namespace): string
    {
        $printer = new Printer();
        $nsObj   = new PhpNamespace($namespace);
        $code    = "<?php declare(strict_types=1);\n\nnamespace {$namespace};\n\n"
            . $printer->printClass($class, $nsObj);
        eval(substr($code, 5));

        return $namespace . '\\' . $class->getName();
    }
}
