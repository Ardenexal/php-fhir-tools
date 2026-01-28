---
inclusion: always
---

# Security Guidelines for PHP FHIRTools

## Data Protection

### Sensitive Information Handling
- **No Secrets in Code**: Never hardcode API keys, passwords, or sensitive data
- **Environment Variables**: Use environment variables for configuration secrets
- **Log Sanitization**: Ensure logs don't contain sensitive information
- **Error Messages**: Don't expose internal system details in error messages

### Input Validation and Sanitization
- **FHIR Data Validation**: Validate all FHIR input data against schemas
- **Path Traversal Prevention**: Prevent directory traversal attacks in file operations
- **Input Sanitization**: Sanitize user inputs before processing
- **Command Injection Prevention**: Avoid executing user-provided commands

## File System Security

### File Operations
- **Path Validation**: Validate file paths to prevent unauthorized access
- **Permission Checks**: Verify file permissions before operations
- **Temporary Files**: Securely handle temporary files and cleanup
- **Output Directory Security**: Ensure output directories have appropriate permissions

### Generated Code Security
- **Code Injection Prevention**: Prevent code injection in generated PHP classes
- **Template Security**: Secure template processing to prevent template injection
- **File Naming**: Use safe file naming conventions to prevent conflicts
- **Namespace Security**: Ensure generated namespaces don't conflict with system classes

## Network Security

### HTTP Operations
- **HTTPS Only**: Use HTTPS for all network communications
- **Certificate Validation**: Validate SSL certificates for external requests
- **Timeout Configuration**: Set appropriate timeouts to prevent hanging connections
- **Rate Limiting**: Implement rate limiting for external API calls

### FHIR Package Downloads
- **Source Validation**: Validate FHIR package sources before downloading
- **Integrity Checks**: Verify package integrity using checksums when available
- **Size Limits**: Implement reasonable size limits for downloaded packages
- **Malware Prevention**: Consider scanning downloaded packages for malware

## Access Control

### File System Access
- **Principle of Least Privilege**: Run with minimal required permissions
- **Directory Restrictions**: Restrict file operations to designated directories
- **User Context**: Be aware of user context when performing file operations
- **Permission Validation**: Validate permissions before file operations

### Command Execution
- **Command Validation**: Validate all command inputs and parameters
- **Execution Context**: Be aware of execution context and privileges
- **Shell Injection Prevention**: Prevent shell injection attacks
- **Resource Limits**: Implement resource limits for command execution

## Dependency Security

### Third-Party Dependencies
- **Dependency Scanning**: Regularly scan dependencies for vulnerabilities
- **Version Pinning**: Pin dependency versions to avoid supply chain attacks
- **Security Updates**: Keep dependencies updated with security patches
- **Minimal Dependencies**: Use minimal required dependencies

### Composer Security
- **Lock File Management**: Commit composer.lock for reproducible builds
- **Package Verification**: Verify package signatures when available
- **Repository Security**: Use trusted package repositories
- **Audit Tools**: Use composer audit tools to check for vulnerabilities

## Runtime Security

### Error Handling Security
- **Information Disclosure**: Prevent information disclosure through error messages
- **Stack Trace Sanitization**: Sanitize stack traces in production
- **Logging Security**: Ensure logs don't contain sensitive information
- **Debug Mode**: Disable debug mode in production environments

### Memory Security
- **Memory Cleanup**: Clear sensitive data from memory after use
- **Memory Limits**: Set appropriate memory limits to prevent DoS
- **Resource Exhaustion**: Prevent resource exhaustion attacks
- **Garbage Collection**: Ensure proper garbage collection of sensitive data

## Compliance and Auditing

### Security Auditing
- **Code Reviews**: Conduct security-focused code reviews
- **Penetration Testing**: Perform regular security testing
- **Vulnerability Assessments**: Conduct regular vulnerability assessments
- **Security Monitoring**: Implement security monitoring and alerting

### Compliance Requirements
- **Data Protection**: Comply with data protection regulations
- **Healthcare Standards**: Follow healthcare data security standards when applicable
- **Industry Standards**: Adhere to relevant industry security standards
- **Documentation**: Document security measures and procedures