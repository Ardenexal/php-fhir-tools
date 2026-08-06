# demo/CLAUDE.md

This file documents conventions for the demo Symfony 7.4 application.

## Stack

- **Framework**: Symfony 7.4 with `MicroKernelTrait`
- **Templates**: Twig (`*.html.twig`) in `templates/`
- **CSS**: Tailwind CSS via Play CDN (`<script src="https://cdn.tailwindcss.com">` in `base.html.twig`)
- **JS**: Stimulus controllers in `assets/controllers/`, Turbo for partial page updates
- **Routes**: PHP 8 `#[Route]` attributes on controllers (auto-discovered via `config/routes.yaml`)
- **Services**: Autowired via `config/services.yaml`; FHIR services injected by type hint (registered by FHIRBundle)

## Controllers

Located in `src/Controller/`. Use `#[Route]` attribute for routing. Extend `AbstractController`.

- `SdcController` (`/sdc`) — the SDC Populate/Extract playground: pick or paste a Questionnaire, fill in
  its form (repeats, `enableWhen`, quantity, `answerValueSet` code-checking all supported), Populate it
  from launch-context JSON, and Extract into a transaction Bundle. A curated example gallery and a
  "View QuestionnaireResponse JSON" toggle are on the page itself. See "External FHIR/Terminology
  Servers" below for its optional live-server env vars.

## FHIR Services (via FHIRBundle)

Inject by type hint — all autowired:

- `Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService` — evaluate/validate FHIRPath expressions
- `Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService` — serialize/deserialize FHIR JSON/XML
- `Ardenexal\FHIRTools\Component\Serialization\FHIRVersionedSerializationServiceLocator` — get version-specific serializer at runtime
- `Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator` — validate FHIR objects
- `Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface` — extract resource metadata
- `Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceInterface` — run constraint validation, returns `FHIRValidationReport`

## FHIR Config

`config/packages/fhir.yaml` — configures default version, output/cache directories, validation, and FHIRPath cache.

## External FHIR/Terminology Servers (SDC Playground)

The `/sdc` page is offline-first by default. Two env vars opt it into live server connectivity:

- `FHIR_SERVER_URL` — when set, `application/x-fhir-query` `itemPopulationContext`/`variable` directives
  in `$populate` resolve against this server instead of being skipped with an informational issue.
- `FHIR_TERMINOLOGY_SERVER_URL` — when set, an `answerValueSet`-bound choice item's "Check code" action
  validates against this server instead of showing a "no terminology server configured" note.

Both default to empty string (offline). Set them via a real environment variable or `.env.local`
(gitignored) — **not** `demo/.env`, which is off-limits to agent edits in this repo; the empty defaults
are declared instead as committed `parameters:` in `config/services.yaml` using Symfony's
`env(NAME): 'default'` convention, so no `.env` edit is ever required to keep the demo offline by default.

Server base URLs are **operator-configured only** — there is deliberately no "enter a FHIR server URL"
field anywhere in the UI. A public URL-entry field would turn the demo host into an open SSRF proxy
against whatever network it runs on. See `src/Sdc/ExternalClientFactory.php` and the
`FHIRHttpClientInterface`/`FHIRTerminologyClientInterface` factory wiring in `config/services.yaml`.

Overriding these interfaces is app-wide, not scoped to `/sdc`: setting `FHIR_SERVER_URL` also makes
`/fhirpath`'s `resolve()`/`memberOf()` go live, and `FHIR_TERMINOLOGY_SERVER_URL` also makes `/validate`'s
terminology binding checks go live — matching `FHIRBundle`'s own documented override pattern.

### FHIR server authentication (M06)

Two independent, composable authentication mechanisms for the `FHIR_SERVER_URL` connection. The
*destination* is always operator-configured only (env var/`.env.local`, never a request-time input); the
*credential values* can additionally come from a visitor's own session — see "Session-scoped credential
entry (M07)" below.

- **OAuth 2.0 client credentials grant** — `FHIR_SERVER_OAUTH_TOKEN_URL`, `FHIR_SERVER_OAUTH_CLIENT_ID`,
  `FHIR_SERVER_OAUTH_CLIENT_SECRET`. The token URL is required to enable OAuth at all; the client id and
  secret must either both be present (from env vars, a visitor's session, or one of each) or both absent
  — a mismatched pair fails loudly at container-resolution time. Token-URL-alone (no id/secret anywhere
  yet) is a valid state: OAuth is enabled but not yet authenticated for this request. The library fetches
  and caches the bearer token itself (`Ardenexal\FHIRTools\Component\HttpClient\OAuth\
  OAuthClientCredentialsTokenProvider` / `OAuthHttpClient`).
- **A manual header** — `FHIR_SERVER_AUTH_HEADER_NAME` / `FHIR_SERVER_AUTH_HEADER_VALUE`, attached
  verbatim to every request (`StaticHeaderHttpClient`). Covers a hand-obtained `Authorization: Bearer
  <token>` or an `X-Api-Key`-style header. Same shape as OAuth: the name enables the mechanism, the value
  can arrive later (env or session).

Both mechanisms can be configured together as long as they don't both target the `Authorization` header
(that combination fails loudly too — see `ExternalClientFactory::assertAuthConfigurationIsConsistent()`).
The client secret and header value are never logged, never displayed, and never appear in any error
panel — verified end-to-end in `demo/tests/Controller/SdcOAuthSecretLeakageTest.php`. The `/sdc` status
badge shows *which* mechanism is active (e.g. "configured (OAuth)") but never the credential itself.

### Session-scoped credential entry (M07)

`/sdc` has a small form (shown only when the operator has enabled OAuth or a manual header — i.e. when
`FHIR_SERVER_OAUTH_TOKEN_URL` or `FHIR_SERVER_AUTH_HEADER_NAME` is set) letting a visitor enter their own
OAuth client id/secret, or their own header value, for **their session only** — never persisted to disk,
never logged, never echoed back into the form. This lets someone test the demo as different users
without editing env vars and restarting the server. `ExternalClientFactory` checks the current visitor's
session first (`sdc_oauth_client_id`/`sdc_oauth_client_secret`/`sdc_auth_header_value`) and falls back to
the env-var value when no session override is present — see `App\Controller\SdcController::
setCredentials()`/`clearCredentials()` and `ExternalClientFactory`'s class docblock.

**Accepted risk, deliberately chosen — not a hardening recommendation.** The destination FHIR server and
OAuth token URL/IdP stay operator-configured; only the credential *values* are visitor-enterable. This
still means **any visitor who can reach `/sdc` can authenticate as whoever they claim, against whatever
server the operator has configured** — there is no verification that a submitted client id/secret
"belongs" to the person submitting it. This tradeoff was raised explicitly (against a safer
"operator-pre-configured named profiles" alternative) and the free-text session-entry approach was
chosen anyway, on the basis that this is suited to a local/trusted-access demo. **Do not expose `/sdc`
on a network you don't trust without adding real access control first** (see `backlog.md`).

Session isolation between visitors, and that a submitted secret never leaks into any rendered page or
error, are both covered by `demo/tests/Controller/SdcSessionCredentialIsolationTest.php` and
`SdcSessionCredentialLeakageTest.php`.

## Useful Commands

```bash
# From the demo/ directory:
symfony local:server:start        # Start local dev server (http://localhost:8000)
php bin/console debug:router      # Inspect registered routes
php bin/console debug:container   # Inspect services
php bin/console cache:clear       # Clear cache
```

## Testing

Start the local server with `symfony local:server:start` from the `demo/` directory, then use the
`webapp-testing` skill to interact with `http://localhost:8000`.
