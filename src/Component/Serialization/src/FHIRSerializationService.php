<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

use Symfony\Component\Serializer\SerializerInterface;
use Ardenexal\FHIRTools\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;

/**
 * High-level FHIR serialization service providing convenient methods for FHIR data conversion.
 *
 * This service provides a simplified interface for serializing and deserializing FHIR objects
 * with appropriate defaults and error handling.
 *
 * @author Kiro AI Assistant
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
     */
    private function detectTargetClass(string $data, string $format): string
    {
        if ($format === 'json') {
            $decoded = json_decode($data, true);
            if (is_array($decoded) && isset($decoded['resourceType'])) {
                $resourceType = $decoded['resourceType'];

                // This would need to be configured with actual class mappings
                return "Ardenexal\\FHIRTools\\Generated\\FHIR\\{$resourceType}";
            }
        } elseif ($format === 'xml') {
            // Parse XML to extract root element name
            $xml = simplexml_load_string($data);
            if ($xml !== false) {
                $rootName = $xml->getName();

                return "Ardenexal\\FHIRTools\\Generated\\FHIR\\{$rootName}";
            }
        }

        throw new FHIRSerializationException('Unable to detect target class from data');
    }
}
