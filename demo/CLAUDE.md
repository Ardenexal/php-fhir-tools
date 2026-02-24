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

## FHIR Services (via FHIRBundle)

Inject by type hint — all autowired:

- `Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService` — evaluate/validate FHIRPath expressions
- `Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService` — serialize/deserialize FHIR JSON/XML
- `Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator` — validate FHIR objects
- `Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface` — extract resource metadata

## FHIR Config

`config/packages/fhir.yaml` — configures default version, output/cache directories, validation, and FHIRPath cache.

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
