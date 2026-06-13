---
description: Generate PHP model classes from FHIR Structure Definitions and ValueSets.
icon: gears
---

# Overview

The Code Generation component loads FHIR packages from the registry and generates strongly-typed PHP
classes — Resources, DataTypes, Primitives, and Enums — from their Structure Definitions and
ValueSets.

There are two distinct workflows:

<table data-view="cards">
  <thead>
    <tr><th>Workflow</th><th data-card-target data-type="content-ref">Page</th></tr>
  </thead>
  <tbody>
    <tr><td>Generate the base FHIR models (R4 / R4B / R5)</td><td><a href="base-models.md">Base FHIR Models</a></td></tr>
    <tr><td>Generate typed extension & profile classes for an Implementation Guide</td><td><a href="implementation-guides.md">Implementation Guides</a></td></tr>
  </tbody>
</table>

## How it works

<!-- TODO: migrate the "How It Works" pipeline (PackageLoader → parse → generate) from
     src/Component/CodeGeneration/README.md -->

{% hint style="warning" %}
Generated model files under `src/Component/Models/src/` are **never hand-edited** — always
regenerate. See [Generated Output Structure](output-structure.md).
{% endhint %}

<!-- MIGRATION SOURCE: src/Component/CodeGeneration/README.md (Features, How It Works, Core Classes) -->
