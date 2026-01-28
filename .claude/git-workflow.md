# Git Workflow and Commit Standards

## Commit Message Format

### Conventional Commits
Use the Conventional Commits specification:
```
<type>: <description>

[optional body]
```

### Commit Types
- **feat**: New feature or functionality
- **fix**: Bug fix or error correction
- **chore**: Maintenance tasks, dependency updates
- **test**: Adding or modifying tests
- **docs**: Documentation changes
- **refactor**: Code refactoring without changing functionality

### Commit Rules
- **No AI Mentions**: Never mention AI, ChatGPT, Copilot, Claude, or similar in commits
- **Imperative Mood**: Use imperative mood ("add feature" not "added feature")
- **Lowercase**: Start description with lowercase letter
- **No Period**: Don't end description with a period
- **Concise**: Keep description under 50 characters when possible

### Examples
```bash
# Good commits
feat: add FHIR R4B structure definition parser
fix: handle null values in element cardinality validation
chore: update symfony dependencies to 7.4
test: add unit tests for error collector

# Bad commits
feat: Added new stuff with AI help
fix: Fixed bug
chore: Updates
```

## Branch Naming

### Conventions Used
- **Feature branches**: `feature/description-of-feature`
- **Bug fixes**: `fix/description-of-bug`
- **Chores**: `chore/maintenance-task`
- **AI Agent branches**: `claude/task-description` or `copilot/task-description`

## Before Committing

Run quality checks:
```bash
composer lint      # Fix code style
composer phpstan   # Static analysis
composer test      # Run tests
```

## Pull Request Guidelines
- Use conventional commit format for PR titles
- Explain what changed and why
- Describe how changes were tested
- Mark any breaking changes clearly
