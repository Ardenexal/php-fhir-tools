<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistryFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRConformanceViolationException;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRUnreadableDocumentException;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRBackboneElementJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractOperationPayloadNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRLogicalModelJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRComplexTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIROperationPayloadJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRPrimitiveTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRResourceJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRBackboneElementXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRComplexTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRLogicalModelXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIROperationPayloadXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRPrimitiveTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRResourceXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Xml\XmlDoctypeGuard;
use Ardenexal\FHIRTools\Component\Serialization\Xml\XmlNamespacePrefixResolver;
use Seld\JsonLint\JsonParser;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\LogicalModelLocatorTrait;

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
    use LogicalModelLocatorTrait;

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly FHIRSerializationContextFactory $contextFactory,
        private readonly FHIRSerializationDebugInfo $debugInfo,
        private readonly FHIRMetadataExtractorInterface $metadataExtractor = new FHIRMetadataExtractor(),
        private readonly FHIRTypeResolverInterface $typeResolver = new FHIRTypeResolver(),
        private readonly XmlNamespacePrefixResolver $namespacePrefixResolver = new XmlNamespacePrefixResolver(),
    ) {
    }

    /**
     * Create a fully-wired serialization service without Symfony DI.
     *
     * Useful in tests or standalone scripts where the Symfony container is not
     * available. Uses a two-phase construction to inject the Serializer back into
     * normalizers that need it for recursive object handling.
     */
    public static function createDefault(FhirVersion $version = FhirVersion::R4): self
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
        FhirVersion $version = FhirVersion::R4
    ): self {
        $metadataExtractor = new FHIRMetadataExtractor();
        $registry          = FHIRIGTypeRegistryFactory::create($igOutputDirectory, $igNamespace);
        $typeResolver      = new FHIRTypeResolver(igTypeRegistry: $registry, fhirVersion: $version->value);

        $normalizers = [
            // Operation payloads first. They carry no #[FhirResource], so nothing else in this chain
            // claims them and a generic normalizer would accept one and map a `Parameters` body onto
            // constructor arguments it has no keys for — producing an object with every property
            // null, silently.
            new FHIROperationPayloadJsonNormalizer($metadataExtractor, $typeResolver, version: $version->value, igTypeRegistry: $registry),
            new FHIROperationPayloadXmlNormalizer($metadataExtractor, $typeResolver, version: $version->value, igTypeRegistry: $registry),
            // CDA logical models are XML-only; this guard rejects JSON (de)serialization with a
            // descriptive error before the complex-type JSON normalizers see the object.
            new FHIRLogicalModelJsonNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRResourceJsonNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRResourceXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRComplexTypeJsonNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRLogicalModelXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRComplexTypeXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRPrimitiveTypeJsonNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRPrimitiveTypeXmlNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRBackboneElementJsonNormalizer($metadataExtractor, fhirVersion: $version->value, igTypeRegistry: $registry),
            new FHIRBackboneElementXmlNormalizer($metadataExtractor, $typeResolver, fhirVersion: $version->value, igTypeRegistry: $registry),
        ];

        $serializer = new Serializer($normalizers, [new JsonEncoder(), new XmlEncoder()]);

        return new self($serializer, new FHIRSerializationContextFactory(), new FHIRSerializationDebugInfo('initial', 'json'), $metadataExtractor, $typeResolver);
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

            // The FHIR XML root element name must be the resource type (e.g. "Patient"), or for CDA
            // logical models the model name (e.g. "ClinicalDocument").
            // Symfony XmlEncoder uses XmlEncoder::ROOT_NODE_NAME from context.
            $rootName = $this->extractResourceTypeFromObject($fhirObject)
                ?? $this->extractLogicalModelName($fhirObject);
            if ($rootName !== null) {
                $xmlContext[XmlEncoder::ROOT_NODE_NAME] = $rootName;
            }

            return $this->serializer->serialize($fhirObject, 'xml', $xmlContext);
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to serialize FHIR object to XML: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * The XML root element name for an object about to be serialized.
     *
     * FHIR requires the root element to be the resource type. `XmlEncoder` otherwise falls back to
     * its own default (`<response>`), which is not valid FHIR — and silently so, because the element
     * *contents* are correct.
     *
     * Operation payloads need explicit handling: they carry no `#[FhirResource]`, so the metadata
     * extractor rightly reports no resource type for them. What actually reaches the encoder is the
     * `Parameters` resource the payload normalizer maps them to, so that is the root name. Reading
     * the object here rather than the emitted document is what makes this a lookup instead of a
     * second serialization pass.
     */
    private function extractResourceTypeFromObject(object $fhirObject): ?string
    {
        $resourceType = $this->metadataExtractor->extractResourceType($fhirObject);

        if ($resourceType !== null) {
            return $resourceType;
        }

        // A profiled `Parameters` is still `Parameters` on the wire, so the literal is safe here.
        return AbstractOperationPayloadNormalizer::isOperationPayload($fhirObject) ? 'Parameters' : null;
    }

    /**
     * Resolve the XML root element name for a CDA logical-model object from its (or an ancestor's)
     * #[LogicalModel] attribute. Returns null for non-logical-model objects.
     */
    private function extractLogicalModelName(object $fhirObject): ?string
    {
        return $this->findLogicalModelAttribute($fhirObject)?->name;
    }

    /**
     * Deserialize JSON data to a FHIR object.
     *
     * @template T of object
     *
     * @param string               $jsonData    The JSON data to deserialize
     * @param class-string<T>      $targetClass The target FHIR class name
     * @param array<string, mixed> $context     Additional deserialization context
     *
     * @return T
     *
     * @throws FHIRSerializationException If deserialization fails
     */
    public function deserializeFromJson(string $jsonData, string $targetClass, array $context = []): object
    {
        try {
            $jsonContext = $this->contextFactory->createJsonContext($context);

            // json_decode() rejects a leading BOM with a bare "Syntax error", so it is stripped here
            // rather than only in detectFormat() — this method is a public entry point in its own right.
            $result = $this->serializer->deserialize(
                $this->stripByteOrderMark($jsonData),
                $targetClass,
                'json',
                $jsonContext,
            );

            if (!is_object($result)) {
                throw new FHIRSerializationException('Deserialization did not produce an object');
            }

            /** @var T $result */
            return $result;
        } catch (FHIRConformanceViolationException|FHIRUnreadableDocumentException $e) {
            // Pass both through unwrapped. Each already states its own reason in the reference
            // validator's wording, and wrapping would bury that behind a generic prefix *and* replace
            // the exception type — leaving nothing able to tell "this document breaks a FHIR rule"
            // from "these bytes are not a document" from "we could not map it to a model". The
            // conformance oracle reports the first two as findings and only the third as UNREAD, so
            // both distinctions have to survive the catch.
            throw $e;
        } catch (\Exception $e) {
            throw new FHIRSerializationException(sprintf('Failed to deserialize JSON to FHIR object: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * Deserialize XML data to a FHIR object.
     *
     * @template T of object
     *
     * @param string               $xmlData     The XML data to deserialize
     * @param class-string<T>      $targetClass The target FHIR class name
     * @param array<string, mixed> $context     Additional deserialization context
     *
     * @return T
     *
     * @throws FHIRSerializationException If deserialization fails
     */
    public function deserializeFromXml(string $xmlData, string $targetClass, array $context = []): object
    {
        try {
            // Two preconditions, in this order. The DOCTYPE scan is a byte comparison, so it reads a
            // UTF-16 document as having no DOCTYPE at all and waves an XXE payload straight through to
            // libxml; the encoding must therefore be settled before the scan can be trusted.
            // deserialize() gets the encoding check for free from detectFormat(), which cannot make
            // sense of byte pairs — this is a public entry point in its own right and skips that.
            XmlDoctypeGuard::assertUtf8($xmlData);
            XmlDoctypeGuard::assertNoDoctype($xmlData);

            $xmlContext = $this->contextFactory->createXmlContext($context);

            // Ignore the DOCTYPE node type as well, for defence in depth on any path that reaches
            // the decoder without passing the guard above.
            // This must ADD to the ignored-node list, never replace it: XmlEncoder reads the key with
            // `$context[...] ?? $this->defaultContext[...]`, so assigning outright discards Symfony's
            // default of [XML_PI_NODE, XML_COMMENT_NODE]. Comment nodes then stop being ignored, and a
            // document with a leading comment decodes to that comment's text as a bare string, which no
            // normalizer claims — surfacing as the misleading "no supporting normalizer found".
            // A caller may extend the list (or opt back into decoding comments/PIs) via $context, but
            // XML_DOCUMENT_TYPE_NODE is a security floor and is always re-added.
            $callerIgnored = $context[XmlEncoder::DECODER_IGNORED_NODE_TYPES] ?? [\XML_PI_NODE, \XML_COMMENT_NODE];

            $xmlContext[XmlEncoder::DECODER_IGNORED_NODE_TYPES] = array_values(array_unique(
                [...(is_array($callerIgnored) ? $callerIgnored : []), \XML_DOCUMENT_TYPE_NODE],
            ));
            // Preserve all attribute values as strings so numeric-looking values (e.g. "1.0",
            // "2002") are not cast to float/int, which would lose precision on round-trip.
            $xmlContext[XmlEncoder::TYPE_CAST_ATTRIBUTES] = false;

            // Stash the source document element so the denormalizer can recover document order for
            // transparent xml-choice-group properties — Symfony's XmlEncoder decode regroups
            // same-named siblings and loses the interleaving (CDA M7). LIBXML_NONET disables network
            // access; DOCTYPE entities are not expanded. The denormalizer threads the element down to
            // each complex child, so a choice group nested at any depth recovers its order.
            $sourceDocument = new \DOMDocument();
            if (@$sourceDocument->loadXML($xmlData, \LIBXML_NONET) && $sourceDocument->documentElement !== null) {
                $xmlContext[FHIRComplexTypeXmlNormalizer::SOURCE_ELEMENT_CONTEXT_KEY] = $sourceDocument->documentElement;
            }

            // Resolve prefixed element names to local names while the DOM's namespace scoping still
            // exists — XmlEncoder::decode() destroys child-declared prefix bindings, after which
            // `<f:status>` can no longer be told apart from an element in a foreign namespace.
            $result = $this->serializer->deserialize(
                $this->namespacePrefixResolver->resolve($this->stripByteOrderMark($xmlData)),
                $targetClass,
                'xml',
                $xmlContext,
            );

            if (!is_object($result)) {
                throw new FHIRSerializationException('Deserialization did not produce an object');
            }

            /** @var T $result */
            return $result;
        } catch (FHIRConformanceViolationException|FHIRUnreadableDocumentException $e) {
            // See deserializeFromJson(): both must keep their type and their message.
            throw $e;
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
        // Strip once, up front: detectFormat(), detectTargetClass() and the delegated deserialize call
        // must all see the same bytes. detectTargetClass() json_decode()s the payload, which returns
        // null on a BOM and surfaces as "Unable to detect target class from data" — a second, distinct
        // failure from the detectFormat() one, and the reason a fix confined to detectFormat() is not
        // enough.
        $data = $this->stripByteOrderMark($data);

        // Auto-detect format
        $format = $this->detectFormat($data);

        // Auto-detect target class if not provided
        if ($targetClass === null) {
            $targetClass = $this->detectTargetClass($data, $format);
        }

        return match ($format) {
            /** @phpstan-ignore argument.type, argument.templateType */
            'json'  => $this->deserializeFromJson($data, $targetClass, $context),
            /** @phpstan-ignore argument.type, argument.templateType */
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
        $trimmed = trim($this->stripByteOrderMark($data));

        if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
            return 'json';
        }

        if (str_starts_with($trimmed, '<')) {
            return 'xml';
        }

        // Neither shape matched. Show what it actually started with — "Unable to detect data format"
        // alone is unactionable, and the common real causes (a leading JSON comment, an HTML error
        // page returned by a server, a stray log line) are all obvious the moment the bytes are shown.
        if ($trimmed === '') {
            throw FHIRUnreadableDocumentException::because('Unable to detect data format: input is empty');
        }

        throw FHIRUnreadableDocumentException::because(sprintf('Unable to detect data format: expected JSON (starting "{" or "[") or XML (starting "<"), got %s', var_export(mb_strimwidth($trimmed, 0, 40, '…'), true)));
    }

    /**
     * Remove a leading UTF-8 byte order mark.
     *
     * A BOM is legal at the start of a UTF-8 document and real-world FHIR payloads carry one — 15 files
     * in the vendored test corpus do. It broke two separate layers here:
     *
     *  - `trim()`'s default charlist is " \t\n\r\0\x0B", which does NOT include `EF BB BF`, so
     *    `detectFormat()` saw neither `{`, `[` nor `<` and threw "Unable to detect data format".
     *  - `json_decode()` rejects a leading BOM outright with a syntax error, so stripping it for
     *    detection alone would simply move the failure one layer down.
     *
     * XML is unaffected either way — libxml consumes the BOM itself — but the strip is applied
     * uniformly so every entry point sees the same bytes.
     *
     * Only the UTF-8 BOM is handled. FHIR mandates UTF-8, so a UTF-16/32 BOM signals a payload that is
     * not decodable as FHIR at all, and silently discarding those bytes would turn a clear encoding
     * error into a confusing parse error further in.
     */
    private function stripByteOrderMark(string $data): string
    {
        return str_starts_with($data, "\xEF\xBB\xBF") ? substr($data, 3) : $data;
    }

    /**
     * Describe why a JSON payload failed to parse, with a position.
     *
     * `json_last_error_msg()` returns a bare "Syntax error" carrying no line, no column and no
     * context, which is not enough to locate the problem in a large Bundle or to attach a validation
     * violation to an element. seld/jsonlint reports the line, a caret column and the expected tokens.
     * It is a hard dependency of this package, but the lint result is still treated as best-effort:
     * a linter that finds nothing must not turn a real parse failure into an empty message.
     */
    private function describeJsonParseFailure(string $data): string
    {
        $fallback = json_last_error_msg();

        try {
            $lintError = (new JsonParser())->lint($data);
        } catch (\Throwable) {
            // The linter is a diagnostic aid; never let it replace the failure it was asked to explain.
            return $fallback;
        }

        $message = $lintError?->getMessage();

        return $message === null || trim($message) === '' ? $fallback : $message;
    }

    /**
     * Describe why an XML payload failed to parse, with a position.
     *
     * Deliberately no extra dependency: libxml already produces messages and line/column pairs that
     * match the HL7 Java reference validator's own output closely (e.g. "Entity 'reg' not defined"
     * at line 6 column 916, against Java's "The entity "reg" was referenced, but not declared."
     * at [6,916]). The errors were simply being discarded rather than reported.
     */
    private function describeXmlParseFailure(string $data): string
    {
        $previousErrorState = libxml_use_internal_errors(true);
        libxml_clear_errors();

        try {
            $document = new \DOMDocument();
            $document->loadXML($data, \LIBXML_NONET);
            $errors = libxml_get_errors();
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrorState);
        }

        $first = $errors[0] ?? null;
        if ($first === null) {
            return 'malformed XML';
        }

        return sprintf('%s at line %d column %d', trim($first->message), $first->line, $first->column);
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

            // A parse failure here is not "we could not identify the resource" — it is "this is not
            // JSON", and reporting the former discards the only diagnostic the caller can act on.
            // The encoder's detailed errors never reach this path, because deserialize() resolves the
            // target class before handing anything to the serializer.
            if (json_last_error() !== \JSON_ERROR_NONE) {
                throw FHIRUnreadableDocumentException::because(sprintf('Unable to parse JSON: %s', $this->describeJsonParseFailure($data)));
            }

            if (!is_array($decoded)) {
                $decoded = null;
            }
        } elseif ($format === 'xml') {
            // Reject a DOCTYPE before libxml sees it. LIBXML_NONET alone does not prevent an
            // external entity's system identifier from being resolved — see XmlDoctypeGuard.
            XmlDoctypeGuard::assertNoDoctype($data);

            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOERROR);
            if ($xml === false) {
                throw FHIRUnreadableDocumentException::because(sprintf('Unable to parse XML: %s', $this->describeXmlParseFailure($data)));
            }

            $decoded = ['resourceType' => $xml->getName()];
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
