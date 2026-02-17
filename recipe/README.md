# Symfony Flex Recipe for FHIR Bundle

This directory contains the Symfony Flex recipe for `ardenexal/fhir-bundle`.

## What is this?

When users install the FHIR Bundle in their Symfony application via:

```bash
composer require ardenexal/fhir-bundle
```

Symfony Flex will automatically:
1. Register the bundle in `config/bundles.php`
2. Copy configuration files to `config/packages/`
3. Add environment variables to `.env`
4. Add entries to `.gitignore`

## Recipe Structure

```
recipe/fhir-bundle/1.0/
├── config/
│   └── packages/
│       ├── fhir.yaml           # Main configuration
│       ├── dev/fhir.yaml       # Development overrides
│       ├── prod/fhir.yaml      # Production overrides
│       └── test/fhir.yaml      # Test overrides
├── manifest.json               # Recipe manifest (what Flex does)
├── post-install.txt            # Message shown after installation
├── README.md                   # Recipe documentation
├── RECIPE_SUMMARY.md           # Quick reference
└── INSTALLATION.md             # Installation instructions
```

## Publishing to Symfony Flex

To make this recipe available publicly:

1. **For Official Recipes** (requires approval):
   - Submit a PR to: https://github.com/symfony/recipes
   - Requires community review and approval

2. **For Contrib Recipes** (easier):
   - Submit a PR to: https://github.com/symfony/recipes-contrib
   - Less strict requirements

3. **For Private/Testing**:
   - Keep in this repository
   - Users can add this repository as a Flex endpoint

## Recipe Version

This is version `1.0` of the recipe. The version structure is:

```
recipe/
└── fhir-bundle/        # Package name
    └── 1.0/           # Recipe version
```

When the bundle's API changes significantly, create a new version:
- `1.0/` - Initial version
- `2.0/` - Major changes requiring different configuration
- etc.

## Local Testing

To test this recipe locally before publishing:

1. Create a test Symfony application:
   ```bash
   symfony new test-app
   cd test-app
   ```

2. Add this repository to `composer.json`:
   ```json
   {
     "repositories": [
       {
         "type": "path",
         "url": "../path-to-fhir-tools"
       }
     ]
   }
   ```

3. Install the bundle:
   ```bash
   composer require ardenexal/fhir-bundle
   ```

4. Verify the recipe was applied:
   - Check `config/bundles.php`
   - Check `config/packages/fhir.yaml`
   - Check `.env` for FHIR variables

## Documentation

- See [README.md](1.0/README.md) for user-facing recipe documentation
- See [RECIPE_SUMMARY.md](1.0/RECIPE_SUMMARY.md) for quick reference
- See [INSTALLATION.md](1.0/INSTALLATION.md) for installation details

## Maintenance

When updating the recipe:

1. **For Patch Updates** (bug fixes, typos):
   - Update files in the current version directory
   - No version bump needed

2. **For Minor Updates** (new optional features):
   - Update files in the current version directory
   - Consider version bump if configuration changes significantly

3. **For Major Updates** (breaking changes):
   - Create a new version directory (e.g., `2.0/`)
   - Keep old version for backwards compatibility

## Related Files

The FHIR Bundle code is in: `src/Bundle/FHIRBundle/`

Each component has its own `composer.json`:
- `src/Bundle/FHIRBundle/composer.json`
- `src/Component/CodeGeneration/composer.json`
- `src/Component/Serialization/composer.json`
- `src/Component/FHIRPath/composer.json`
- `src/Component/Models/composer.json`
