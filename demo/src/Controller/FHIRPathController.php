<?php

declare(strict_types=1);

namespace App\Controller;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Interactive FHIRPath expression playground.
 *
 * Allows users to evaluate and validate FHIRPath 2.0 expressions
 * against optional JSON context data.
 */
#[Route('/fhirpath', name: 'app_fhirpath')]
class FHIRPathController extends AbstractController
{
    public function __construct(
        private readonly FHIRPathService $fhirPathService,
    ) {
    }

    /** Render the FHIRPath playground form. */
    #[Route('', name: '', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('fhirpath/index.html.twig', [
            'expression' => '',
            'context'    => '',
            'action'     => null,
            'result'     => null,
            'error'      => null,
        ]);
    }

    /** Handle evaluate or validate form submissions. */
    #[Route('/evaluate', name: '_evaluate', methods: ['POST'])]
    public function evaluate(Request $request): Response
    {
        $expression = trim((string) $request->request->get('expression', ''));
        $contextRaw = trim((string) $request->request->get('context', ''));
        $action     = (string) $request->request->get('submit_action', 'evaluate');

        $result = null;
        $error  = null;

        if ($expression === '') {
            $error = 'Please enter a FHIRPath expression.';

            return $this->render('fhirpath/index.html.twig', [
                'expression' => $expression,
                'context'    => $contextRaw,
                'action'     => $action,
                'result'     => null,
                'error'      => $error,
            ]);
        }

        if ($action === 'validate') {
            // Syntax validation only — no resource needed
            try {
                $valid  = $this->fhirPathService->validate($expression);
                $result = ['valid' => $valid, 'items' => []];
            } catch (\Throwable $e) {
                $error = 'Validation error: ' . $e->getMessage();
            }
        } else {
            // Evaluate expression against optional JSON context
            $resource = null;

            if ($contextRaw !== '') {
                try {
                    $decoded = json_decode($contextRaw, true, 512, JSON_THROW_ON_ERROR);
                    $resource = $decoded;
                } catch (\JsonException $e) {
                    $error = 'Invalid JSON context: ' . $e->getMessage();
                }
            }

            if ($error === null) {
                try {
                    $collection = $this->fhirPathService->evaluate($expression, $resource ?? []);
                    $items      = [];

                    foreach ($collection as $item) {
                        $items[] = $this->formatItem($item);
                    }

                    $result = ['valid' => true, 'items' => $items, 'count' => count($collection)];
                } catch (FHIRPathException $e) {
                    $error = 'FHIRPath error: ' . $e->getMessage();
                } catch (\Throwable $e) {
                    $error = 'Unexpected error: ' . $e->getMessage();
                }
            }
        }

        return $this->render('fhirpath/index.html.twig', [
            'expression' => $expression,
            'context'    => $contextRaw,
            'action'     => $action,
            'result'     => $result,
            'error'      => $error,
        ]);
    }

    /**
     * Format a collection item for display.
     *
     * @param mixed $item
     */
    private function formatItem(mixed $item): string
    {
        if (is_array($item)) {
            return json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
        }

        if (is_bool($item)) {
            return $item ? 'true' : 'false';
        }

        if (is_null($item)) {
            return 'null';
        }

        if (is_object($item)) {
            return json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
        }

        return (string) $item;
    }
}
