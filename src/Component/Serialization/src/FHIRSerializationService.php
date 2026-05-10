<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRBackboneElementJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRComplexTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRPrimitiveTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRResourceJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRBackboneElementXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRComplexTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRPrimitiveTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRResourceXmlNormalizer;
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
        private readonly FHIRMetadataExtractorInterface $metadataExtractor = new FHIRMetadataExtractor(),
        private readonly FHIRTypeResolverInterface $typeResolver = new FHIRTypeResolver(),
    ) {
    }

    /**
     * Create a fully-wired serialization service without Symfony DI.
     *
     * Useful in tests or standalone scripts where the Symfony container is not
     * available. Uses a two-phase construction to inject the Serializer back into
     * normalizers that need it for recursive object handling.
     */
    public static function createDefault(FhirVersion $version = FhirVersion::R4B): self
    {
        return self::createWithIG(version: $version);
    }

    /**
     * Create a fully-wired serialization service with IG-aware extension/profile/discriminator resolution.
     *
     * Scans base model Extension directories and an optional user IG output directory,
     * building a FHIRIGTypeRegistry that enables typed extension deserialization,
     * profile URL resolution, and discriminator-based slice resolution.
     *
     * @param string $igOutputDirectory Absolute path to IG output directory (e.g. '/app/src/FHIRIG').
     *                                  Pass an empty string (default) to skip IG scanning.
     * @param string $igNamespace       PSR-4 namespace root for the IG output directory
     *                                  (e.g. 'App\FHIR\IG'). Pass an empty string (default) to skip.
     */
    public static function createWithIG(
        string $igOutputDirectory = '',
        string $igNamespace = '',
        FhirVersion $version = FhirVersion::R4B
    ): self {
        $metadataExtractor = new FHIRMetadataExtractor();
        $registry          = FHIRIGTypeRegistryFactory::create($igOutputDirectory, $igNamespace);
        $typeResolver      = new FHIRTypeResolver(igTypeRegistry: $registry);

        $normalizers = [
            new FHIRResourceJsonNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRResourceXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRComplexTypeJsonNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRComplexTypeXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRPrimitiveTypeJsonNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRPrimitiveTypeXmlNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRBackboneElementJsonNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRBackboneElementXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
        ];

        $serializer = new Serializer($normalizers, [new JsonEncoder(), new XmlEncoder()]);

        return new self($serializer, new FHIRSerializationContextFactory(), new FHIRSerializationDebugInfo('initial', 'json'), $metadataExtractor);
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

            // The FHIR XML root element name must be the resource type (e.g. "Patient").
            // Symfony XmlEncoder uses XmlEncoder::ROOT_NODE_NAME from context.
            $resourceType = $this->extractResourceTypeFromObject($fhirObject);
            if ($resourceType !== null) {
                $xmlContext[XmlEncoder::ROOT_NODE_NAME] = $resourceType;
            }

            return $this->serializer->serialize($fhirObject, 'xml', $xmlContext);
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to serialize FHIR object to XML: %s', $e->getMessage()), 0, $e);
        }
    }

    private function extractResourceTypeFromObject(object $fhirObject): ?string
    {
        return $this->metadataExtractor->extractResourceType($fhirObject);
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
            // Preserve all attribute values as strings so numeric-looking values (e.g. "1.0",
            // "2002") are not cast to float/int, which would lose precision on round-trip.
            $xmlContext[XmlEncoder::TYPE_CAST_ATTRIBUTES] = false;

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
     * Delegates to FHIRTypeResolver so that profile-based resolution (via meta.profile) and
     * the IG type registry are applied when available, in addition to the default resourceType
     * convention lookup.
     */
    private function detectTargetClass(string $data, string $format): string
    {
        /** @var array<string, mixed>|null $decoded */
        $decoded = null;

        if ($format === 'json') {
            $decoded = json_decode($data, true);
            if (!is_array($decoded)) {
                $decoded = null;
            }
        } elseif ($format === 'xml') {
            // Strip DOCTYPE to prevent XXE, then extract the root element name
            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOERROR);
            if ($xml !== false) {
                $decoded = ['resourceType' => $xml->getName()];
            }
        }

        if ($decoded !== null) {
            $resolved = $this->typeResolver->resolveResourceType($decoded);
            if ($resolved !== null) {
                return $resolved;
            }
        }

        throw new FHIRSerializationException('Unable to detect target class from data');
    }
}
