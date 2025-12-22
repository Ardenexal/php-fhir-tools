<?php

declare(strict_types=1);

/**
 * Test script for package installation functionality
 *
 * This script tests that all components can be loaded and their basic functionality works
 *
 * @author Claude AI Assistant
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

echo "🧪 Testing Package Installation Functionality...\n";
echo "================================================\n\n";

// Test 1: Bundle can be instantiated
echo "1. Testing FHIRBundle instantiation...\n";
try {
    $bundle = new FHIRBundle();
    echo "   ✅ FHIRBundle instantiated successfully\n";
} catch (Throwable $e) {
    echo '   ❌ FHIRBundle instantiation failed: ' . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: CodeGeneration component can be instantiated
echo "\n2. Testing CodeGeneration component...\n";
try {
    $context = new BuilderContext();
    echo "   ✅ BuilderContext instantiated successfully\n";
} catch (Throwable $e) {
    echo '   ❌ BuilderContext instantiation failed: ' . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Serialization component can be instantiated
echo "\n3. Testing Serialization component...\n";
try {
    // FHIRSerializationService requires dependencies, so we'll just test class loading
    if (class_exists(FHIRSerializationService::class)) {
        echo "   ✅ FHIRSerializationService class loaded successfully\n";
    } else {
        throw new Exception('FHIRSerializationService class not found');
    }
} catch (Throwable $e) {
    echo '   ❌ FHIRSerializationService loading failed: ' . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: Check autoloading works for all components
echo "\n4. Testing component autoloading...\n";
$testClasses = [
    'Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle',
    'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Context\\BuilderContext',
    'Ardenexal\\FHIRTools\\Component\\Serialization\\FHIRSerializationService',
];

foreach ($testClasses as $className) {
    if (class_exists($className)) {
        echo "   ✅ {$className} autoloaded successfully\n";
    } else {
        echo "   ❌ {$className} autoloading failed\n";
        exit(1);
    }
}

// Test 5: Check that component namespaces are properly configured
echo "\n5. Testing namespace configuration...\n";
$reflection   = new ReflectionClass(BuilderContext::class);
$actualPath   = str_replace('\\', '/', $reflection->getFileName());
$expectedPath = 'Component/CodeGeneration/src/Context/BuilderContext.php';
if (str_contains($actualPath, $expectedPath)) {
    echo "   ✅ CodeGeneration component namespace correctly configured\n";
} else {
    echo "   ❌ CodeGeneration component namespace misconfigured\n";
    echo "   Expected path to contain: {$expectedPath}\n";
    echo '   Actual path: ' . $actualPath . "\n";
    exit(1);
}

$reflection   = new ReflectionClass(FHIRSerializationService::class);
$actualPath   = str_replace('\\', '/', $reflection->getFileName());
$expectedPath = 'Component/Serialization/src/FHIRSerializationService.php';
if (str_contains($actualPath, $expectedPath)) {
    echo "   ✅ Serialization component namespace correctly configured\n";
} else {
    echo "   ❌ Serialization component namespace misconfigured\n";
    echo "   Expected path to contain: {$expectedPath}\n";
    echo '   Actual path: ' . $actualPath . "\n";
    exit(1);
}

echo "\n🎉 All package installation tests passed!\n";
echo "📦 Multi-component structure is working correctly.\n";

exit(0);
