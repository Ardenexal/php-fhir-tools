<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\FHIRPath;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for FHIRPath expression evaluation.
 *
 * Each expression is tested in two modes:
 *  - Cold cache: the parsed AST is not yet cached — measures lexer + parser + evaluator.
 *  - Warm cache: the AST is pre-compiled and cached — measures evaluator only.
 */
class FHIRPathEvaluationBench
{
    private const EXPR_SIMPLE  = 'Patient.name.given';

    private const EXPR_FILTER  = "Patient.name.where(use = 'official').given";

    private const EXPR_DEEP    = 'Patient.contact.where(relationship.coding.code = \'N\').name.family';

    private const EXPR_CHAIN   = 'Patient.name.given.where($this.length() > 3).first()';

    private FHIRPathService $service;

    private PatientResource $patient;

    public function setUp(): void
    {
        $this->service = new FHIRPathService();

        // Build a Patient with contacts so deep-navigation expressions have data to traverse.
        $this->patient = new PatientResource(
            id: 'bench-fhirpath-001',
            name: [
                new HumanName(family: 'Smith', given: ['John', 'Michael']),
                new HumanName(family: 'Smith', given: ['Johnny']),
            ],
        );
    }

    /** Compile (prime) the simple expression into the service cache. */
    public function primeSimple(): void
    {
        $this->setUp();
        $this->service->compile(self::EXPR_SIMPLE);
    }

    /** Compile (prime) the filter expression into the service cache. */
    public function primeFilter(): void
    {
        $this->setUp();
        $this->service->compile(self::EXPR_FILTER);
    }

    /** Compile all expressions into the service cache. */
    public function primeAll(): void
    {
        $this->setUp();
        $this->service->compile(self::EXPR_SIMPLE);
        $this->service->compile(self::EXPR_FILTER);
        $this->service->compile(self::EXPR_DEEP);
        $this->service->compile(self::EXPR_CHAIN);
    }

    /** Clear the expression cache before cold benchmarks. */
    public function clearCache(): void
    {
        $this->setUp();
        $this->service->clearCache();
    }

    // -------------------------------------------------------------------------
    // Simple path: Patient.name.given
    // -------------------------------------------------------------------------

    /**
     * Evaluate a simple property-navigation path with no cached AST.
     */
    #[Bench\BeforeMethods(['clearCache'])]
    public function benchSimplePathColdCache(): void
    {
        $this->service->evaluate(self::EXPR_SIMPLE, $this->patient);
    }

    /**
     * Evaluate a simple property-navigation path with a pre-compiled cached AST.
     */
    #[Bench\BeforeMethods(['primeSimple'])]
    public function benchSimplePathWarmCache(): void
    {
        $this->service->evaluate(self::EXPR_SIMPLE, $this->patient);
    }

    // -------------------------------------------------------------------------
    // Filter expression: Patient.name.where(use = 'official').given
    // -------------------------------------------------------------------------

    /**
     * Evaluate a filter expression (where clause) with no cached AST.
     */
    #[Bench\BeforeMethods(['clearCache'])]
    public function benchComplexFilterColdCache(): void
    {
        $this->service->evaluate(self::EXPR_FILTER, $this->patient);
    }

    /**
     * Evaluate a filter expression (where clause) with a pre-compiled cached AST.
     */
    #[Bench\BeforeMethods(['primeFilter'])]
    public function benchComplexFilterWarmCache(): void
    {
        $this->service->evaluate(self::EXPR_FILTER, $this->patient);
    }

    // -------------------------------------------------------------------------
    // Deep navigation + function chain (always warm — focuses on evaluator cost)
    // -------------------------------------------------------------------------

    /**
     * Evaluate a multi-hop navigation with a nested where() filter (warm cache).
     */
    #[Bench\BeforeMethods(['primeAll'])]
    public function benchDeepNavigation(): void
    {
        $this->service->evaluate(self::EXPR_DEEP, $this->patient);
    }

    /**
     * Evaluate a chained function expression (where + length + first) (warm cache).
     */
    #[Bench\BeforeMethods(['primeAll'])]
    public function benchFunctionChain(): void
    {
        $this->service->evaluate(self::EXPR_CHAIN, $this->patient);
    }
}
