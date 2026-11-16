<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRPathResourceContext;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantFastPaths;
use PHPUnit\Framework\TestCase;

/**
 * Every pass the invariant fast paths report must be a pass the FHIRPath engine reports too.
 *
 * The fast paths skip the engine, so a wrong answer would hide a real failure without any other test
 * noticing: the conformance tests only count findings per case. This walks every object in every JSON
 * case of the FHIR validator test corpus, takes each invariant its class declares that has a fast
 * path, and evaluates it both ways at the same position, with the same `%resource` binding the
 * invariant validator uses.
 */
final class FHIRPathInvariantFastPathsCorpusTest extends TestCase
{
    private const array VERSIONS = ['4.0' => FhirVersion::R4, '4.3' => FhirVersion::R4B, '5.0' => FhirVersion::R5];

    private const array FAST_EXPRESSIONS = [
        FHIRPathInvariantFastPaths::ELE_1,
        FHIRPathInvariantFastPaths::ELE_1_R5,
        FHIRPathInvariantFastPaths::EXT_1,
        FHIRPathInvariantFastPaths::REF_1_R4,
        FHIRPathInvariantFastPaths::REF_1_R4B,
        FHIRPathInvariantFastPaths::REF_1_R5,
    ];

    private FHIRPathService $paths;

    /** @var array<class-string, list<string>> fast-path expressions each class declares, its ancestors' included */
    private array $expressionsByClass = [];

    /** @var array<string, int> how many fast-path passes were checked, per expression */
    private array $checked = [];

    /** @var list<string> */
    private array $disagreements = [];

    public function testEveryFastPathPassIsAPassForTheEngineAcrossTheValidatorCorpus(): void
    {
        $directory = dirname(__DIR__, 5) . '/vendor/fhir/fhir-test-cases/validator/';
        if (!is_file($directory . 'manifest.json')) {
            self::markTestSkipped('fhir/fhir-test-cases is not installed.');
        }

        $this->paths = new FHIRPathService();

        /** @var array{test-cases: list<array<string, mixed>>} $manifest */
        $manifest    = json_decode((string) file_get_contents($directory . 'manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        $serializers = [];

        foreach ($manifest['test-cases'] as $case) {
            $file    = is_string($case['file'] ?? null) ? $case['file'] : '';
            $version = self::VERSIONS[is_string($case['version'] ?? null) ? $case['version'] : ''] ?? null;

            if ($version === null || !str_ends_with($file, '.json') || !is_file($directory . $file)) {
                continue;
            }

            $json = (string) file_get_contents($directory . $file);
            $data = json_decode($json, true);
            if (!is_array($data) || !is_string($data['resourceType'] ?? null)) {
                continue;
            }

            $class = sprintf('Ardenexal\\FHIRTools\\Component\\Models\\%s\\Resource\\%sResource', $version->value, $data['resourceType']);
            if (!class_exists($class)) {
                continue;
            }

            try {
                $serializers[$version->value] ??= FHIRSerializationService::createDefault($version);
                $resource = $serializers[$version->value]->deserializeFromJson($json, $class);
            } catch (\Throwable) {
                // The conformance tests own unreadable cases; there is nothing here to compare
                continue;
            }

            $visited = [];
            $this->walk($resource, $resource, '', $file, $visited);
        }

        self::assertSame([], $this->disagreements, 'the fast paths must never pass what the engine fails');

        // Without these the test could pass by checking nothing at all
        self::assertGreaterThan(1000, $this->checked[FHIRPathInvariantFastPaths::ELE_1] ?? 0, 'R4 ele-1 passes checked');
        self::assertGreaterThan(100, $this->checked[FHIRPathInvariantFastPaths::ELE_1_R5] ?? 0, 'R5 ele-1 passes checked');
        self::assertGreaterThan(100, $this->checked[FHIRPathInvariantFastPaths::REF_1_R4] ?? 0, 'R4 ref-1 passes checked');
        self::assertGreaterThan(10, $this->checked[FHIRPathInvariantFastPaths::EXT_1] ?? 0, 'ext-1 passes checked');
    }

    /**
     * @param array<int, true> $visited
     */
    private function walk(object $node, object $root, string $path, string $file, array &$visited): void
    {
        if (isset($visited[spl_object_id($node)])) {
            return;
        }
        $visited[spl_object_id($node)] = true;

        foreach ($this->fastExpressionsOf($node) as $expression) {
            $this->compare($expression, $node, $root, $path, $file);
        }

        foreach (get_object_vars($node) as $name => $value) {
            $childPath = $path === '' ? (string) $name : $path . '.' . $name;

            if (is_object($value)) {
                $this->walk($value, $root, $childPath, $file, $visited);
            } elseif (is_array($value)) {
                foreach ($value as $index => $item) {
                    if (is_object($item)) {
                        $this->walk($item, $root, $childPath . '[' . $index . ']', $file, $visited);
                    }
                }
            }
        }
    }

    private function compare(string $expression, object $node, object $root, string $path, string $file): void
    {
        if (FHIRPathInvariantFastPaths::passes($expression, $node) !== true) {
            return;
        }

        $this->checked[$expression] = ($this->checked[$expression] ?? 0) + 1;

        try {
            $result = $this->paths->evaluate($expression, $node, FHIRPathResourceContext::at($root, $path));
        } catch (FHIRPathException $exception) {
            $this->disagreements[] = sprintf('%s %s: engine threw %s', $file, $path, $exception->getMessage());

            return;
        }

        // The invariant validator treats an empty result as a pass, exactly as it treats true
        if ($result->isEmpty() || ($result->count() === 1 && $result->first() === true)) {
            return;
        }

        $this->disagreements[] = sprintf('%s %s: `%s` fails in the engine', $file, $path === '' ? '(root)' : $path, $expression);
    }

    /**
     * @return list<string>
     */
    private function fastExpressionsOf(object $node): array
    {
        $class = $node::class;

        if (isset($this->expressionsByClass[$class])) {
            return $this->expressionsByClass[$class];
        }

        $expressions = [];
        for ($current = new \ReflectionClass($node); $current !== false; $current = $current->getParentClass()) {
            foreach ($current->getAttributes(FHIRPathInvariant::class) as $attribute) {
                $expression = $attribute->newInstance()->expression;

                if (in_array($expression, self::FAST_EXPRESSIONS, true) && !in_array($expression, $expressions, true)) {
                    $expressions[] = $expression;
                }
            }
        }

        return $this->expressionsByClass[$class] = $expressions;
    }
}
