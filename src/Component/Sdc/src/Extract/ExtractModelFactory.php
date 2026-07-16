<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Extract;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;

/**
 * Version-scoped construction of the FHIR model objects a `$extract` run assembles.
 *
 * The extraction service is otherwise version-agnostic — it reflects over whatever
 * {@see DefinitionPathWriter} and {@see PropertyMetadataProvider}
 * hand it — but the transaction `Bundle` envelope, the companion `OperationOutcome`, and the target
 * resource stubs must be instantiated from the correct per-version model namespace
 * (`Models\R4`, `Models\R4B`, `Models\R5`). This factory is the *single* place that resolves those
 * FQCNs from {@see FhirVersion} and performs the dynamic `new $fqcn(...)` construction, mirroring the
 * `FhirVersion::extensionFqcn()` convention (the version string is the namespace segment).
 *
 * Concentrating the dynamic construction here keeps the service body statically typed: every helper
 * there returns/consumes the toolkit's sanctioned cross-version `object`, and the only place PHPStan
 * cannot see through a `new $variableClass(...)` is this bounded, annotated file.
 *
 * The FHIR *codes* passed in (`'transaction'`, `'POST'`/`'PUT'`, issue severities/types) are
 * version-stable strings, so they are taken as literals; only the wrapper classes are per-version.
 *
 * @internal implementation detail of the `Sdc` extraction path; not part of the public API
 */
final class ExtractModelFactory
{
    public function __construct(
        private readonly FhirVersion $version,
    ) {
    }

    /**
     * The FHIR version string (`R4`/`R4B`/`R5`) this factory constructs for — also the value FHIRPath
     * evaluation must be scoped to, so the extraction service reads it here rather than hardcoding R4.
     */
    public function fhirVersionValue(): string
    {
        return $this->version->value;
    }

    /**
     * Resolve a base FHIR resource type name (e.g. `Patient`) to its generated resource class in this
     * factory's version namespace, or null when no such resource class exists (profiles / unknown
     * types are unsupported — the caller surfaces a diagnostic issue instead of crashing).
     *
     * @return class-string|null
     */
    public function resolveResourceClass(string $type): ?string
    {
        $fqcn     = $this->fqcn('Resource\\' . $type . 'Resource');
        $abstract = $this->fqcn('Resource\\AbstractResource');

        if (!class_exists($fqcn) || !is_subclass_of($fqcn, $abstract)) {
            return null;
        }

        return $fqcn;
    }

    /**
     * Instantiate an empty resource stub for a base type, or null when the type does not resolve.
     */
    public function newResource(string $type): ?object
    {
        $class = $this->resolveResourceClass($type);

        return $class === null ? null : new $class();
    }

    /**
     * Build one transaction-Bundle entry: `fullUrl` + resource + a `request` directive (method + url).
     */
    public function bundleEntry(string $fullUrl, object $resource, string $method, string $url): object
    {
        $uriClass     = $this->fqcn('Primitive\\UriPrimitive');
        $verbClass    = $this->fqcn('DataType\\HTTPVerbType');
        $requestClass = $this->fqcn('Resource\\Bundle\\BundleEntryRequest');
        $entryClass   = $this->fqcn('Resource\\Bundle\\BundleEntry');

        return new $entryClass(
            fullUrl: new $uriClass(value: $fullUrl),
            resource: $resource,
            request: new $requestClass(
                method: new $verbClass($method),
                url: new $uriClass(value: $url),
            ),
        );
    }

    /**
     * Assemble the given entries into a `transaction` Bundle.
     *
     * @param list<object> $entries
     */
    public function transactionBundle(array $entries): object
    {
        $typeClass   = $this->fqcn('DataType\\BundleTypeType');
        $bundleClass = $this->fqcn('Resource\\BundleResource');

        return new $bundleClass(
            type: new $typeClass('transaction'),
            entry: $entries,
        );
    }

    /**
     * Build a single `OperationOutcome.issue` with the given severity/type codes and diagnostics text.
     */
    public function issue(string $severity, string $code, string $diagnostics): object
    {
        $severityClass = $this->fqcn('DataType\\IssueSeverityType');
        $codeClass     = $this->fqcn('DataType\\IssueTypeType');
        $issueClass    = $this->fqcn('Resource\\OperationOutcome\\OperationOutcomeIssue');

        return new $issueClass(
            severity: new $severityClass($severity),
            code: new $codeClass($code),
            diagnostics: $diagnostics,
        );
    }

    /**
     * Wrap collected issues in an `OperationOutcome`.
     *
     * @param list<object> $issues
     */
    public function operationOutcome(array $issues): object
    {
        $outcomeClass = $this->fqcn('Resource\\OperationOutcomeResource');

        return new $outcomeClass(issue: $issues);
    }

    /**
     * Build the extraction `Provenance`: a cardinality-complete record attesting the run.
     *
     * Per the SDC extraction spec, the produced resources' provenance points back at the source
     * QuestionnaireResponse. This builds `target` 1..* (one Reference per extracted resource fullUrl),
     * the required `recorded` instant, a single `agent` (`who.display` naming the extractor software —
     * see ADR-010, the toolkit is the acting device and mints no Device resource), and a single `entity`
     * with `role = source` whose `what` references the QR. FHIR *codes* (`'source'`) are version-stable
     * literals; only the wrapper classes are per-version.
     *
     * @param list<string> $targetFullUrls  the `fullUrl` of each extracted Bundle entry (Provenance.target)
     * @param string       $sourceReference a reference to the source QuestionnaireResponse (Provenance.entity.what)
     * @param string       $recordedInstant an ISO-8601 instant (full date+time+timezone) for Provenance.recorded
     * @param string       $agentDisplay    human-readable name of the extracting software (Provenance.agent.who.display)
     */
    public function provenance(array $targetFullUrls, string $sourceReference, string $recordedInstant, string $agentDisplay): object
    {
        $refClass     = $this->fqcn('DataType\\Reference');
        $instantClass = $this->fqcn('Primitive\\InstantPrimitive');
        $roleClass    = $this->fqcn('DataType\\ProvenanceEntityRoleType');
        $agentClass   = $this->fqcn('Resource\\Provenance\\ProvenanceAgent');
        $entityClass  = $this->fqcn('Resource\\Provenance\\ProvenanceEntity');
        $provClass    = $this->fqcn('Resource\\ProvenanceResource');

        $targets = [];
        foreach ($targetFullUrls as $fullUrl) {
            $targets[] = new $refClass(reference: $fullUrl);
        }

        return new $provClass(
            target: $targets,
            recorded: new $instantClass(value: FHIRInstant::parse($recordedInstant)),
            agent: [new $agentClass(who: new $refClass(display: $agentDisplay))],
            entity: [new $entityClass(
                role: new $roleClass('source'),
                what: new $refClass(reference: $sourceReference),
            )],
        );
    }

    /**
     * Build a fully-qualified model class name in this factory's version namespace.
     */
    private function fqcn(string $relative): string
    {
        return 'Ardenexal\\FHIRTools\\Component\\Models\\' . $this->version->value . '\\' . $relative;
    }
}
