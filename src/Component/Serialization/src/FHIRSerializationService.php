<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRBackboneElementNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRComplexTypeNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRPrimitiveTypeNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRResourceNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * High-level FHIR serialization service providing convenient methods for FHIR data conversion.
 *
 * This service provides a simplified interface for serializing and deserializing FHIR objects
 * with appropriate defaults and error handling.
 *
 * @author Ardenexal
 */
class FHIRSerializationService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly FHIRSerializationContextFactory $contextFactory,
        private readonly FHIRSerializationDebugInfo $debugInfo,
    ) {
    }

    /**
     * Create a fully-wired serialization service without Symfony DI.
     *
     * Useful in tests or standalone scripts where the Symfony container is not
     * available. Uses a two-phase construction to inject the Serializer back into
     * normalizers that need it for recursive object handling.
     */
    public static function createDefault(): self
    {
        $metadataExtractor = new FHIRMetadataExtractor();
        $typeResolver      = new FHIRTypeResolver();

        // Phase 1: create normalizers without an inner serializer reference
        $resourceNormalizer  = new FHIRResourceNormalizer($metadataExtractor, $typeResolver);
        $complexNormalizer   = new FHIRComplexTypeNormalizer($metadataExtractor, $typeResolver);
        $primitiveNormalizer = new FHIRPrimitiveTypeNormalizer($metadataExtractor);
        $backboneNormalizer  = new FHIRBackboneElementNormalizer($metadataExtractor);

        // Phase 2: create the Serializer so we have a concrete instance to inject
        $serializer = new Serializer(
            [$resourceNormalizer, $complexNormalizer, $primitiveNormalizer, $backboneNormalizer],
            [new JsonEncoder(), new XmlEncoder()]
        );

        // Phase 3: re-create normalizers with the Serializer wired in for recursive calls
        $resourceNormalizer  = new FHIRResourceNormalizer($metadataExtractor, $typeResolver, $serializer, $serializer);
        $complexNormalizer   = new FHIRComplexTypeNormalizer($metadataExtractor, $typeResolver, $serializer, $serializer);
        $primitiveNormalizer = new FHIRPrimitiveTypeNormalizer($metadataExtractor, $serializer, $serializer);
        $backboneNormalizer  = new FHIRBackboneElementNormalizer($metadataExtractor, $serializer, $serializer);

        // Phase 4: build the final, properly-wired Serializer
        $serializer = new Serializer(
            [$resourceNormalizer, $complexNormalizer, $primitiveNormalizer, $backboneNormalizer],
            [new JsonEncoder(), new XmlEncoder()]
        );

        return new self($serializer, new FHIRSerializationContextFactory(), new FHIRSerializationDebugInfo('initial', 'json'));
    }

    /**
     * Serialize a FHIR object to JSON format.
     *
     * @param object               $fhirObject The FHIR object to serialize
     * @param array<string, mixed> $context    Additional serialization context
     *
     * @throws FHIRSerializationException If serialization fails
     */
    public function serializeToJson(object $fhirObject, array $context = []): string
    {
        try {
            $jsonContext = $this->contextFactory->createJsonContext($context);

            return $this->serializer->serialize($fhirObject, 'json', $jsonContext);
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to serialize FHIR object to JSON: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * Serialize a FHIR object to XML format.
     *
     * @param object               $fhirObject The FHIR object to serialize
     * @param array<string, mixed> $context    Additional serialization context
     *
     * @throws FHIRSerializationException If serialization fails
     */
    public function serializeToXml(object $fhirObject, array $context = []): string
    {
        try {
            $xmlContext = $this->contextFactory->createXmlContext($context);

            return $this->serializer->serialize($fhirObject, 'xml', $xmlContext);
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to serialize FHIR object to XML: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * Deserialize JSON data to a FHIR object.
     *
     * @param string               $jsonData    The JSON data to deserialize
     * @param string               $targetClass The target FHIR class name
     * @param array<string, mixed> $context     Additional deserialization context
     *
     * @throws FHIRSerializationException If deserialization fails
     */
    public function deserializeFromJson(string $jsonData, string $targetClass, array $context = []): object
    {
        try {
            $jsonContext = $this->contextFactory->createJsonContext($context);

            $result = $this->serializer->deserialize($jsonData, $targetClass, 'json', $jsonContext);

            if (!is_object($result)) {
                throw new FHIRSerializationException('Deserialization did not produce an object');
            }

            return $result;
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to deserialize JSON to FHIR object: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * Deserialize XML data to a FHIR object.
     *
     * @param string               $xmlData     The XML data to deserialize
     * @param string               $targetClass The target FHIR class name
     * @param array<string, mixed> $context     Additional deserialization context
     *
     * @throws FHIRSerializationException If deserialization fails
     */
    public function deserializeFromXml(string $xmlData, string $targetClass, array $context = []): object
    {
        try {
            $xmlContext = $this->contextFactory->createXmlContext($context);

            // Strip DOCTYPE declarations to prevent XXE entity definitions from being processed
            $xmlContext[XmlEncoder::DECODER_IGNORED_NODE_TYPES] = [\XML_DOCUMENT_TYPE_NODE];

            $result = $this->serializer->deserialize($xmlData, $targetClass, 'xml', $xmlContext);

            if (!is_object($result)) {
                throw new FHIRSerializationException('Deserialization did not produce an object');
            }

            return $result;
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to deserialize XML to FHIR object: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * Auto-detect and deserialize FHIR data from JSON or XML.
     *
     * @param string               $data        The data to deserialize
     * @param string|null          $targetClass Optional target class (will be auto-detected if null)
     * @param array<string, mixed> $context     Additional deserialization context
     *
     * @throws FHIRSerializationException If deserialization fails
     */
    public function deserialize(string $data, ?string $targetClass = null, array $context = []): object
    {
        // Auto-detect format
        $format = $this->detectFormat($data);

        // Auto-detect target class if not provided
        if ($targetClass === null) {
            $targetClass = $this->detectTargetClass($data, $format);
        }

        return match ($format) {
            'json'  => $this->deserializeFromJson($data, $targetClass, $context),
            'xml'   => $this->deserializeFromXml($data, $targetClass, $context),
            default => throw new FHIRSerializationException("Unsupported format: {$format}")
        };
    }

    /**
     * Perform a round-trip serialization test (serialize then deserialize).
     *
     * @param object               $fhirObject The FHIR object to test
     * @param string               $format     The format to test ('json' or 'xml')
     * @param array<string, mixed> $context    Additional context
     *
     * @throws FHIRSerializationException If round-trip fails
     */
    public function roundTripTest(object $fhirObject, string $format = 'json', array $context = []): object
    {
        $originalClass = get_class($fhirObject);

        // Serialize
        $serialized = match ($format) {
            'json'  => $this->serializeToJson($fhirObject, $context),
            'xml'   => $this->serializeToXml($fhirObject, $context),
            default => throw new FHIRSerializationException("Unsupported format: {$format}")
        };

        // Deserialize
        $deserialized = match ($format) {
            'json'  => $this->deserializeFromJson($serialized, $originalClass, $context),
            'xml'   => $this->deserializeFromXml($serialized, $originalClass, $context),
            default => throw new FHIRSerializationException("Unsupported format: {$format}")
        };

        return $deserialized;
    }

    /**
     * Get serialization debug information for the last operation.
     *
     * @return array<string, mixed>
     */
    public function getDebugInfo(): array
    {
        return $this->debugInfo->getDebugInfo();
    }

    /**
     * Detect the format of the input data.
     */
    private function detectFormat(string $data): string
    {
        $trimmed = trim($data);

        if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
            return 'json';
        }

        if (str_starts_with($trimmed, '<')) {
            return 'xml';
        }

        throw new FHIRSerializationException('Unable to detect data format');
    }

    /**
     * Detect the target class from the data content.
     *
     * Tries Models convention (Ardenexal\FHIRTools\Component\Models\{Version}\Resource\{Type}Resource)
     * across all supported FHIR versions before giving up.
     */
    private function detectTargetClass(string $data, string $format): string
    {
        $resourceType = null;

        if ($format === 'json') {
            $decoded = json_decode($data, true);
            if (is_array($decoded) && isset($decoded['resourceType']) && is_string($decoded['resourceType'])) {
                $resourceType = $decoded['resourceType'];
            }
        } elseif ($format === 'xml') {
            // Strip DOCTYPE to prevent XXE, then extract the root element name
            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOERROR);
            if ($xml !== false) {
                $resourceType = $xml->getName();
            }
        }

        if ($resourceType !== null) {
            foreach (['R4', 'R4B', 'R5'] as $version) {
                $candidate = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource\\{$resourceType}Resource";
                if (class_exists($candidate)) {
                    return $candidate;
                }
            }
        }

        throw new FHIRSerializationException('Unable to detect target class from data');
    }
}
