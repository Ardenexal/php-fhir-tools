<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Test directory cleanup functionality in FHIRModelGeneratorCommand
 *
 * This test validates the logic used by the FHIRModelGeneratorCommand
 * to clean output directories before regenerating FHIR models.
 *
 * @author FHIR Tools
 */
class FHIRModelGeneratorCommandDirectoryCleanupTest extends TestCase
{
    private Filesystem $filesystem;

    private string $testOutputDir;

    private string $testModelsDir;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();

        // Create temporary test directories
        $this->testOutputDir = sys_get_temp_dir() . '/fhir-test-output-' . uniqid();
        $this->testModelsDir = sys_get_temp_dir() . '/fhir-test-models-' . uniqid();

        $this->filesystem->mkdir($this->testOutputDir);
        $this->filesystem->mkdir($this->testModelsDir);
    }

    protected function tearDown(): void
    {
        // Clean up test directories
        if ($this->filesystem->exists($this->testOutputDir)) {
            $this->filesystem->remove($this->testOutputDir);
        }
        if ($this->filesystem->exists($this->testModelsDir)) {
            $this->filesystem->remove($this->testModelsDir);
        }
    }

    public function testOutputDirectoryCleanupLogic(): void
    {
        // Create some test files in the output directory
        $testFile1 = $this->testOutputDir . '/test1.php';
        $testFile2 = $this->testOutputDir . '/subdir/test2.php';

        $this->filesystem->dumpFile($testFile1, '<?php echo "test1";');
        $this->filesystem->dumpFile($testFile2, '<?php echo "test2";');

        // Verify files exist
        self::assertFileExists($testFile1);
        self::assertFileExists($testFile2);

        // Simulate the clearOutputDirectory logic
        if ($this->filesystem->exists($this->testOutputDir)) {
            $this->filesystem->remove($this->testOutputDir);
        }
        $this->filesystem->mkdir($this->testOutputDir, 0755);

        // Verify the directory exists but is empty
        self::assertDirectoryExists($this->testOutputDir);
        self::assertEmpty(glob($this->testOutputDir . '/*'));
    }

    public function testModelsComponentDirectoryCleanupLogic(): void
    {
        // Create some test version directories with files
        $r4Dir         = $this->testModelsDir . '/R4';
        $r4bDir        = $this->testModelsDir . '/R4B';
        $r5Dir         = $this->testModelsDir . '/R5';
        $nonVersionDir = $this->testModelsDir . '/NotAVersion';

        $this->filesystem->mkdir([$r4Dir, $r4bDir, $r5Dir, $nonVersionDir]);

        $this->filesystem->dumpFile($r4Dir . '/test.php', '<?php echo "R4";');
        $this->filesystem->dumpFile($r4bDir . '/test.php', '<?php echo "R4B";');
        $this->filesystem->dumpFile($r5Dir . '/test.php', '<?php echo "R5";');
        $this->filesystem->dumpFile($nonVersionDir . '/test.php', '<?php echo "NotAVersion";');

        // Verify directories exist
        self::assertDirectoryExists($r4Dir);
        self::assertDirectoryExists($r4bDir);
        self::assertDirectoryExists($r5Dir);
        self::assertDirectoryExists($nonVersionDir);

        // Simulate the clearModelsComponentOutputDirectory logic
        if ($this->filesystem->exists($this->testModelsDir)) {
            // Get all version directories (R4, R4B, R5, etc.)
            $versionDirs = glob($this->testModelsDir . '/*', GLOB_ONLYDIR);

            foreach ($versionDirs as $versionDir) {
                $versionName = basename($versionDir);
                // Only clear directories that look like FHIR version names
                if (preg_match('/^R\d+[A-Z]*$/', $versionName)) {
                    $this->filesystem->remove($versionDir);
                }
            }
        }

        // Ensure the base directory exists
        $this->filesystem->mkdir($this->testModelsDir, 0755);

        // Verify version directories are removed
        self::assertDirectoryDoesNotExist($r4Dir);
        self::assertDirectoryDoesNotExist($r4bDir);
        self::assertDirectoryDoesNotExist($r5Dir);

        // Verify non-version directory remains
        self::assertDirectoryExists($nonVersionDir);
        self::assertFileExists($nonVersionDir . '/test.php');
    }

    public function testVersionDirectoryPatternMatching(): void
    {
        // Test the regex pattern used for version directory detection
        $validVersions   = ['R4', 'R4B', 'R5', 'R6', 'R10A', 'R99Z'];
        $invalidVersions = ['r4', 'R', 'R4b', 'Version4', 'NotAVersion', 'src', 'docs'];

        foreach ($validVersions as $version) {
            self::assertTrue(
                preg_match('/^R\d+[A-Z]*$/', $version) === 1,
                "Version '{$version}' should match the pattern",
            );
        }

        foreach ($invalidVersions as $version) {
            self::assertFalse(
                preg_match('/^R\d+[A-Z]*$/', $version) === 1,
                "Version '{$version}' should not match the pattern",
            );
        }
    }

    public function testDirectoryCleanupPreservesNonVersionDirectories(): void
    {
        // Create mixed directories
        $r4Dir      = $this->testModelsDir . '/R4';
        $readmeFile = $this->testModelsDir . '/README.md';
        $srcDir     = $this->testModelsDir . '/src';
        $docsDir    = $this->testModelsDir . '/docs';

        $this->filesystem->mkdir([$r4Dir, $srcDir, $docsDir]);
        $this->filesystem->dumpFile($r4Dir . '/test.php', '<?php echo "R4";');
        $this->filesystem->dumpFile($readmeFile, '# README');
        $this->filesystem->dumpFile($srcDir . '/test.php', '<?php echo "src";');
        $this->filesystem->dumpFile($docsDir . '/guide.md', '# Guide');

        // Verify all exist
        self::assertDirectoryExists($r4Dir);
        self::assertFileExists($readmeFile);
        self::assertDirectoryExists($srcDir);
        self::assertDirectoryExists($docsDir);

        // Simulate cleanup logic
        $versionDirs = glob($this->testModelsDir . '/*', GLOB_ONLYDIR);

        foreach ($versionDirs as $versionDir) {
            $versionName = basename($versionDir);
            if (preg_match('/^R\d+[A-Z]*$/', $versionName)) {
                $this->filesystem->remove($versionDir);
            }
        }

        // Verify only version directories are removed
        self::assertDirectoryDoesNotExist($r4Dir);
        self::assertFileExists($readmeFile);
        self::assertDirectoryExists($srcDir);
        self::assertDirectoryExists($docsDir);
        self::assertFileExists($srcDir . '/test.php');
        self::assertFileExists($docsDir . '/guide.md');
    }
}
