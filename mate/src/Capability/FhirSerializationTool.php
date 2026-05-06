<?php

declare(strict_types=1);

namespace App\Mate\Capability;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Mcp\Capability\Attribute\McpTool;
use Symfony\AI\Mate\Encoding\ResponseEncoder;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * MCP tool for inspecting a deserialized FHIR resource's PHP object structure.
 */
class FhirSerializationTool
{
    public function __construct(
        private readonly FHIRSerializationService $service,
        private readonly FHIRMetadataExtractorInterface $metadataExtractor,
    ) {
    }

    /**
     * @param string      $data        FHIR resource as a JSON or XML string. Format is auto-detected.
     * @param string|null $targetClass Optional fully-qualified PHP class name to deserialize into.
     *                                 When omitted, the class is resolved from the resourceType field.
     * @param bool        $roundTrip   when true, re-serializes the object back to the original format
     *                                 and includes a matchesInput flag to verify round-trip fidelity
     */
    #[McpTool('fhir-deserialize-inspect', 'Deserialize FHIR JSON or XML and return the rehydrated PHP object dumped via Symfony VarDumper, optionally round-tripped back to the source format to verify fidelity.')]
    public function inspect(string $data, ?string $targetClass = null, bool $roundTrip = false): string
    {
        try {
            $object = $this->service->deserialize($data, $targetClass);
        } catch (FHIRSerializationException $e) {
            return ResponseEncoder::encode([
                'error' => $e->getMessage(),
                'class' => $targetClass,
            ]);
        }

        $cloner = new VarCloner();
        $dumper = new CliDumper();
        $dumper->setColors(false);
        $dump = (string) $dumper->dump($cloner->cloneVar($object), true);

        $payload = [
            'class'        => $object::class,
            'resourceType' => $this->metadataExtractor->extractResourceType($object),
            'fhirVersion'  => $this->metadataExtractor->extractFHIRVersion($object),
            'dump'         => $dump,
        ];

        if ($roundTrip) {
            $isXml = str_starts_with(ltrim($data), '<');

            try {
                $reserialized        = $isXml
                    ? $this->service->serializeToXml($object)
                    : $this->service->serializeToJson($object);
                $payload['reserialized'] = $reserialized;
                $payload['matchesInput'] = trim($reserialized) === trim($data);
            } catch (FHIRSerializationException $e) {
                $payload['roundTripError'] = $e->getMessage();
            }
        }

        return ResponseEncoder::encode($payload);
    }
}
