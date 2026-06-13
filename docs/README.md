---
description: PHP toolkit for FHIR — code generation, serialization, validation, and FHIRPath.
icon: book-medical
---

# Introduction

PHP FHIR Tools is a PHP 8.3+ library monorepo for working with
[FHIR](https://www.hl7.org/fhir/) (Fast Healthcare Interoperability Resources). It generates PHP
model classes from FHIR Structure Definitions, serializes resources to and from JSON and XML,
validates resources against the specification and Implementation Guides, and evaluates FHIRPath
expressions.

{% hint style="info" %}
The packages can be used independently or together. If you only need serialization, install
`ardenexal/fhir-serialization`; if you are building a Symfony app, the `ardenexal/fhir-bundle`
wires everything for you.
{% endhint %}

## What's in the toolkit

<table data-view="cards">
  <thead>
    <tr>
      <th>Capability</th>
      <th>Package</th>
      <th data-card-target data-type="content-ref">Docs</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Generate PHP models from FHIR definitions</td>
      <td><code>ardenexal/fhir-code-generation</code></td>
      <td><a href="code-generation/overview.md">Code Generation</a></td>
    </tr>
    <tr>
      <td>JSON / XML serialization</td>
      <td><code>ardenexal/fhir-serialization</code></td>
      <td><a href="serialization/overview.md">Serialization</a></td>
    </tr>
    <tr>
      <td>Resource & profile validation</td>
      <td><code>ardenexal/fhir-serialization</code></td>
      <td><a href="validation/overview.md">Validation</a></td>
    </tr>
    <tr>
      <td>FHIRPath expression evaluation</td>
      <td><code>ardenexal/fhir-path</code></td>
      <td><a href="fhirpath/overview.md">FHIRPath</a></td>
    </tr>
    <tr>
      <td>Symfony integration</td>
      <td><code>ardenexal/fhir-bundle</code></td>
      <td><a href="bundle/configuration.md">Symfony Bundle</a></td>
    </tr>
  </tbody>
</table>

## Where to start

* New to the library? Start with [Installation](getting-started/installation.md) and the
  [Quick Start](getting-started/quick-start.md).
* Not sure which package you need? See [Choosing the Right Package](getting-started/packages.md).

<!-- MIGRATION SOURCE: root README.md (overview + package table). Keep this page canonical. -->
