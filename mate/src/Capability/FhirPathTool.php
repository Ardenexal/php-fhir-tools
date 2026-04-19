<?php

declare(strict_types=1);

namespace App\Mate\Capability;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Mcp\Capability\Attribute\McpTool;
use Symfony\AI\Mate\Encoding\ResponseEncoder;

/**
 * MCP tool for evaluating FHIRPath expressions against a FHIR resource.
 */
class FhirPathTool
{
    public function __construct(private readonly FHIRPathService $service)
    {
    }

    /**
     * @param string      $resource    FHIR resource as a JSON string (must include "resourceType").
     * @param string      $expression  FHIRPath expression to evaluate, e.g. "Patient.name.given".
     * @param string|null $fhirVersion Optional FHIR version hint: "R4", "R4B", or "R5".
     */
    #[McpTool('fhirpath-evaluate', 'Evaluate a FHIRPath expression against a JSON FHIR resource and return the result collection.')]
    public function evaluate(string $resource, string $expression, ?string $fhirVersion = null): string
    {
        try {
            $decoded = json_decode($resource, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return ResponseEncoder::encode(['error' => 'Invalid JSON: ' . $e->getMessage()]);
        }

        try {
            $collection = $this->service->evaluate($expression, $decoded, fhirVersion: $fhirVersion);
        } catch (FHIRPathException $e) {
            return ResponseEncoder::encode([
                'error'      => $e->getFullMessage(),
                'suggestion' => $e->getSuggestion(),
                'position'   => $e->getPosition(),
            ]);
        }

        return ResponseEncoder::encode([
            'count'    => $collection->count(),
            'isEmpty'  => $collection->isEmpty(),
            'isSingle' => $collection->isSingle(),
            'result'   => $collection->toArray(),
        ]);
    }
}
