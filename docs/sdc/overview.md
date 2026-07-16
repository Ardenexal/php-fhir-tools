---
description: Populate and extract FHIR resources for SDC Questionnaires.
icon: file-export
---

# Overview

The SDC component implements [Structured Data Capture](https://build.fhir.org/ig/HL7/sdc/)
operations for FHIR PHP model objects. Today it delivers two operations:
`QuestionnaireResponse/$extract` — turning a completed `QuestionnaireResponse` into FHIR
resources per the [SDC extraction operation](https://build.fhir.org/ig/HL7/sdc/en/extraction.html) —
and `Questionnaire/$populate` — pre-filling a `QuestionnaireResponse` from launch context per the
[SDC populate operation](https://build.fhir.org/ig/HL7/sdc/en/populate.html).
It supports R4, R4B, and R5.

## `$extract` quick start

```php
use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnaireResponseExtractService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

$service = new FHIRQuestionnaireResponseExtractService();

$result = $service->extract($questionnaireResponse, new ExtractContext(
    fhirVersion:    FhirVersion::R4, // output model namespace (R4 / R4B / R5)
    questionnaire:  $questionnaire,  // the source Questionnaire carrying the extract directives
    emitProvenance: false,           // opt-in Provenance entry
));

$bundle = $result->getResource(); // a transaction Bundle (always)
$issues = $result->getIssues();   // an OperationOutcome, or null when nothing to report
```

## `$populate` quick start

```php
use Ardenexal\FHIRTools\Component\Sdc\BundlePopulationDataProvider;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;

$service = new FHIRQuestionnairePopulateService();

$result = $service->populate($questionnaire, new PopulateContext(
    fhirVersion:            FhirVersion::R4,                       // output model namespace (R4 / R4B / R5)
    launchContextResources: ['patient' => $patient],              // bound as FHIRPath %patient, …
    subject:                'Patient/123',                         // sets QuestionnaireResponse.subject (optional)
    dataProvider:           new BundlePopulationDataProvider($dataBundle), // observation-based (optional)
));

$response = $result->getResponse(); // a QuestionnaireResponse (status: in-progress)
$issues   = $result->getIssues();   // an OperationOutcome, or null when nothing to report
```

## What it does

* **Three extraction methods** — observation-based (R4 only), definition-based
  (`definitionExtract` / `definitionExtractValue`), and template-based (`#contained`
  templates). A single Questionnaire may mix all three; results merge into one Bundle.
* **Transaction-Bundle output** — the payload is always a `transaction` Bundle. Each
  `entry.request` is `POST Type` (no logical id) or `PUT Type/id` (id present) — extraction
  is create/update only, never delete.
* **Graceful degradation** — an empty extraction yields an empty Bundle plus an
  `information` `OperationOutcome`; a malformed expression warns and skips that entry rather
  than failing the run.
* **Opt-in `Provenance`** — pass `emitProvenance: true` to add a `Provenance` entry linking
  the extracted resources back to the source `QuestionnaireResponse`.

`Questionnaire/$populate` pre-fills a `QuestionnaireResponse` from a `Questionnaire`'s SDC
population directives — expression-based (`launchContext` + `initialExpression`, `variable`
chains, `itemPopulationContext`) and observation-based (`observationLinkPeriod`). Call
`FHIRQuestionnairePopulateService::populate($questionnaire, new PopulateContext(...))`; the
returned `PopulateResult` carries the generated `QuestionnaireResponse` (status
`in-progress`) plus an optional `OperationOutcome`. Population is offline-first and
FHIRPath-only — the component README is canonical for the supported-mechanisms table and
exclusions.

## Reference

The component README is canonical for the full supported-methods table, output contract,
and current exclusions (StructureMap-based extraction, `templateExtractBundle`, live
persistence, and access control): see `src/Component/Sdc/README.md`.
