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
 * for a subset of common R4 resource types.
 */
#[Route('/serialization', name: 'app_serialization')]
class SerializationController extends AbstractController
{
    /**
     * Maps display label → fully-qualified class name for common R4 resources.
     *
     * @var array<string, string>
     */
    private const RESOURCE_TYPES = [
        'Patient'              => \Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource::class,
        'Observation'          => \Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource::class,
        'Condition'            => \Ardenexal\FHIRTools\Component\Models\R4\Resource\ConditionResource::class,
        'Encounter'            => \Ardenexal\FHIRTools\Component\Models\R4\Resource\EncounterResource::class,
        'MedicationRequest'    => \Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequestResource::class,
        'Organization'         => \Ardenexal\FHIRTools\Component\Models\R4\Resource\OrganizationResource::class,
        'Practitioner'         => \Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerResource::class,
        'Bundle'               => \Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource::class,
    ];

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
            'resource_types' => array_keys(self::RESOURCE_TYPES),
            'input'          => '',
            'resource_type'  => 'Patient',
            'action'         => 'validate',
            'result'         => null,
            'error'          => null,
        ]);
    }

    /** Handle validate / convert / metadata form submissions. */
    #[Route('/process', name: '_process', methods: ['POST'])]
    public function process(Request $request): Response
    {
        $input        = trim((string) $request->request->get('input', ''));
        $resourceType = (string) $request->request->get('resource_type', 'Patient');
        $action       = (string) $request->request->get('action', 'validate');

        $result = null;
        $error  = null;

        if ($input === '') {
            $error = 'Please paste a FHIR resource in JSON or XML format.';

            return $this->renderForm($input, $resourceType, $action, null, $error);
        }

        $targetClass = self::RESOURCE_TYPES[$resourceType] ?? null;

        if ($targetClass === null) {
            $error = sprintf('Unknown resource type "%s".', $resourceType);

            return $this->renderForm($input, $resourceType, $action, null, $error);
        }

        try {
            $format = $this->detectFormat($input);
            $object = match ($format) {
                'json'  => $this->serializationService->deserializeFromJson($input, $targetClass),
                'xml'   => $this->serializationService->deserializeFromXml($input, $targetClass),
                default => throw new FHIRSerializationException("Unable to detect format (expected JSON or XML)"),
            };

            $result = match ($action) {
                'validate'      => $this->doValidate($object),
                'json_to_xml'   => $this->doConvert($object, 'xml'),
                'xml_to_json'   => $this->doConvert($object, 'json'),
                'show_metadata' => $this->doMetadata($object),
                default         => throw new \InvalidArgumentException(sprintf('Unknown action "%s"', $action)),
            };
        } catch (FHIRSerializationException $e) {
            $error = 'Serialization error: ' . $e->getMessage();
        } catch (\Throwable $e) {
            $error = 'Error: ' . $e->getMessage();
        }

        return $this->renderForm($input, $resourceType, $action, $result, $error);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

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
            'xml'  => $this->serializationService->serializeToXml($object),
            'json' => $this->serializationService->serializeToJson($object),
            default => throw new \InvalidArgumentException("Unsupported target format: {$targetFormat}"),
        };

        return [
            'type'   => 'convert',
            'format' => $targetFormat,
            'output' => $output,
        ];
    }

    /** @return array<string, mixed> */
    private function doMetadata(object $object): array
    {
        return [
            'type'             => 'metadata',
            'class'            => get_class($object),
            'resource_type'    => $this->metadataExtractor->extractResourceType($object),
            'fhir_type'        => $this->metadataExtractor->extractFHIRType($object),
            'fhir_version'     => $this->metadataExtractor->extractFHIRVersion($object),
            'is_resource'      => $this->metadataExtractor->isResource($object),
            'is_complex_type'  => $this->metadataExtractor->isComplexType($object),
            'parent_resource'  => $this->metadataExtractor->extractParentResource($object),
            'element_path'     => $this->metadataExtractor->extractElementPath($object),
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
        string $resourceType,
        string $action,
        ?array $result,
        ?string $error,
    ): Response {
        return $this->render('serialization/index.html.twig', [
            'resource_types' => array_keys(self::RESOURCE_TYPES),
            'input'          => $input,
            'resource_type'  => $resourceType,
            'action'         => $action,
            'result'         => $result,
            'error'          => $error,
        ]);
    }
}
