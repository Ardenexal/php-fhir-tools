---
description: Set up the monorepo for local development.
icon: laptop-code
---

# Development Setup

```bash
git clone https://github.com/Ardenexal/php-fhir-tools.git
cd php-fhir-tools
composer install
```

## Quality checks

```bash
composer quality:all   # lint + phpstan + test
```

<!-- TODO: migrate dev setup / quality commands from root README.md and CONTRIBUTING.md -->

{% hint style="warning" %}
Generated model files under `src/Component/Models/src/` must never be hand-edited — regenerate via
`fhir:generate`.
{% endhint %}

<!-- MIGRATION SOURCE: root README.md (Development), CONTRIBUTING.md -->
