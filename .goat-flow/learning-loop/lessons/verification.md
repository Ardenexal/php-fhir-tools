---
category: verification
last_reviewed: 2026-05-10
---

# Lessons: Verification

## Lesson: Harness checks match exact text patterns, not semantic equivalents

**Status:** active | **Created:** 2026-05-10 | **Evidence:** OBSERVED

When rewriting CLAUDE.md to satisfy harness checks, paraphrasing the skill-reference pointer
in the READ step ("Check `.goat-flow/skill-docs/`...") caused the `instruction-file-skill-reference-pointer` setup check to fail, even though the intent was identical to the original wording.

The harness audits for specific strings (e.g. "Before declaring any tool or capability unavailable, read the matching playbook in `.goat-flow/skill-docs/`"). Semantically equivalent rewrites do not pass.

**Rule:** When editing instruction files, diff against the exact strings the harness checks before removing or paraphrasing any existing pointer text. The `howToFix` field in the audit JSON contains the exact required wording.

**Evidence:** CLAUDE.md READ section (search: `Before declaring any tool or capability unavailable`) — must match exactly.
