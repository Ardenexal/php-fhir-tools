<?php

declare(strict_types=1);

namespace App\Controller;

use App\Sdc\ExternalClientFactory;
use App\Sdc\QuestionnaireFormRenderer;
use App\Sdc\QuestionnaireResponseBuilder;
use Ardenexal\FHIRTools\Component\HttpClient\NullFHIRHttpClient;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Sdc\Contract\ExtractServiceInterface;
use Ardenexal\FHIRTools\Component\Sdc\Contract\PopulateServiceInterface;
use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Sdc\XFhirQueryPopulationDataProvider;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FHIRVersionedSerializationServiceLocator;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The real SDC demo page: pick (or paste) a Questionnaire, fill in its form, Populate it from pasted
 * launch-context JSON, and Extract the answers into a transaction Bundle. Replaces the M01 throwaway
 * spike (`SdcSpikeController`).
 *
 * The loaded Questionnaire's raw JSON travels between requests as a hidden form field
 * (`questionnaireJson`) rather than server-side session state — each POST is self-contained.
 */
#[Route('/sdc', name: 'app_sdc')]
class SdcController extends AbstractController
{
    private const string MODELS_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models';

    private const string LAUNCH_CONTEXT_URL     = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext';

    public function __construct(
        private readonly PopulateServiceInterface $populateService,
        private readonly ExtractServiceInterface $extractService,
        private readonly FHIRVersionedSerializationServiceLocator $serializationLocator,
        private readonly FHIRHttpClientInterface $httpClient,
        private readonly FHIRTerminologyClientInterface $terminologyClient,
        private readonly RequestStack $requestStack,
        private readonly string $oauthTokenUrl = '',
        private readonly string $oauthClientIdEnv = '',
        private readonly string $authHeaderName = '',
        private readonly bool $authHeaderValueSetViaEnv = false,
        private readonly string $samplesDir = __DIR__ . '/../../assets/sdc-samples',
    ) {
    }

