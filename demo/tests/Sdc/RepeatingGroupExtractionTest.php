<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\QuestionnaireResponseBuilder;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\Contract\ExtractServiceInterface;
use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRVersionedSerializationServiceLocator;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Proves a repeating Questionnaire GROUP extracts into multiple separate array entries on the target
 * resource (e.g. two `Patient.contact` entries, not one collapsed/overwritten entry) — the M03 exit
 * criterion for repeating groups.
 *
 * The repeating group itself MUST carry its own `definition` (e.g. `Patient#Patient.contact`), not rely
 * on its leaf children's absolute paths to imply the array segment. Without the group's own `definition`,
 * `DefinitionExtractionWalker::walkOneItem` treats it as a transparent "logical group" passthrough — each
 * leaf child then resolves the shared array segment (`contact`) independently via
 * `DefinitionPathWriter::createIntermediate()`, whose `reuseExisting` reuse of the first array element
 * collapses every repeat instance into one. Giving the group its own `definition` routes it through the
 * walker's explicit group-item branch instead, where `createIntermediate()` is called once per response
 * item with `reuseExisting: false` at the final segment — appending a fresh array entry per repeat
 * instance, exactly as needed.
 */
final class RepeatingGroupExtractionTest extends KernelTestCase
{
    public function testRepeatingContactGroupExtractsAsTwoSeparatePatientContactEntries(): void
    {
        $questionnaire = [
            'resourceType' => 'Questionnaire',
            'status'       => 'active',
            'item'         => [
                [
                    'extension' => [[
                        'url'       => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract',
                        'extension' => [['url' => 'definition', 'valueCanonical' => 'http://hl7.org/fhir/StructureDefinition/Patient']],
                    ]],
                    'linkId' => 'patient',
                    'type'   => 'group',
                    'item'   => [
                        [
                            'definition' => 'http://hl7.org/fhir/StructureDefinition/Patient#Patient.contact',
                            'linkId'     => 'contact',
                            'type'       => 'group',
                            'repeats'    => true,
                            'item'       => [
                                ['definition' => 'http://hl7.org/fhir/StructureDefinition/Patient#Patient.contact.name.given', 'linkId' => 'contactGiven', 'type' => 'string'],
                                ['definition' => 'http://hl7.org/fhir/StructureDefinition/Patient#Patient.contact.name.family', 'linkId' => 'contactFamily', 'type' => 'string'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $answers = [
            'patient' => [
                'contact' => [
                    '0' => ['contactGiven' => 'Alice', 'contactFamily' => 'Anderson'],
                    '1' => ['contactGiven' => 'Bob', 'contactFamily' => 'Baker'],
                ],
            ],
        ];

        self::bootKernel();
        $container = static::getContainer();

        /** @var ExtractServiceInterface $extractService */
        $extractService = $container->get(ExtractServiceInterface::class);
        /** @var FHIRVersionedSerializationServiceLocator $locator */
        $locator = $container->get(FHIRVersionedSerializationServiceLocator::class);
        $service = $locator->get(FhirVersion::R4);

        $questionnaireResponse = (new QuestionnaireResponseBuilder())->build($questionnaire['item'], $answers);
        $questionnaireModel    = $service->deserialize(json_encode($questionnaire, JSON_THROW_ON_ERROR), QuestionnaireResource::class);

        $result     = $extractService->extract($questionnaireResponse, new ExtractContext(fhirVersion: FhirVersion::R4, questionnaire: $questionnaireModel));
        $bundleJson = $service->serializeToJson($result->getResource());
        $bundle     = json_decode($bundleJson, true, 512, JSON_THROW_ON_ERROR);

        $patient = $bundle['entry'][0]['resource'];
        self::assertCount(2, $patient['contact'] ?? [], 'expected TWO separate Patient.contact entries, one per repeat instance');
        self::assertSame('Anderson', $patient['contact'][0]['name']['family']);
        self::assertSame(['Alice'], $patient['contact'][0]['name']['given']);
        self::assertSame('Baker', $patient['contact'][1]['name']['family']);
        self::assertSame(['Bob'], $patient['contact'][1]['name']['given']);
    }
}
