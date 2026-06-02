<?php

declare(strict_types=1);

namespace App\Controller;

use Ardenexal\FHIRTools\Component\Serialization\FHIRVersionedSerializationServiceLocator;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/validate', name: 'app_validate')]
class ValidationController extends AbstractController
{
    private const MODELS_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models';

    public function __construct(
        private readonly FHIRVersionedSerializationServiceLocator $serializationLocator,
        private readonly FHIRValidationServiceInterface $validation,
    ) {
    }

    #[Route('', name: '', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('validation/index.html.twig', [
            'fhirJson'                => '',
            'fhirVersion'             => 'r4',
            'profileUrls'             => '',
            'mustSupport'             => false,
            'hideExtensibleWarnings'  => false,
            'result'                  => null,
            'error'                   => null,
            'elapsedMs'               => null,
        ]);
    }

    #[Route('/run', name: '_run', methods: ['POST'])]
    public function run(Request $request): Response
    {
        $fhirJson               = trim((string) $request->request->get('fhirJson', ''));
        $fhirVersion            = strtolower(trim((string) $request->request->get('fhirVersion', 'r4')));
        $profileUrls            = (string) $request->request->get('profileUrls', '');
        $mustSupport            = (bool) $request->request->get('mustSupport', false);
        $hideExtensibleWarnings = (bool) $request->request->get('hideExtensibleWarnings', false);

        $result    = null;
        $error     = null;
        $elapsedMs = null;

        $templateVars = static fn (
            ?array $result,
            ?string $error,
            ?float $elapsedMs,
        ) => [
            'fhirJson'               => $fhirJson,
            'fhirVersion'            => $fhirVersion,
            'profileUrls'            => $profileUrls,
            'mustSupport'            => $mustSupport,
            'hideExtensibleWarnings' => $hideExtensibleWarnings,
            'result'                 => $result,
            'error'                  => $error,
            'elapsedMs'              => $elapsedMs,
        ];

        if ($fhirJson === '') {
            return $this->render('validation/index.html.twig', $templateVars(null, 'Please enter FHIR JSON.', null));
        }

        try {
            $decoded = json_decode($fhirJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->render('validation/index.html.twig', $templateVars(null, 'Invalid JSON: ' . $e->getMessage(), null));
        }

        if (!is_array($decoded) || !isset($decoded['resourceType']) || !is_string($decoded['resourceType'])) {
            return $this->render('validation/index.html.twig', $templateVars(null, 'Missing resourceType field.', null));
        }

        $resourceType = $decoded['resourceType'];

        $version = FhirVersion::tryFrom(strtoupper($fhirVersion));
        if ($version === null) {
            return $this->render('validation/index.html.twig', $templateVars(null, sprintf('Unknown FHIR version "%s". Supported: r4, r4b, r5.', $fhirVersion), null));
        }

        $fqcn = sprintf('%s\\%s\\Resource\\%sResource', self::MODELS_BASE_NAMESPACE, $version->value, $resourceType);

        if (!class_exists($fqcn)) {
            return $this->render('validation/index.html.twig', $templateVars(null, sprintf('Unknown resource type \'%s\' for FHIR %s.', $resourceType, $version->value), null));
        }

        try {
            $model = $this->serializationLocator->get($version)->deserialize($fhirJson, $fqcn);
        } catch (\Throwable $e) {
            return $this->render('validation/index.html.twig', $templateVars(null, 'Deserialization failed: ' . $e->getMessage(), null));
        }

        $parsedProfileUrls = array_values(array_filter(array_map('trim', explode("\n", $profileUrls))));

        $start     = microtime(true);
        $report    = $this->validation->validate($model, $parsedProfileUrls, $mustSupport);
        $elapsedMs = round((microtime(true) - $start) * 1000, 1);

        $warnings = $hideExtensibleWarnings
            ? array_values(array_filter($report->warnings(), static fn ($v) => !str_contains($v->constraintClass, 'FHIRValueSetBinding')))
            : $report->warnings();

        $result = [
            'errors'   => $report->errors(),
            'warnings' => $warnings,
            'info'     => $report->info(),
            'isValid'  => $report->isValid(),
        ];

        return $this->render('validation/index.html.twig', $templateVars($result, null, $elapsedMs));
    }
}
