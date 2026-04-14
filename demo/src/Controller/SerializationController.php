<?php

declare(strict_types=1);

namespace App\Controller;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Interactive FHIR serialization and validation playground.
 *
 * Supports JSON/XML conversion, FHIR structure validation, and metadata inspection
 * for any generated FHIR resource type across all versions (R4, R4B, R5) and IGs.
 * The resource type is auto-detected from the input's resourceType field (JSON) or
 * root element name (XML); a version selector can pin detection to a specific version.
 */
#[Route('/serialization', name: 'app_serialization')]
class SerializationController extends AbstractController
{
    /**
     * Available FHIR version constraints for the version selector.
     * 'Auto' probes R4 → R4B → R5 in order; specific versions restrict detection.
     *
     * @var list<string>
     */
    private const FHIR_VERSIONS = ['Auto', 'R4', 'R4B', 'R5'];

    /**
     * Base namespace root for canonical FHIR model classes.
     */
    private const MODELS_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models';

    public function __construct(
        private readonly FHIRSerializationService $serializationService,
        private readonly FHIRValidator $validator,
        private readonly FHIRMetadataExtractorInterface $metadataExtractor,
    ) {
    }

    /** Render the serialization playground form. */
    #[Route('', name: '', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('serialization/index.html.twig', [
            'fhir_versions' => self::FHIR_VERSIONS,
            'input'         => '',
            'fhir_version'  => 'Auto',
            'action'        => 'validate',
            'result'        => null,
            'error'         => null,
        ]);
    }

    /** Handle validate / convert / metadata form submissions. */
    #[Route('/process', name: '_process', methods: ['POST'])]
    public function process(Request $request): Response
    {
        $input       = trim((string) $request->request->get('input', ''));
        $fhirVersion = (string) $request->request->get('fhir_version', 'Auto');
        $action      = (string) $request->request->get('action', 'validate');

        $result = null;
        $error  = null;

        if ($input === '') {
            $error = 'Please paste a FHIR resource in JSON or XML format.';

            return $this->renderForm($input, $fhirVersion, $action, null, $error);
        }

        if (!in_array($fhirVersion, self::FHIR_VERSIONS, true)) {
            $fhirVersion = 'Auto';
        }

        try {
            $object = $this->deserialize($input, $fhirVersion);

            $result = match ($action) {
                'validate'       => $this->doValidate($object),
                'json_to_xml'    => $this->doConvert($object, 'xml'),
                'xml_to_json'    => $this->doConvert($object, 'json'),
                'show_metadata'  => $this->doMetadata($object),
                'dump_structure' => $this->doDumpStructure($object),
                default          => throw new \InvalidArgumentException(sprintf('Unknown action "%s"', $action)),
            };
        } catch (FHIRSerializationException $e) {
            $error = 'Serialization error: ' . $e->getMessage();
        } catch (\Throwable $e) {
            $error = 'Error: ' . $e->getMessage();
        }

        return $this->renderForm($input, $fhirVersion, $action, $result, $error);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Deserialize the input, optionally constraining to a specific FHIR version.
     *
     * When $fhirVersion is 'Auto' the serialization service probes R4 → R4B → R5 and
     * also checks IG profile URLs from meta.profile. When a specific version is given,
     * the resourceType is extracted from the input and a concrete class is built.
     *
     * @throws FHIRSerializationException|\RuntimeException
     */
    private function deserialize(string $input, string $fhirVersion): object
    {
        if ($fhirVersion === 'Auto') {
            return $this->serializationService->deserialize($input);
        }

        $format           = $this->detectFormat($input);
        $resourceTypeName = $this->extractResourceTypeFromInput($input, $format);
        $targetClass      = sprintf('%s\\%s\\Resource\\%sResource', self::MODELS_BASE_NAMESPACE, $fhirVersion, $resourceTypeName);

        if (!class_exists($targetClass)) {
            throw new \RuntimeException(sprintf('No %s class found for resource type "%s". Try using Auto detection.', $fhirVersion, $resourceTypeName));
        }

        return $this->serializationService->deserialize($input, $targetClass);
    }

    /**
     * Extract the FHIR resource type name from a JSON or XML input string.
     * For JSON reads the top-level "resourceType" key; for XML reads the root element name.
     *
     * @throws FHIRSerializationException
     */
    private function extractResourceTypeFromInput(string $input, string $format): string
    {
        if ($format === 'json') {
            /** @var array<string, mixed>|null $decoded */
            $decoded      = json_decode($input, true);
            $resourceType = is_array($decoded) ? ($decoded['resourceType'] ?? null) : null;

            if (!is_string($resourceType) || $resourceType === '') {
                throw new FHIRSerializationException('JSON input is missing the "resourceType" field.');
            }

            return $resourceType;
        }

        $xml = @simplexml_load_string($input);

        if ($xml === false) {
            throw new FHIRSerializationException('Cannot parse XML to determine resource type.');
        }

        return $xml->getName();
    }

    /** @return array<string, mixed> */
    private function doValidate(object $object): array
    {
        $errors = $this->validator->validate($object);

        return [
            'type'   => 'validate',
            'valid'  => empty($errors),
            'errors' => $errors,
        ];
    }

    /** @return array<string, mixed> */
    private function doConvert(object $object, string $targetFormat): array
    {
        $output = match ($targetFormat) {
            'xml'   => $this->serializationService->serializeToXml($object),
            'json'  => $this->serializationService->serializeToJson($object),
            default => throw new \InvalidArgumentException("Unsupported target format: {$targetFormat}"),
        };

        $output = $this->prettyPrint($output, $targetFormat);

        return [
            'type'   => 'convert',
            'format' => $targetFormat,
            'output' => $output,
        ];
    }

    /** Pretty-print a JSON or XML string; returns original on failure. */
    private function prettyPrint(string $output, string $format): string
    {
        if ($format === 'json') {
            $decoded = json_decode($output);

            if (json_last_error() === JSON_ERROR_NONE) {
                $pretty = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                if ($pretty !== false) {
                    return $pretty;
                }
            }

            return $output;
        }

        if ($format === 'xml') {
            $dom                     = new \DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput       = true;

            if ($dom->loadXML($output)) {
                $pretty = $dom->saveXML();

                if ($pretty !== false) {
                    return $pretty;
                }
            }

            return $output;
        }

        return $output;
    }

    /** @return array<string, mixed> */
    private function doMetadata(object $object): array
    {
        return [
            'type'            => 'metadata',
            'class'           => get_class($object),
            'resource_type'   => $this->metadataExtractor->extractResourceType($object),
            'fhir_type'       => $this->metadataExtractor->extractFHIRType($object),
            'fhir_version'    => $this->metadataExtractor->extractFHIRVersion($object),
            'is_resource'     => $this->metadataExtractor->isResource($object),
            'is_complex_type' => $this->metadataExtractor->isComplexType($object),
            'parent_resource' => $this->metadataExtractor->extractParentResource($object),
            'element_path'    => $this->metadataExtractor->extractElementPath($object),
        ];
    }

    /** @return array<string, mixed> */
    private function doDumpStructure(object $object): array
    {
        return [
            'type'   => 'dump_structure',
            'object' => $object,
        ];
    }

    private function detectFormat(string $input): string
    {
        $trimmed = ltrim($input);

        if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
            return 'json';
        }

        if (str_starts_with($trimmed, '<')) {
            return 'xml';
        }

        throw new FHIRSerializationException('Cannot detect format — input must start with { (JSON) or < (XML).');
    }

    private function renderForm(
        string $input,
        string $fhirVersion,
        string $action,
        ?array $result,
        ?string $error,
    ): Response {
        return $this->render('serialization/index.html.twig', [
            'fhir_versions' => self::FHIR_VERSIONS,
            'input'         => $input,
            'fhir_version'  => $fhirVersion,
            'action'        => $action,
            'result'        => $result,
            'error'         => $error,
        ]);
    }
}
