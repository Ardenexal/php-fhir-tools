<?php

declare(strict_types=1);

/**
 * Build WASM PHP bundles for the GitHub Pages demo site.
 *
 * Packages PHP component source files into JSON bundles that can be loaded
 * by the browser and written to php-wasm's virtual filesystem.
 *
 * Usage: php scripts/build-wasm-bundles.php
 */

$projectRoot = dirname(__DIR__);
$outputDir = $projectRoot . '/docs/assets/php-bundles';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

/**
 * Recursively collect all PHP files from a directory into a VFS path map.
 *
 * @param string $sourceDir  Absolute path to source directory
 * @param string $vfsPrefix  Virtual filesystem path prefix
 * @return array<string, string> Map of VFS path => file contents
 */
function collectPhpFiles(string $sourceDir, string $vfsPrefix): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $relativePath = substr($file->getPathname(), strlen($sourceDir));
        $vfsPath = $vfsPrefix . $relativePath;
        $files[$vfsPath] = file_get_contents($file->getPathname());
    }

    ksort($files);
    return $files;
}

// --- Bootstrap autoloader ---

$bootstrap = <<<'PHP'
<?php

declare(strict_types=1);

/**
 * Minimal PSR-4 autoloader for php-wasm virtual filesystem.
 * Maps component namespaces to their VFS paths.
 */
spl_autoload_register(function (string $class): void {
    $prefixes = [
        'Ardenexal\\FHIRTools\\Component\\FHIRPath\\' => '/fhir/Component/FHIRPath/src/',
        'Ardenexal\\FHIRTools\\Component\\Serialization\\' => '/fhir/Component/Serialization/src/',
        'Ardenexal\\FHIRTools\\' => '/fhir/src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($class, $prefix, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});
PHP;

// --- Build FHIRPath bundle ---

echo "Building FHIRPath bundle...\n";

$fhirpathFiles = collectPhpFiles(
    $projectRoot . '/src/Component/FHIRPath/src',
    '/fhir/Component/FHIRPath/src'
);

// Include shared exception classes that FHIRPath may reference
$sharedExceptionDir = $projectRoot . '/src/Exception';
if (is_dir($sharedExceptionDir)) {
    $sharedFiles = collectPhpFiles($sharedExceptionDir, '/fhir/src/Exception');
    $fhirpathFiles = array_merge($fhirpathFiles, $sharedFiles);
}

$fhirpathFiles['/fhir/bootstrap.php'] = $bootstrap;

$fhirpathBundle = [
    'component' => 'FHIRPath',
    'fileCount' => count($fhirpathFiles),
    'files' => $fhirpathFiles,
];

$fhirpathJson = json_encode($fhirpathBundle, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents($outputDir . '/fhirpath-bundle.json', $fhirpathJson);
echo sprintf("  -> %d files, %s bytes\n", count($fhirpathFiles), number_format(strlen($fhirpathJson)));

// --- Build Serialization bundle (standalone subset) ---

echo "Building Serialization bundle (standalone subset)...\n";

$serializationFiles = [];

// Include only the standalone (non-Symfony) parts
$standaloneDirs = [
    'Context',
    'Exception',
    'Metadata',
    'Validator',
];

foreach ($standaloneDirs as $subdir) {
    $dir = $projectRoot . '/src/Component/Serialization/src/' . $subdir;
    if (is_dir($dir)) {
        $files = collectPhpFiles($dir, '/fhir/Component/Serialization/src/' . $subdir);
        $serializationFiles = array_merge($serializationFiles, $files);
    }
}

// Include standalone root-level files
$standaloneRootFiles = [
    'FHIRTypeResolver.php',
    'FHIRTypeResolverInterface.php',
];

foreach ($standaloneRootFiles as $filename) {
    $filepath = $projectRoot . '/src/Component/Serialization/src/' . $filename;
    if (file_exists($filepath)) {
        $vfsPath = '/fhir/Component/Serialization/src/' . $filename;
        $serializationFiles[$vfsPath] = file_get_contents($filepath);
    }
}

// Include shared exception classes
if (is_dir($sharedExceptionDir)) {
    $sharedFiles = collectPhpFiles($sharedExceptionDir, '/fhir/src/Exception');
    $serializationFiles = array_merge($serializationFiles, $sharedFiles);
}

$serializationFiles['/fhir/bootstrap.php'] = $bootstrap;

ksort($serializationFiles);

$serializationBundle = [
    'component' => 'Serialization',
    'fileCount' => count($serializationFiles),
    'files' => $serializationFiles,
];

$serializationJson = json_encode($serializationBundle, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents($outputDir . '/serialization-bundle.json', $serializationJson);
echo sprintf("  -> %d files, %s bytes\n", count($serializationFiles), number_format(strlen($serializationJson)));

echo "\nBundles written to: {$outputDir}/\n";
echo "Done.\n";
