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
     * @param string      $resource    FHIR resource as a JSON string (must include "resourceType")
     * @param string      $expression  FHIRPath expression to evaluate, e.g. "Patient.name.given".
     * @param string|null $fhirVersion optional FHIR version hint: "R4", "R4B", or "R5"
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
        } catch (\InvalidArgumentException $e) {
            // $fhirVersion arrives as free text from the caller, and the type resolver rejects
            // anything that is not a release label. Encoding it as a tool error keeps the failure
            // legible; letting it escape would surface as a transport-level fault with no hint that
            // one argument was at fault.
            return ResponseEncoder::encode([
                'error'      => $e->getMessage(),
                'suggestion' => 'Pass fhirVersion as a release label — "R4", "R4B" or "R5" — or omit it to search all three.',
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
