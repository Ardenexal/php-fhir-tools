<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuSubstanceAdministration;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Patient;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TS;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * CDA fixes the order of an element's children, and a receiver that validates structure rejects a
 * document whose order is wrong even when every child is present and correct.
 *
 * Each case here populates the elements that FOLLOW the one under test, so "published position" and
 * "appended last" are different answers. Without that, both cases pass against the original defect:
 * the reported sample document left every element between the confidentiality code and the completion
 * code empty, which made the wrong position look right.
 *
 * @coversNothing
 */
final class CdaElementOrderTest extends TestCase
{
    /**
     * The default service, which routes CDA logical models through the XML normalizer under test.
     */
    private FHIRSerializationService $service;

    /**
     * Build one serializer for every case; none of them mutate it.
     */
    protected function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();
    }

    /**
     * Serialize a model and read back the order its children actually came out in.
     *
     * @param object $model the CDA model to serialize
     *
     * @return list<string> the serialized root's child element names, in document order
     */
    private function childElementOrder(object $model): array
    {
        $document = new \DOMDocument();
        $document->loadXML($this->service->serializeToXml($model));

        $names = [];
        foreach ($document->documentElement?->childNodes ?? [] as $node) {
            if ($node instanceof \DOMElement) {
                $names[] = $node->nodeName;
            }
        }

        return $names;
    }

    /**
     * The originally reported fault. `templateId` comes from `InfrastructureRoot`, which the content
     * model places first, but reflection reports inherited properties last — so it serialized after
     * every element the act declares itself.
     */
    public function testTemplateIdLeadsAnAustralianClinicalAct(): void
    {
        $order = $this->childElementOrder(new AuSubstanceAdministration(
            classCode: 'SBADM',
            moodCode: 'RQO',
            id: [new II(root: '1.2.36.1.2001.1001.101', extension: 'ITEM-1')],
            statusCode: new CS(code: 'active'),
            templateId: [new II(root: '1.2.36.1.2001.1001.100.102.16211')],
        ));

        self::assertSame(['templateId', 'id', 'statusCode'], $order);
    }

    /**
     * The second reported fault, and a different cause: `completionCode` is an AU extension element in
     * its own XML namespace, so it took the buffered emit path and was folded in after every sibling
     * regardless of position. Its published slot is between `versionNumber` and `copyTime`.
     */
    public function testCompletionCodeSitsAtItsPublishedPositionOnTheDocument(): void
    {
        $order = $this->childElementOrder(new AuClinicalDocument(
            completionCode: new CE(code: 'F'),
            templateId: [new II(root: '1.2.36.1.2001.1001.100.1002.170')],
            id: new II(root: '1.2.36.1.2001.1001.101', extension: 'DOC-1'),
            code: new CE(code: '51852-2'),
            effectiveTime: new TS(value: '20260101'),
            confidentialityCode: new CE(code: 'N'),
            languageCode: new CS(code: 'en-AU'),
            setId: new II(root: '1.2.36.1.2001.1001.102'),
            versionNumber: new INTType(value: 1),
            copyTime: new TS(value: '20260102'),
        ));

        self::assertSame(
            [
                'templateId',
                'id',
                'code',
                'effectiveTime',
                'confidentialityCode',
                'languageCode',
                'setId',
                'versionNumber',
                'completionCode',
                'copyTime',
            ],
            $order,
        );
    }

    /**
     * Two properties can emit under one local name: `Patient.raceCode` in the CDA namespace and
     * `Patient.sdtcRaceCode` in the sdtc one. Buffering exists so neither silently overwrites the
     * other, and reserving a position must not undo that — losing an element would be worse than
     * misplacing one.
     */
    public function testCollidingLocalNamesBothSurviveWithTheirOwnNamespaces(): void
    {
        $xml = $this->service->serializeToXml(new Patient(
            raceCode: new CE(code: '1002-5'),
            sdtcRaceCode: [new CE(code: '2106-3')],
        ));

        $document = new \DOMDocument();
        $document->loadXML($xml);

        $raceCodes = [];
        foreach ($document->documentElement?->childNodes ?? [] as $node) {
            if ($node instanceof \DOMElement && $node->localName === 'raceCode') {
                $raceCodes[$node->namespaceURI ?? ''] = $node->getAttribute('code');
            }
        }

        self::assertSame(
            ['urn:hl7-org:v3' => '1002-5', 'urn:hl7-org:sdtc' => '2106-3'],
            $raceCodes,
            'both raceCode elements must survive, each in its own namespace',
        );
    }

    /**
     * Ordering must not disturb a round trip: reading the reordered document back must still produce
     * an equal model, since a receiver's parser is order-insensitive but ours must agree with it.
     */
    public function testReorderedDocumentStillRoundTrips(): void
    {
        $original = new AuSubstanceAdministration(
            classCode: 'SBADM',
            moodCode: 'RQO',
            id: [new II(root: '1.2.36.1.2001.1001.101', extension: 'ITEM-1')],
            statusCode: new CS(code: 'active'),
            templateId: [new II(root: '1.2.36.1.2001.1001.100.102.16211')],
        );

        $xml = $this->service->serializeToXml($original);

        $decoded = $this->service->deserializeFromXml($xml, AuSubstanceAdministration::class);

        self::assertInstanceOf(AuSubstanceAdministration::class, $decoded);
        self::assertSame('1.2.36.1.2001.1001.100.102.16211', $decoded->templateId[0]->root ?? null);
        self::assertSame('active', $decoded->statusCode?->code);
    }
}
