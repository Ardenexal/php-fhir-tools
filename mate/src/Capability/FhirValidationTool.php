<?php

declare(strict_types=1);

namespace App\Mate\Capability;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Mcp\Capability\Attribute\McpTool;
use Symfony\AI\Mate\Encoding\ResponseEncoder;

/**
 * MCP tool for validating a FHIR resource against its profile constraints.
 */
class FhirValidationTool
{
    public function __construct(
        private readonly FHIRSerializationService $serialization,
        private readonly FHIRValidationService $validation,
    ) {
    }

    /**
     * @param string   $data                 FHIR resource as a JSON or XML string. Format is auto-detected.
     * @param string[] $profileUrls          Optional list of profile canonical URLs to validate against.
     * @param bool     $includeMustSupportInfo When true, adds info-level violations for unpopulated must-support fields.
     */
    #[McpTool('fhir-validate', 'Deserialize a FHIR JSON or XML resource and run validation, returning a structured report of errors, warnings, and informational findings.')]
    public function validate(
        string $data,
        array $profileUrls = [],
        bool $includeMustSupportInfo = false,
    ): string {
        try {
            $object = $this->serialization->deserialize($data);
        } catch (FHIRSerializationException $e) {
            return ResponseEncoder::encode([
                'error' => 'Deserialization failed: ' . $e->getMessage(),
            ]);
        }

        $report = $this->validation->validate(
            $object,
            profileUrls: array_values($profileUrls),
            includeMustSupportInfo: $includeMustSupportInfo,
        );

        $mapViolation = static fn (FHIRValidationViolation $v): array => array_filter([
            'severity'     => $v->severity,
            'path'         => $v->path,
            'message'      => $v->message,
            'invariantKey' => $v->invariantKey,
            'profileGroup' => $v->profileGroup,
        ], static fn (mixed $val): bool => $val !== null && $val !== '');

        return ResponseEncoder::encode([
            'isValid'      => $report->isValid(),
            'errorCount'   => count($report->errors()),
            'warningCount' => count($report->warnings()),
            'infoCount'    => count($report->info()),
            'violations'   => array_map($mapViolation, $report->violations),
        ]);
    }
}
