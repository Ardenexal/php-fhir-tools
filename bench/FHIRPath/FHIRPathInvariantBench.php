<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\FHIRPath;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks FHIRPath on the workload validation gives it: every core invariant of every element
 * of real R4 examples, each evaluated against its own node with the resource as `%resource`.
 *
 * The examples come from the official FHIR test cases (a Patient, a Questionnaire, an Observation
 * and a 130 KB DiagnosticReport Bundle), so the expressions and data are the ones users meet. A
 * second subject runs search-parameter style paths over the same resources.
 */
#[Bench\Revs(1)]
#[Bench\Iterations(5)]
class FHIRPathInvariantBench
{
    private const EXAMPLES = [
        'patient-example.json',
        'diagnosticreport-example.json',
        'bundle-questionnaire.json',
        'observation-example.json',
    ];

    /** Search-parameter style expressions, each with the example it is evaluated on. */
    private const SEARCH_PATHS = [
        ['Patient.name.family', 'patient-example.json'],
        ["Patient.telecom.where(system = 'phone')", 'patient-example.json'],
        ['Patient.name.given | Patient.name.family', 'patient-example.json'],
        ['Bundle.entry.resource.ofType(Observation).code', 'diagnosticreport-example.json'],
        ['Bundle.entry.resource.ofType(Observation).value.ofType(Quantity)', 'diagnosticreport-example.json'],
        ['Bundle.entry.resource.ofType(DiagnosticReport).result.reference', 'diagnosticreport-example.json'],
        ['descendants().ofType(Reference).reference', 'diagnosticreport-example.json'],
        ['Observation.value.as(Quantity).value > 5', 'observation-example.json'],
        ['Questionnaire.repeat(item).linkId', 'bundle-questionnaire.json'],
    ];

    private FHIRPathService $service;

    /** @var array<string, object> */
    private array $resources = [];

    /** @var list<array{string, object, object}> expression, node it applies to, enclosing resource */
    private array $invariants = [];

    public function setUp(): void
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
        $directory  = dirname(__DIR__, 2) . '/vendor/fhir/fhir-test-cases/r4/examples/';

        foreach (self::EXAMPLES as $file) {
            $resource               = $serializer->deserialize((string) file_get_contents($directory . $file));
            $this->resources[$file] = $resource;

            foreach (self::objectsIn($resource) as $node) {
                foreach (self::invariantsOf($node::class) as $expression) {
                    $this->invariants[] = [$expression, $node, $resource];
                }
            }
        }

        // Parse every expression up front so the subjects measure evaluation, not parsing.
        $this->service = new FHIRPathService();
        foreach ($this->invariants as [$expression]) {
            $this->compile($expression);
        }
        foreach (self::SEARCH_PATHS as [$expression]) {
            $this->compile($expression);
        }
    }

    /**
     * Evaluate every non-best-practice core invariant on every element of the examples.
     */
    #[Bench\BeforeMethods(['setUp'])]
    public function benchCoreInvariants(): void
    {
        foreach ($this->invariants as [$expression, $node, $resource]) {
            try {
                $this->service->evaluate($expression, $node, new EvaluationContext($resource));
            } catch (FHIRPathException) {
                // Validation reports these as eval-errors; the cost up to the throw still counts.
            }
        }
    }

    /**
     * Evaluate search-parameter style paths against whole resources and a Bundle.
     */
    #[Bench\BeforeMethods(['setUp'])]
    public function benchSearchPaths(): void
    {
        foreach (self::SEARCH_PATHS as [$expression, $file]) {
            $this->service->evaluate($expression, $this->resources[$file]);
        }
    }

    private function compile(string $expression): void
    {
        try {
            $this->service->compile($expression);
        } catch (FHIRPathException) {
            // An expression the engine cannot parse fails the same way in both subjects.
        }
    }

    /**
     * The FHIRPath expressions of the non-best-practice invariants a class and its ancestors declare.
     *
     * @param class-string $class
     *
     * @return list<string>
     */
    private static function invariantsOf(string $class): array
    {
        $expressions = [];
        $reflection  = new \ReflectionClass($class);

        do {
            foreach ($reflection->getAttributes(FHIRPathInvariant::class) as $attribute) {
                $invariant = $attribute->newInstance();
                if (!$invariant->bestPractice) {
                    $expressions[] = $invariant->expression;
                }
            }
        } while (($reflection = $reflection->getParentClass()) !== false);

        return $expressions;
    }

    /**
     * Every model object reachable from a value, the value itself included.
     *
     * @return list<object>
     */
    private static function objectsIn(mixed $value): array
    {
        if (is_array($value)) {
            $objects = [];
            foreach ($value as $item) {
                array_push($objects, ...self::objectsIn($item));
            }

            return $objects;
        }

        if (!is_object($value) || $value instanceof \UnitEnum) {
            return [];
        }

        $objects = [$value];
        foreach (get_object_vars($value) as $property) {
            array_push($objects, ...self::objectsIn($property));
        }

        return $objects;
    }
}
