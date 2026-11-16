---
category: docs-and-crossrefs
last_reviewed: 2026-05-10
---

# Footguns: Documentation and Cross-References

## Footgun: Router Table vs Key Resources heading conflict

**Status:** active | **Created:** 2026-05-10 | **Evidence:** OBSERVED

The goat-flow harness checks CLAUDE.md for two distinct things using different check IDs:

- `instruction-file-skill-reference-pointer` (setup scope) looks for the exact heading text `## Router Table` and a row matching `Tool playbooks (CLI/MCP availability checks...)`.
- `instruction-sections-present` (harness scope) looks for a `## Key Resources` section heading.

These are separate requirements. Renaming `## Router Table` to `## Key Resources` satisfies one check while breaking the other. CLAUDE.md must contain **both** sections.

**Evidence:** CLAUDE.md (search: `## Router Table` and `## Key Resources`) — both headings must coexist.
