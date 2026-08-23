# Git Commit Instructions

<!-- goat-flow: generated from recent git history; review and edit for project policy -->

## Observed Commit Style

- Conventional commits: 83
- Ticket-prefixed subjects: 0
- Free-form subjects: 17

Use conventional commits because at least 70% of sampled subjects matched that style.

## Format

- Use `type(scope): subject` or `type: subject`.
- Observed types: feat, fix, refactor, chore, test, tests, ci, docs.
- Keep the subject concrete: name the behavior, file family, or command that changed.
- Add a body when the subject names more than one axis or the motivation is not obvious.

## Evidence

- Sampled commits: 100
- Subject length p95: 96 characters
- Bodies observed: yes
- Co-authored-by trailers observed: yes
- Signed-off-by trailers observed: yes
- Example from history: `feat(validation): implement FHIRDerivedQuestionnaireValidator for derived Questionnaire validation`
