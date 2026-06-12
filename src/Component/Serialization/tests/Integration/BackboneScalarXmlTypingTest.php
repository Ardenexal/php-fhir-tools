<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * Regression for S-XMLSCALAR (M02b): backbone-element scalar primitives (int/bool/float) must be
 * coerced to their PHP types when deserialized from XML. Coercion is done by
 * unwrapXmlValue($value, $propertyType) in the builtin-property branch — NOT by the dead
 * $meta->phpItemClass block removed in M02b. This test pins that the removed block was a redundant
 * vestige: backbone scalar typing still works.
 */
final class BackboneScalarXmlTypingTest extends TestCase
{
    private const string QUESTIONNAIRE_XML = <<<'XML'
<Questionnaire xmlns="http://hl7.org/fhir">
  <status value="active"/>
  <item>
    <linkId value="q1"/>
    <type value="string"/>
    <required value="true"/>
    <maxLength value="10"/>
  </item>
</Questionnaire>
XML;

    public function testBackboneScalarPrimitivesAreTypedFromXml(): void
    {
        $service = FHIRSerializationService::createDefault();

        $questionnaire = $service->deserialize(self::QUESTIONNAIRE_XML);

        $items = (new \ReflectionClass($questionnaire))->getProperty('item')->getValue($questionnaire);
        self::assertIsArray($items);
        self::assertNotEmpty($items, 'Questionnaire.item must deserialize');

        $item     = $items[0];
        $required = (new \ReflectionClass($item))->getProperty('required')->getValue($item);
        $maxLen   = (new \ReflectionClass($item))->getProperty('maxLength')->getValue($item);

        self::assertIsBool($required, 'backbone bool primitive must be coerced to bool, not left a string');
        self::assertTrue($required);
        self::assertIsInt($maxLen, 'backbone int primitive must be coerced to int, not left a string');
        self::assertSame(10, $maxLen);
    }
}
