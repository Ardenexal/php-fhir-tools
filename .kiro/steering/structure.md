# Project Structure

## Multi-Component Monorepo Architecture

PHP FHIRTools is organized as a monorepo containing multiple independent components that can be used standalone or together through the Symfony Bundle.

## Root Directory Structure

```
php-fhir-tools/
├── src/                           # Source code
│   ├── Bundle/FHIRBundle/         # Symfony Bundle integration
│   ├── Component/                 # Standalone components
│   │   ├── CodeGeneration/        # FHIR class generation
│   │   ├── Serialization/         # FHIR JSON/XML serialization
│   │   ├── FHIRPath/             # FHIRPath expression engine
│   │   └── Models/               # Generated FHIR model classes
│   ├── Exception/                # Root-level exceptions
│   └── (legacy files)            # Backward compatibility
├── tests/                        # Test suites
├── docs/                         # Documentation
├── config/                       # Symfony configuration
├── bin/                          # Console entry points
├── public/                       # Web entry point
├── var/                          # Runtime files (cache, logs)
├── vendor/                       # Composer dependencies
└── composer.json                 # Root package definition
```

## Component Organization

### FHIRBundle (`src/Bundle/FHIRBundle/`)
Symfony Bundle for framework integration:
```
FHIRBundle/
├── FHIRBundle.php                # Bundle class
├── DependencyInjection/          # Service configuration
│   ├── FHIRExtension.php         # Bundle extension
│   ├── Configuration.php         # Configuration tree
│   └── Compiler/                 # Compiler passes
├── Command/                      # Console commands
├── Resources/config/             # Service definitions
├── Compatibility/                # Version compatibility
├── composer.json                 # Bundle package definition
└── README.md                     # Bundle documentation
```

### CodeGeneration Component (`src/Component/CodeGeneration/`)
Standalone FHIR class generator:
```
CodeGeneration/
├── src/                          # Component source
│   ├── Generator/                # Code generators
│   ├── Package/                  # FHIR package handling
│   ├── Context/                  # Generation context
│   ├── Configuration/            # Generator configuration
│   ├── Service/                  # Core services
│   ├── Command/                  # Console commands
│   ├── Attributes/               # PHP attributes
│   └── Exception/                # Component exceptions
├── tests/                        # Component tests
├── output/                       # Generated code output
├── composer.json                 # Component package definition
└── README.md                     # Component documentation
```

### Serialization Component (`src/Component/Serialization/`)
Standalone FHIR serialization:
```
Serialization/
├── src/                          # Component source
│   ├── Normalizer/               # Symfony normalizers
│   ├── Validator/                # FHIR validators
│   ├── Context/                  # Serialization context
│   ├── Metadata/                 # Type metadata
│   ├── Exception/                # Component exceptions
│   ├── FHIRSerializationService.php
│   ├── FHIRTypeResolver.php
│   └── FHIRTypeResolverInterface.php
├── tests/                        # Component tests
├── composer.json                 # Component package definition
└── README.md                     # Component documentation
```

### FHIRPath Component (`src/Component/FHIRPath/`)
FHIRPath expression evaluation:
```
FHIRPath/
├── src/                          # Component source
│   ├── Parser/                   # Expression parsing
│   ├── Evaluator/                # Expression evaluation
│   ├── Function/                 # FHIRPath functions
│   ├── Expression/               # Expression types
│   ├── Type/                     # FHIRPath type system
│   ├── Service/                  # Core services
│   ├── Cache/                    # Expression caching
│   └── Exception/                # Component exceptions
├── tests/                        # Component tests
├── composer.json                 # Component package definition
└── README.md                     # Component documentation
```

## Test Organization

```
tests/
├── Unit/                         # Unit tests
│   ├── Bundle/FHIRBundle/        # Bundle unit tests
│   ├── Component/                # Component unit tests
│   │   ├── CodeGeneration/       # CodeGeneration tests
│   │   ├── Serialization/        # Serialization tests
│   │   └── Models/               # Models tests
│   └── Exception/                # Exception tests
├── Integration/                  # Integration tests
│   ├── Package/                  # Package integration
│   ├── FHIRModelGeneratorIntegrationTest.php
│   ├── FHIRSerializationIntegrationTest.php
│   └── CommandIntegrationTest.php
├── FHIR/                         # FHIR-specific tests
├── Fixtures/                     # Test data
│   ├── FHIR/                     # FHIR test resources
│   ├── OfficialFHIR/             # Official FHIR examples
│   └── StructureDefinitions/     # Test structure definitions
├── Utilities/                    # Test utilities
└── bootstrap.php                 # Test bootstrap
```

## Configuration Structure

```
config/
├── bundles.php                   # Bundle registration
├── services.yaml                 # Service configuration
├── packages/                     # Package configurations
├── routes/                       # Routing configuration
├── recipes/fhir-bundle/          # Symfony Flex recipe
└── services/                     # FHIR-specific services
    ├── fhir_bundle_commands.yaml
    ├── fhir_code_generation.yaml
    ├── fhir_path.yaml
    └── fhir_serialization.yaml
```

## Documentation Structure

```
docs/
├── architecture.md               # Multi-project architecture
├── migration-guide.md            # Migration instructions
├── FHIR-Serialization-Guide.md   # Serialization guide
├── component-guides/             # Component-specific guides
│   ├── fhir-bundle.md            # Bundle guide
│   ├── code-generation.md        # CodeGeneration guide
│   ├── serialization.md          # Serialization guide
│   └── fhir-path.md              # FHIRPath guide
└── FHIRPATH_*.md                 # FHIRPath documentation
```

## Namespace Conventions

### Component Namespaces
- **Root**: `Ardenexal\FHIRTools\`
- **Bundle**: `Ardenexal\FHIRTools\Bundle\FHIRBundle\`
- **CodeGeneration**: `Ardenexal\FHIRTools\Component\CodeGeneration\`
- **Serialization**: `Ardenexal\FHIRTools\Component\Serialization\`
- **FHIRPath**: `Ardenexal\FHIRTools\Component\FHIRPath\`
- **Models**: `Ardenexal\FHIRTools\Component\Models\`

### Generated Code Namespaces
Generated FHIR classes use configurable namespaces:
- **Default Pattern**: `{BaseNamespace}\{Version}\{ResourceType}`
- **Example**: `App\FHIR\R4\Patient`

## File Naming Conventions

### PHP Classes
- **PascalCase**: `FHIRModelGenerator.php`
- **Suffix Patterns**: 
  - Commands: `*Command.php`
  - Services: `*Service.php`
  - Exceptions: `*Exception.php`
  - Interfaces: `*Interface.php`

### Test Files
- **Pattern**: `{ClassUnderTest}Test.php`
- **Integration**: `*IntegrationTest.php`
- **Property Tests**: `*PropertyTest.php`

### Configuration Files
- **YAML**: `.yaml` extension preferred
- **Environment**: `.env` files for configuration
- **Composer**: `composer.json` for package definitions

## Output Organization

Generated FHIR models are organized by version and type:
```
output/
└── Ardenexal/FhirTools/
    ├── R4/                       # FHIR R4 models
    ├── R4B/                      # FHIR R4B models
    └── R5/                       # FHIR R5 models
```

## Dependency Boundaries

### Component Independence
- Each component can be used standalone
- Clear interface definitions between components
- Minimal cross-component dependencies

### Bundle Integration
- FHIRBundle depends on all components
- Components do not depend on the bundle
- Symfony-specific code isolated in bundle
