# Security Guidelines

## Data Protection

### Sensitive Information
- **No Secrets in Code**: Never hardcode API keys, passwords, or sensitive data
- **Environment Variables**: Use `.env` files for configuration (`.env`, `.env.dev`, `.env.test`)
- **Log Sanitization**: Ensure logs don't contain sensitive information
- **Error Messages**: Don't expose internal system details in error messages

### Input Validation
- **FHIR Data Validation**: Validate FHIR input data against schemas
- **Path Traversal Prevention**: Validate file paths to prevent directory traversal
- **Input Sanitization**: Sanitize user inputs before processing

## File System Security

### File Operations
- **Path Validation**: Validate file paths before read/write operations
- **Output Directory**: Generated files go to `src/Component/Models/src/`
- **Temporary Files**: Clean up temporary files after use
- **Permissions**: Ensure appropriate file permissions on generated code

### Generated Code Security
- **Code Injection Prevention**: Nette PhpGenerator handles escaping
- **Safe File Naming**: Use safe naming conventions for generated files
- **Namespace Safety**: Ensure generated namespaces don't conflict with system classes

## Network Security

### HTTP Operations
- **HTTPS**: Use HTTPS for FHIR package registry requests
- **Certificate Validation**: Validate SSL certificates
- **Timeouts**: Set appropriate timeouts to prevent hanging connections

### FHIR Package Downloads
- **Source Validation**: Only download from trusted FHIR package registries
- **Integrity Checks**: Verify package integrity via `CacheIntegrityManager`
- **Caching**: Packages cached in `var/cache/fhir-packages/`

## Dependency Security

### Composer Security
- **Lock File**: Commit `composer.lock` for reproducible builds
- **Audit**: Run `composer audit` to check for vulnerabilities
- **Updates**: Keep dependencies updated with security patches

## Runtime Security

### Error Handling
- **Information Disclosure**: Prevent information disclosure in error messages
- **Stack Traces**: Sanitize stack traces in production
- **Debug Mode**: Disable debug mode in production (`APP_ENV=prod`)

### Healthcare Data Considerations
- FHIR data may contain PHI (Protected Health Information)
- Follow HIPAA guidelines when applicable
- Don't log or store patient data unnecessarily