    #[Route('', name: '', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('sdc/index.html.twig', $this->baseVars());
    }

    #[Route('/render', name: '_render', methods: ['POST'])]
    public function renderQuestionnaire(Request $request): Response
    {
        $vars = $this->baseVars();

        try {
            $questionnaireJson          = $this->resolveQuestionnaireJson($request);
            $questionnaire              = $this->decodeQuestionnaire($questionnaireJson);
            $vars['questionnaireJson']  = $questionnaireJson;
            $vars['questionnaireTitle'] = (string) ($questionnaire['title'] ?? $questionnaire['name'] ?? $questionnaire['id'] ?? 'Questionnaire');
            $vars['fields']             = (new QuestionnaireFormRenderer())->renderBlank($questionnaire['item'] ?? []);
        } catch (\Throwable $e) {
            $vars['error'] = $e->getMessage();
        }

        return $this->render('sdc/index.html.twig', $vars);
    }

    #[Route('/populate', name: '_populate', methods: ['POST'])]
    public function populate(Request $request): Response
    {
        $vars                      = $this->baseVars();
        $questionnaireJson         = (string) $request->request->get('questionnaireJson', '');
        $launchContextJson         = trim((string) $request->request->get('launchContextJson', ''));
        $launchContextId           = trim((string) $request->request->get('launchContextId', ''));
        $vars['questionnaireJson'] = $questionnaireJson;
        $vars['launchContextJson'] = $launchContextJson;
        $vars['launchContextId']   = $launchContextId;

        try {
            $questionnaire              = $this->decodeQuestionnaire($questionnaireJson);
            $vars['questionnaireTitle'] = (string) ($questionnaire['title'] ?? $questionnaire['name'] ?? $questionnaire['id'] ?? 'Questionnaire');
            $service                    = $this->serializationLocator->get(FhirVersion::R4);

            $launchContextResources = [];
            if ($launchContextId !== '') {
                // Fetching by ID takes precedence over a pasted resource when both are supplied — the
                // fetch goes through the SAME operator-configured FHIRHttpClientInterface used everywhere
                // else (never a visitor-supplied server URL); only the resource type (server-declared, on
                // the Questionnaire itself) and a strictly-validated ID land in the request path.
                $declared = $this->declaredLaunchContext($questionnaire);
                if ($declared === null) {
                    throw new \RuntimeException('This Questionnaire does not declare a launchContext, so there is nothing to fetch by ID for.');
                }

                $launchContextResources[$declared['name']] = $this->fetchLaunchContextResource($declared['type'], $launchContextId);
            } elseif ($launchContextJson !== '') {
                $declaredName = $this->declaredLaunchContext($questionnaire)['name'] ?? null;
                if ($declaredName === null) {
                    throw new \RuntimeException('This Questionnaire does not declare a launchContext, so a pasted resource has nothing to bind to.');
                }
                $launchContextResources[$declaredName] = $this->deserializeByResourceType($service, $launchContextJson);
            }

            $questionnaireModel = $service->deserialize($questionnaireJson, $this->fqcnFor('Questionnaire'));
            $result             = $this->populateService->populate($questionnaireModel, new PopulateContext(
                fhirVersion: FhirVersion::R4,
                launchContextResources: $launchContextResources,
                queryProvider: $this->queryProvider(),
            ));

            $vars['fields']                    = (new QuestionnaireFormRenderer())->renderFromResponse($questionnaire['item'] ?? [], $result->getResponse());
            $vars['populateIssues']            = $this->issuesFrom($result->getIssues());
            $vars['questionnaireResponseJson'] = $this->prettyJson($service->serializeToJson($result->getResponse()));
        } catch (\Throwable $e) {
            $vars['error'] = $e->getMessage();
            try {
                $questionnaire  = $this->decodeQuestionnaire($questionnaireJson);
                $vars['fields'] = (new QuestionnaireFormRenderer())->renderBlank($questionnaire['item'] ?? []);
            } catch (\Throwable) {
                // Keep $vars['fields'] empty — the questionnaireJson itself was unusable.
            }
        }

        return $this->render('sdc/index.html.twig', $vars);
    }

    #[Route('/extract', name: '_extract', methods: ['POST'])]
    public function extract(Request $request): Response
    {
        $vars                      = $this->baseVars();
        $questionnaireJson         = (string) $request->request->get('questionnaireJson', '');
        $vars['questionnaireJson'] = $questionnaireJson;
        /** @var array<string, mixed> $answers */
        $answers = $request->request->all('answers');

        try {
            $questionnaire              = $this->decodeQuestionnaire($questionnaireJson);
            $vars['questionnaireTitle'] = (string) ($questionnaire['title'] ?? $questionnaire['name'] ?? $questionnaire['id'] ?? 'Questionnaire');
            $vars['fields']             = (new QuestionnaireFormRenderer())->renderFromAnswers($questionnaire['item'] ?? [], $answers);

            $service               = $this->serializationLocator->get(FhirVersion::R4);
            $questionnaireResponse = (new QuestionnaireResponseBuilder())->build($questionnaire['item'] ?? [], $answers);
            // CORE: confirm the reconstructed QR serializes cleanly before it's handed to extract.
            $vars['questionnaireResponseJson'] = $this->prettyJson($service->serializeToJson($questionnaireResponse));

            $questionnaireModel = $service->deserialize($questionnaireJson, $this->fqcnFor('Questionnaire'));
            $result             = $this->extractService->extract($questionnaireResponse, new ExtractContext(
                fhirVersion: FhirVersion::R4,
                questionnaire: $questionnaireModel,
            ));

            $vars['extractResult'] = $this->prettyJson($service->serializeToJson($result->getResource()));
            $vars['extractIssues'] = $this->issuesFrom($result->getIssues());
        } catch (\Throwable $e) {
            $vars['error'] = $e->getMessage();
        }

        return $this->render('sdc/index.html.twig', $vars);
    }

    /**
     * "Check code" for an `answerValueSet`-bound item: re-renders the form exactly as `/extract` would
     * (preserving every other field's posted value), then validates one item's free-text code against the
     * value set canonical URL declared *on the Questionnaire itself* — never a client-submitted URL — via
     * the configured (env-var-sourced) terminology server.
     */
    #[Route('/validate-code', name: '_validate_code', methods: ['POST'])]
    public function validateCode(Request $request): Response
    {
        $vars                      = $this->baseVars();
        $questionnaireJson         = (string) $request->request->get('questionnaireJson', '');
        $vars['questionnaireJson'] = $questionnaireJson;
        /** @var array<string, mixed> $answers */
        $answers     = $request->request->all('answers');
        $checkLinkId = (string) $request->request->get('checkCodeLinkId', '');

        try {
            $questionnaire              = $this->decodeQuestionnaire($questionnaireJson);
            $vars['questionnaireTitle'] = (string) ($questionnaire['title'] ?? $questionnaire['name'] ?? $questionnaire['id'] ?? 'Questionnaire');
            $items                      = $questionnaire['item'] ?? [];
            $vars['fields']             = (new QuestionnaireFormRenderer())->renderFromAnswers($items, $answers);

            $valueSetUrl = $this->findAnswerValueSet($items, $checkLinkId);
            $code        = $this->findAnsweredCode($items, $answers, $checkLinkId);

            $vars['codeCheckResults'][$checkLinkId] = match (true) {
                $valueSetUrl === null || $code === null || $code === ''       => ['configured' => true, 'checked' => false],
                $this->terminologyClient instanceof NullFHIRTerminologyClient => ['configured' => false, 'checked' => false],
                default                                                       => ['configured' => true, 'checked' => true, 'valid' => $this->terminologyClient->validateCode($valueSetUrl, $code), 'code' => $code],
            };
        } catch (\Throwable $e) {
            $vars['error'] = $e->getMessage();
        }

        return $this->render('sdc/index.html.twig', $vars);
    }

    /**
     * M07: session-scoped credential entry — a visitor's own OAuth client id/secret or manual header
     * value, stored server-side in their session only (never persisted to disk, never logged), which
     * {@see ExternalClientFactory} prefers over the operator-configured env-var credentials for that
     * visitor's own subsequent requests. The FHIR server URL and OAuth token URL/IdP are never accepted
     * here — only credential values — this action does not even read fields for them.
     */
    #[Route('/credentials', name: '_credentials_set', methods: ['POST'])]
    public function setCredentials(Request $request): Response
    {
        $session = $request->getSession();

        $oauthClientId     = trim((string) $request->request->get('oauthClientId', ''));
        $oauthClientSecret = trim((string) $request->request->get('oauthClientSecret', ''));
        $authHeaderValue   = trim((string) $request->request->get('authHeaderValue', ''));

        if ($oauthClientId !== '' && $oauthClientSecret !== '') {
            $session->set(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_ID, $oauthClientId);
            $session->set(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_SECRET, $oauthClientSecret);
        }

        if ($authHeaderValue !== '') {
            $session->set(ExternalClientFactory::SESSION_KEY_AUTH_HEADER_VALUE, $authHeaderValue);
        }

        return $this->renderCurrentQuestionnaire($request);
    }

    #[Route('/credentials/clear', name: '_credentials_clear', methods: ['POST'])]
    public function clearCredentials(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_ID);
        $session->remove(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_SECRET);
        $session->remove(ExternalClientFactory::SESSION_KEY_AUTH_HEADER_VALUE);

        return $this->renderCurrentQuestionnaire($request);
    }

    /**
     * Re-renders `/sdc` preserving the currently-loaded Questionnaire (from the posted hidden field)
     * with blank answers — used by the credential actions above, which (deliberately, since they are a
     * separate small form, not the big Extract form) don't carry the rest of the posted answers.
     */
    private function renderCurrentQuestionnaire(Request $request): Response
    {
        $vars                      = $this->baseVars();
        $questionnaireJson         = (string) $request->request->get('questionnaireJson', '');
        $vars['questionnaireJson'] = $questionnaireJson;

        if ($questionnaireJson !== '') {
            try {
                $questionnaire              = $this->decodeQuestionnaire($questionnaireJson);
                $vars['questionnaireTitle'] = (string) ($questionnaire['title'] ?? $questionnaire['name'] ?? $questionnaire['id'] ?? 'Questionnaire');
                $vars['fields']             = (new QuestionnaireFormRenderer())->renderBlank($questionnaire['item'] ?? []);
            } catch (\Throwable $e) {
                $vars['error'] = $e->getMessage();
            }
        }

        return $this->render('sdc/index.html.twig', $vars);
    }

    /**
     * Find an item's `answerValueSet` canonical URL by walking the *Questionnaire's own* item tree
     * (never a client-submitted URL) — the only trusted source for which value set a code is checked
     * against; the terminology server it's checked on remains the DI-injected, env-configured client
     * regardless of what the Questionnaire declares.
     *
     * @param list<array<string, mixed>> $items
     */
    private function findAnswerValueSet(array $items, string $linkId): ?string
    {
        foreach ($items as $item) {
            if ((string) ($item['linkId'] ?? '') === $linkId) {
                $valueSet = $item['answerValueSet'] ?? null;

                return \is_string($valueSet) && $valueSet !== '' ? $valueSet : null;
            }

            /** @var list<array<string, mixed>> $children */
            $children = \is_array($item['item'] ?? null) ? $item['item'] : [];
            if ($children !== []) {
                $found = $this->findAnswerValueSet($children, $linkId);
                if ($found !== null) {
                    return $found;
                }
            }
        }

        return null;
    }

    /**
     * Find the posted free-text code for one leaf item's linkId, walking the posted `answers` scope in
     * lockstep with the Questionnaire's own group nesting (mirrors {@see QuestionnaireResponseBuilder}'s
     * tree walk) — a repeating group's every posted instance is searched.
     *
     * @param list<array<string, mixed>> $items
     * @param array<string, mixed>       $answers
     */
    private function findAnsweredCode(array $items, array $answers, string $linkId): ?string
    {
        foreach ($items as $item) {
            $itemLinkId = (string) ($item['linkId'] ?? '');

            if (($item['type'] ?? null) === 'group') {
                /** @var list<array<string, mixed>> $children */
                $children = \is_array($item['item'] ?? null) ? $item['item'] : [];
                $scope    = $answers[$itemLinkId] ?? null;

                if (\is_array($scope)) {
                    $instances = ($item['repeats'] ?? false) === true ? $scope : [$scope];
                    foreach ($instances as $instanceAnswers) {
                        if (\is_array($instanceAnswers)) {
                            $found = $this->findAnsweredCode($children, $instanceAnswers, $linkId);
                            if ($found !== null) {
                                return $found;
                            }
                        }
                    }
                }

                continue;
            }

            if ($itemLinkId === $linkId) {
                $value = $answers[$itemLinkId] ?? null;

                return \is_string($value) ? $value : null;
            }
        }

        return null;
    }

    /**
     * A display-only label for which auth mechanism (if any) protects the FHIR server connection, and
     * whether the *current visitor's own session* credentials are the ones in effect — never the
     * secret/header value itself. Null when no FHIR server is configured, or a server is configured but
     * unauthenticated.
     */
    private function fhirServerAuthMechanism(): ?string
    {
        if ($this->httpClient instanceof NullFHIRHttpClient) {
            return null;
        }

        $usingSessionOauth  = $this->sessionHas(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_ID)
            && $this->sessionHas(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_SECRET);
        $usingSessionHeader = $this->sessionHas(ExternalClientFactory::SESSION_KEY_AUTH_HEADER_VALUE);
        $usingEnvOauth      = $this->oauthClientIdEnv !== '';

        return match (true) {
            $this->oauthTokenUrl  !== '' && $usingSessionOauth                        => 'OAuth — your session credentials',
            $this->oauthTokenUrl  !== '' && $usingEnvOauth                            => 'OAuth',
            $this->oauthTokenUrl  !== ''                                              => 'OAuth — set your credentials below',
            $this->authHeaderName !== '' && $usingSessionHeader                       => \sprintf('%s header — your session value', $this->authHeaderName),
            $this->authHeaderName !== '' && $this->authHeaderValueSetViaEnv           => \sprintf('%s header', $this->authHeaderName),
            $this->authHeaderName !== ''                                              => \sprintf('%s header — set your value below', $this->authHeaderName),
            default                                                                   => null,
        };
    }

    /** Whether the current visitor's session has a given credential key set — never reads the value. */
    private function sessionHas(string $key): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request !== null && $request->hasSession() && $request->getSession()->has($key);
    }

    /**
     * Opt into live `application/x-fhir-query` context fetching only when a real FHIR server is
     * configured — deliberately null (not a provider wrapping {@see NullFHIRHttpClient}) when unconfigured,
     * so unconfigured population takes the exact same code path (skipped with an informational issue) as
     * before this server was wired in, rather than a different "fetch failed" warning from a live-shaped
     * provider whose underlying client always returns null.
     */
    private function queryProvider(): ?XFhirQueryPopulationDataProvider
    {
        if ($this->httpClient instanceof NullFHIRHttpClient) {
            return null;
        }

        return new XFhirQueryPopulationDataProvider($this->httpClient);
    }

    /** @return array<string, mixed> */
    private function baseVars(): array
    {
        return [
            'samples'                     => $this->samplePickerOptions(),
            'gallery'                     => $this->curatedGalleryEntries(),
            'questionnaireJson'           => '',
            'questionnaireTitle'          => null,
            'fields'                      => [],
            'launchContextJson'           => '',
            'launchContextId'             => '',
            // Fetch-by-ID only makes sense (and only ever attempts a real request) when a live FHIR
            // server is actually configured — with NullFHIRHttpClient the fetch would always fail.
            'canFetchLaunchContextById'   => !$this->httpClient instanceof NullFHIRHttpClient,
            'populateIssues'              => [],
            'extractResult'               => null,
            'extractIssues'               => [],
            'codeCheckResults'            => [],
            'questionnaireResponseJson'   => null,
            // Server-side only — read from env-configured DI, never a URL-entry field the visitor controls.
            'fhirServerConfigured'        => !$this->httpClient instanceof NullFHIRHttpClient,
            'fhirServerAuthMechanism'     => $this->fhirServerAuthMechanism(),
            'terminologyServerConfigured' => !$this->terminologyClient instanceof NullFHIRTerminologyClient,
            // M07: session-scoped credential entry — which fields (if any) a visitor could usefully
            // override for THIS deployment (only shown when the operator enabled the corresponding
            // mechanism via env vars), and whether this visitor's own session already has one set.
            'showOauthCredentialFields'   => $this->oauthTokenUrl  !== '',
            'showAuthHeaderField'         => $this->authHeaderName !== '',
            'authHeaderName'              => $this->authHeaderName,
            'sessionCredentialsSet'       => $this->sessionHas(ExternalClientFactory::SESSION_KEY_OAUTH_CLIENT_ID)
                || $this->sessionHas(ExternalClientFactory::SESSION_KEY_AUTH_HEADER_VALUE),
            'error'                       => null,
        ];
    }

    /** @return list<array{value: string, label: string}> */
    private function samplePickerOptions(): array
    {
        $options = [];
        foreach (glob($this->samplesDir . '/*.json') ?: [] as $path) {
            $filename  = basename($path);
            $options[] = ['value' => $filename, 'label' => $filename];
        }

        return $options;
    }

    /**
     * Curated, one-click gallery (M05): a hand-picked subset of `samplePickerOptions()`'s full file list,
     * each with a short description of what it demonstrates. Filtered to files that actually exist so a
     * renamed/removed sample degrades to a shorter gallery rather than a broken link.
     *
     * @return list<array{value: string, label: string, description: string}>
     */
    private function curatedGalleryEntries(): array
    {
        $entries = [
            [
                'value'       => 'sdc-demo-patient.questionnaire.json',
                'label'       => 'Patient intake',
                'description' => 'The full tour: repeats, enableWhen, quantity, and a live-checkable marital status code.',
            ],
            [
                'value'       => 'populate-spike.questionnaire.json',
                'label'       => 'Populate spike',
                'description' => 'Minimal Populate-only example — prefills from a pasted launch-context resource.',
            ],
            [
                'value'       => 'extract-spike.questionnaire.json',
                'label'       => 'Extract spike',
                'description' => 'Minimal Extract-only example — a few fields extracting onto a Patient.',
            ],
            [
                'value'       => 'x-fhir-query-demo.questionnaire.json',
                'label'       => 'Live server lookup',
                'description' => 'Fetches a real Patient via x-fhir-query when FHIR_SERVER_URL is configured.',
            ],
            [
                'value'       => 'x-fhir-query-patient-scoped-demo.questionnaire.json',
                'label'       => 'Patient intake + live lookup',
                'description' => 'Name/DOB/gender/emergency contact, plus that patient\'s most recent Observation fetched live from the configured FHIR server. Paste a Patient JSON (e.g. {"resourceType":"Patient","id":"example"}) into "Populate from launch context", then click Populate.',
            ],
        ];

        return array_values(array_filter(
            $entries,
            fn (array $entry): bool => is_file($this->samplesDir . '/' . $entry['value']),
        ));
    }

    /** Resolve the initial questionnaire JSON for `/render`: either a sample file or pasted JSON. */
    private function resolveQuestionnaireJson(Request $request): string
    {
        $source = (string) $request->request->get('source', '');

        if ($source === 'custom') {
            $custom = trim((string) $request->request->get('customJson', ''));
            if ($custom === '') {
                throw new \RuntimeException('Please paste a Questionnaire JSON.');
            }

            return $custom;
        }

        $path = $this->samplesDir . '/' . basename($source);
        if ($source === '' || !is_file($path)) {
            throw new \RuntimeException(sprintf('Unknown sample "%s".', $source));
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException(sprintf('Cannot read sample "%s".', $source));
        }

        return $contents;
    }

    /** @return array<string, mixed> */
    private function decodeQuestionnaire(string $json): array
    {
        if ($json === '') {
            throw new \RuntimeException('No Questionnaire loaded yet — pick a sample or paste one first.');
        }

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (($decoded['resourceType'] ?? null) !== 'Questionnaire') {
            throw new \RuntimeException('Expected a Questionnaire resource (resourceType mismatch).');
        }

        return $decoded;
    }

    /**
     * The name and resource type a Questionnaire's root `launchContext` extension declares (e.g.
     * `{name: "patient", type: "Patient"}`), or null when it declares none — mirrors
     * `FHIRQuestionnairePopulateService`'s own extension read, over the decoded array rather than a
     * typed model. The *type* string returned here is NOT trusted: the Questionnaire itself is
     * visitor-suppliable content (the "paste your own JSON" path), so `fetchLaunchContextResource()`
     * must validate it against the known FHIR resource types before ever using it in a request path.
     *
     * @param array<string, mixed> $questionnaire
     *
     * @return array{name: string, type: string}|null
     */
    private function declaredLaunchContext(array $questionnaire): ?array
    {
        foreach ($questionnaire['extension'] ?? [] as $extension) {
            if (!\is_array($extension) || ($extension['url'] ?? null) !== self::LAUNCH_CONTEXT_URL) {
                continue;
            }

            $name = null;
            $type = null;
            foreach ($extension['extension'] ?? [] as $sub) {
                if (!\is_array($sub)) {
                    continue;
                }
                if (($sub['url'] ?? null) === 'name') {
                    $code = $sub['valueCoding']['code'] ?? null;
                    $name = \is_string($code) && $code !== '' ? $code : $name;
                } elseif (($sub['url'] ?? null) === 'type') {
                    $code = $sub['valueCode'] ?? null;
                    $type = \is_string($code) && $code !== '' ? $code : $type;
                }
            }

            if ($name !== null && $type !== null) {
                return ['name' => $name, 'type' => $type];
            }
        }

        return null;
    }

    private function deserializeByResourceType(FHIRSerializationService $service, string $json): object
    {
        /** @var array<string, mixed>|null $decoded */
        $decoded      = json_decode($json, true);
        $resourceType = \is_array($decoded) ? ($decoded['resourceType'] ?? null) : null;

        if (!\is_string($resourceType) || $resourceType === '') {
            throw new \RuntimeException('Launch-context JSON is missing "resourceType".');
        }

        return $service->deserialize($json, $this->fqcnFor($resourceType));
    }

    /**
     * Fetches `{resourceType}/{id}` from the configured (operator-only) FHIR server and deserializes it.
     * `$resourceType` comes from the Questionnaire's own `launchContext` declaration — but the
     * Questionnaire is visitor-suppliable content (the "paste your own JSON" path), so it is validated
     * against the known FHIR R4 resource types via {@see self::fqcnFor()} BEFORE it is ever used to build
     * the outbound request path, not only afterward when deserializing the response. `$id` is the other
     * visitor-supplied value; it is strictly validated against FHIR's own id grammar
     * (`[A-Za-z0-9\-\.]{1,64}`) before ever reaching the request path, so it cannot inject extra path
     * segments, query parameters, or header/CRLF content into the outbound request — the same posture as
     * every other visitor-influenced value this controller sends outbound (see `SdcPopulateSsrfTest`).
     *
     * @throws \RuntimeException on an invalid id, an unknown resource type, or a failed/empty fetch
     */
    private function fetchLaunchContextResource(string $resourceType, string $id): object
    {
        if (preg_match('/^[A-Za-z0-9\-.]{1,64}$/', $id) !== 1) {
            throw new \RuntimeException('Invalid resource ID — FHIR ids may only contain letters, digits, hyphens, and periods.');
        }

        // Resolve (and thereby validate) the resource type against the known FHIR R4 model classes
        // before issuing any outbound request — an unrecognized $resourceType must never reach the
        // request path below, even as a substring alongside a valid $id.
        $fqcn = $this->fqcnFor($resourceType);

        $body = $this->httpClient->request('GET', $resourceType . '/' . $id);
        if ($body === null) {
            throw new \RuntimeException(\sprintf('Could not fetch %s/%s from the configured FHIR server.', $resourceType, $id));
        }

        return $this->serializationLocator->get(FhirVersion::R4)->deserialize($body, $fqcn);
    }

    private function fqcnFor(string $resourceType): string
    {
        $fqcn = sprintf('%s\\R4\\Resource\\%sResource', self::MODELS_BASE_NAMESPACE, $resourceType);

        if (!class_exists($fqcn)) {
            throw new \RuntimeException(sprintf('Unknown resource type "%s" for FHIR R4.', $resourceType));
        }

        return $fqcn;
    }

    /**
     * @return list<array{severity: string, diagnostics: string}>
     */
    private function issuesFrom(?object $operationOutcome): array
    {
        if ($operationOutcome === null) {
            return [];
        }

        $issues = \is_array($operationOutcome->issue ?? null) ? $operationOutcome->issue : [];
        $result = [];

        foreach ($issues as $issue) {
            $severity      = $issue->severity ?? null;
            $result[]      = [
                'severity'    => \is_object($severity) ? (string) ($severity->value ?? 'information') : (string) ($severity ?? 'information'),
                'diagnostics' => (string) ($issue->diagnostics ?? ''),
            ];
        }

        return $result;
    }

    private function prettyJson(string $json): string
    {
        $decoded = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $json;
        }

        return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: $json;
    }
}
