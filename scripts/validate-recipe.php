<?php

declare(strict_types=1);

/**
 * Recipe validation script.
 *
 * This script validates that the Symfony Flex recipe is properly structured
 * and contains all required files and configurations.
 *
 * @author Ardenexal FHIRTools Team
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class RecipeValidator
{
    private string $recipeDir;

    private array $errors = [];

    private array $warnings = [];

    public function __construct(string $recipeDir)
    {
        $this->recipeDir = $recipeDir;
    }

    public function validate(): bool
    {
        $this->validateManifest();
        $this->validateConfigurationFiles();
        $this->validateDocumentation();
        $this->validateStructure();

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    private function validateManifest(): void
    {
        $manifestPath = $this->recipeDir . '/manifest.json';

        if (!file_exists($manifestPath)) {
            $this->errors[] = 'manifest.json is missing';

            return;
        }

        $content  = file_get_contents($manifestPath);
        $manifest = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->errors[] = 'manifest.json is not valid JSON: ' . json_last_error_msg();

            return;
        }

        // Validate required keys
        $requiredKeys = ['bundles', 'copy-from-recipe', 'env'];
        foreach ($requiredKeys as $key) {
            if (!isset($manifest[$key])) {
                $this->errors[] = "manifest.json is missing required key: {$key}";
            }
        }

        // Validate bundle registration
        if (isset($manifest['bundles'])) {
            $expectedBundle = 'Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle';
            if (!isset($manifest['bundles'][$expectedBundle])) {
                $this->errors[] = 'Bundle registration is missing or incorrect';
            }
        }

        // Validate environment variables
        if (isset($manifest['env'])) {
            $requiredEnvVars = [
                'FHIR_OUTPUT_DIRECTORY',
                'FHIR_CACHE_DIRECTORY',
                'FHIR_DEFAULT_VERSION',
                'FHIR_VALIDATION_ENABLED',
                'FHIR_VALIDATION_STRICT_MODE',
            ];

            foreach ($requiredEnvVars as $envVar) {
                if (!isset($manifest['env'][$envVar])) {
                    $this->errors[] = "Environment variable {$envVar} is missing from manifest";
                }
            }
        }
    }

    private function validateConfigurationFiles(): void
    {
        $configFiles = [
            'config/packages/fhir.yaml',
            'config/packages/dev/fhir.yaml',
            'config/packages/prod/fhir.yaml',
            'config/packages/test/fhir.yaml',
        ];

        foreach ($configFiles as $configFile) {
            $fullPath = $this->recipeDir . '/' . $configFile;

            if (!file_exists($fullPath)) {
                $this->errors[] = "Configuration file {$configFile} is missing";
                continue;
            }

            try {
                $config = Yaml::parseFile($fullPath);

                if (!is_array($config) || !isset($config['fhir'])) {
                    $this->errors[] = "Configuration file {$configFile} is invalid or missing 'fhir' key";
                }
            } catch (Exception $e) {
                $this->errors[] = "Configuration file {$configFile} has invalid YAML: " . $e->getMessage();
            }
        }

        // Validate main configuration structure
        $mainConfigPath = $this->recipeDir . '/config/packages/fhir.yaml';
        if (file_exists($mainConfigPath)) {
            try {
                $config     = Yaml::parseFile($mainConfigPath);
                $fhirConfig = $config['fhir'] ?? [];

                $requiredKeys = ['output_directory', 'cache_directory', 'default_version', 'validation', 'packages'];
                foreach ($requiredKeys as $key) {
                    if (!isset($fhirConfig[$key])) {
                        $this->errors[] = "Main configuration is missing required key: {$key}";
                    }
                }

                // Validate validation structure
                if (isset($fhirConfig['validation'])) {
                    if (!isset($fhirConfig['validation']['enabled']) || !isset($fhirConfig['validation']['strict_mode'])) {
                        $this->errors[] = 'Validation configuration is incomplete';
                    }
                }

                // Validate packages structure
                if (isset($fhirConfig['packages'])) {
                    if (!is_array($fhirConfig['packages']) || empty($fhirConfig['packages'])) {
                        $this->warnings[] = 'No FHIR packages configured';
                    }
                }
            } catch (Exception $e) {
                $this->errors[] = 'Failed to validate main configuration: ' . $e->getMessage();
            }
        }
    }

    private function validateDocumentation(): void
    {
        $docFiles = [
            'README.md',
            'post-install.txt',
            'INSTALLATION.md',
        ];

        foreach ($docFiles as $docFile) {
            $fullPath = $this->recipeDir . '/' . $docFile;

            if (!file_exists($fullPath)) {
                $this->warnings[] = "Documentation file {$docFile} is missing";
                continue;
            }

            $content = file_get_contents($fullPath);
            if (empty(trim($content))) {
                $this->warnings[] = "Documentation file {$docFile} is empty";
            }
        }
    }

    private function validateStructure(): void
    {
        $requiredDirs = [
            'config',
            'config/packages',
            'config/packages/dev',
            'config/packages/prod',
            'config/packages/test',
        ];

        foreach ($requiredDirs as $dir) {
            $fullPath = $this->recipeDir . '/' . $dir;
            if (!is_dir($fullPath)) {
                $this->errors[] = "Required directory {$dir} is missing";
            }
        }
    }
}

// Main execution
$recipeDir = __DIR__ . '/../recipes/fhir-bundle/1.0';

if (!is_dir($recipeDir)) {
    echo "❌ Recipe directory not found: {$recipeDir}\n";
    exit(1);
}

$validator = new RecipeValidator($recipeDir);
$isValid   = $validator->validate();

echo "🔍 Validating FHIR Bundle Symfony Flex Recipe...\n\n";

$errors   = $validator->getErrors();
$warnings = $validator->getWarnings();

if (!empty($errors)) {
    echo "❌ Validation Errors:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  Validation Warnings:\n";
    foreach ($warnings as $warning) {
        echo "  - {$warning}\n";
    }
    echo "\n";
}

if ($isValid) {
    echo "✅ Recipe validation passed!\n";
    echo "📦 Recipe is ready for Symfony Flex installation.\n";
    exit(0);
}
echo "❌ Recipe validation failed!\n";
echo "🔧 Please fix the errors above before using the recipe.\n";
exit(1);
