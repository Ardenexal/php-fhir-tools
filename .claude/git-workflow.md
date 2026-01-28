---
inclusion: always
---

# Git Workflow and Commit Standards

## Commit Message Standards

### Conventional Commits Format
All commits must follow the Conventional Commits specification:
```
<type>: <description>

[optional body]

[optional footer(s)]
```

### Commit Types
- **feat**: New feature or functionality
- **fix**: Bug fix or error correction
- **chore**: Maintenance tasks, dependency updates, tooling changes
- **test**: Adding or modifying tests
- **docs**: Documentation changes
- **refactor**: Code refactoring without changing functionality
- **perf**: Performance improvements
- **style**: Code style changes (formatting, missing semicolons, etc.)

### Commit Message Rules
- **No AI Mentions**: Never mention AI, ChatGPT, Copilot, or similar in commit messages
- **Imperative Mood**: Use imperative mood ("Add feature" not "Added feature")
- **Lowercase**: Start description with lowercase letter
- **No Period**: Don't end description with a period
- **Concise**: Keep description under 50 characters when possible
- **Descriptive**: Be specific about what changed

### Examples
```bash
# Good commits
feat: add FHIR R4B structure definition parser
fix: handle null values in element cardinality validation
chore: update symfony dependencies to 7.3
test: add unit tests for error collector functionality

# Bad commits
feat: Added new stuff with AI help
fix: Fixed bug
chore: Updates
```

## Branch Management

### Branch Naming
- **Feature branches**: `feature/description-of-feature`
- **Bug fixes**: `fix/description-of-bug`
- **Hotfixes**: `hotfix/critical-issue-description`
- **Chores**: `chore/maintenance-task`

### Branch Workflow
- Create feature branches from `main`
- Keep branches focused on single features/fixes
- Regularly rebase against `main` to stay current
- Delete branches after merging

## Code Review Process

### Before Committing
- Run `composer run lint` to fix code style
- Run `composer run phpstan` for static analysis
- Run `composer run test` to ensure tests pass
- Run `composer run generate-models
- Verify all new code follows project standards

### Pull Request Guidelines
- **Clear Title**: Use conventional commit format for PR titles
- **Description**: Explain what changed and why
- **Testing**: Describe how changes were tested
- **Breaking Changes**: Clearly mark any breaking changes
- **Documentation**: Update documentation if needed

## GPG Signing

### Requirement
- All commits must be signed with GPG
- Configure Git to sign commits automatically:
```bash
git config --global commit.gpgsign true
git config --global user.signingkey YOUR_GPG_KEY_ID
```

### Verification
- Verify commits are signed: `git log --show-signature`
- Ensure GitHub shows "Verified" badge on commits

## Security Considerations

### Sensitive Data
- Never commit sensitive information (API keys, passwords, etc.)
- Use `.gitignore` to exclude sensitive files
- Review commits before pushing to ensure no secrets included

### History Hygiene
- Don't rewrite public history
- Use `git revert` instead of force-pushing to fix public commits
- Keep commit history clean and meaningful