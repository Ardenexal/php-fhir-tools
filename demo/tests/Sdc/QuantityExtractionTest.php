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
 * Proves a `quantity`-typed item's full `Quantity` answer object extracts cleanly onto
 * `Observation.value[x]` when the `definition` targets the choice directly — no `:valueQuantity.value`
 * slice sub-path needed (unlike the existing Sdc test fixtures, which use `decimal`-typed items writing
 * to `.value[x]:valueQuantity.value` alone). `Quantity.value` is FHIR's `decimal` type, which serializes
 * as a bare JSON number even though the PHP model holds it as a `numeric-string`.
 */
final class QuantityExtractionTest extends KernelTestCase
{
    public function testQuantityAnswerExtractsOntoObservationValueX(): void
    {
        $questionnaire = [
            'resourceType' => 'Questionnaire',
            'status'       => 'active',
            'item'         => [
                [
                    'extension' => [[
                        'url'       => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract',
                        'extension' => [['url' => 'definition', 'valueCanonical' => 'http://hl7.org/fhir/StructureDefinition/Observation']],
                    ]],
                    'linkId' => 'weightObs',
                    'type'   => 'group',
                    'item'   => [
                        ['definition' => 'http://hl7.org/fhir/StructureDefinition/Observation#Observation.value[x]', 'linkId' => 'weight', 'type' => 'quantity'],
                    ],
                ],
            ],
        ];

        $answers = ['weightObs' => ['weight' => '70.5 kg']];

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

        $resource = $bundle['entry'][0]['resource'];
        self::assertEqualsWithDelta(70.5, $resource['valueQuantity']['value'] ?? null, 0.0001);
        self::assertSame('kg', $resource['valueQuantity']['unit'] ?? null);
    }
}
