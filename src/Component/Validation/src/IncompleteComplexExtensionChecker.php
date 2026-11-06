<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtension;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtensionRecorder;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Report complex extensions the deserializer had to read as plain `Extension`.
 *
 * A complex extension missing a required sub-extension cannot become its typed class, so the reader
 * keeps it as a plain `Extension` and records why (see {@see IncompleteComplexExtension}). Nothing on
 * the plain object says it was meant to be more, so without this pass the document validates clean.
 *
 * The slice finding uses the reference validator's wording, less the package version it appends
 * to the canonical (`patient-animal|5.3.0-ballot`): the generated models do not carry it.
 */
final class IncompleteComplexExtensionChecker
{
    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
    ) {
    }

    /**
     * Collect a violation for every incomplete complex extension anywhere under $resource.
     *
     * @param object $resource the deserialized root to walk
     *
     * @return list<FHIRValidationViolation> one error per incomplete extension, empty when there are none
     */
    public function check(object $resource): array
    {
        // Skips the reflection walk for the normal case, as UnknownInputChecker::check() does.
        if (IncompleteComplexExtensionRecorder::isEmpty()) {
            return [];
        }

        $visited = [];

        return $this->walk($resource, '', $visited);
    }

    /**
     * @param object           $node    the object to read records off and descend from
     * @param string           $path    root-relative dotted path to $node, '' at the root
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation> findings from $node and everything beneath it
     */
    private function walk(object $node, string $path, array &$visited): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;

        $violations = [];

        foreach (IncompleteComplexExtensionRecorder::forObject($node) as $record) {
            $violations[] = $this->violation($record, $node, $path);
        }

        foreach ($this->models->publicPropertyNames($node) as $name) {
            if (!$this->models->isPropertyInitialized($node, $name)) {
                continue;
            }

            $value    = $this->models->readInitializedValue($node, $name);
            $propPath = $path === '' ? $name : $path . '.' . $name;

            if (is_object($value)) {
                $violations = [...$violations, ...$this->walk($value, $propPath, $visited)];

                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            foreach ($value as $index => $item) {
                if (is_object($item)) {
                    $violations = [...$violations, ...$this->walk($item, sprintf('%s[%s]', $propPath, $index), $visited)];
                }
            }
        }

        return $violations;
    }

    /**
     * @param IncompleteComplexExtension $record what the extension could not become, and why
     * @param object                     $node   the plain extension it was read as, which carries the url
     * @param string                     $path   root-relative dotted path to $node
     *
     * @return FHIRValidationViolation the error, pathed at the extension itself
     */
    private function violation(IncompleteComplexExtension $record, object $node, string $path): FHIRValidationViolation
    {
        $url = $this->urlOf($node);

        $message = $record->sliceName !== null
            ? sprintf(
                "Slice 'Extension.extension:%s': a matching slice is required, but not found (from %s). "
                . 'Note that other slices are allowed in addition to this required slice',
                $record->sliceName,
                $url,
            )
            : sprintf('The extension %s does not match its definition: %s', $url, $record->reason);

        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * @param object $node the plain extension
     *
     * @return string its url, or '' when it has none (a separate finding reports that)
     */
    private function urlOf(object $node): string
    {
        if (!$this->models->isPropertyInitialized($node, 'url')) {
            return '';
        }

        $url = $this->models->readInitializedValue($node, 'url');

        return is_string($url) ? $url : '';
    }
}
