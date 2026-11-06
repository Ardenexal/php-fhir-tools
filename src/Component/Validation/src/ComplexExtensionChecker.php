<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\ComplexExtensionSubExtensions;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtension;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtensionRecorder;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Report complex extensions whose sub-extensions do not fit their definition.
 *
 * Two shapes, both of which used to be invisible:
 *
 * - **A required sub-extension is missing.** The typed class cannot be built, so the reader keeps the
 *   element as a plain `Extension` and records why (see {@see IncompleteComplexExtension}). Nothing on
 *   the plain object says it was meant to be more; {@see check()} reports it from the record.
 * - **A sub-extension the definition does not declare.** The reader keeps it on the typed object after
 *   the declared ones (see {@see ComplexExtensionSubExtensions}); {@see checkUndeclaredSubExtensions()}
 *   reports one with a slice-style relative url. An absolute url names another extension, which open
 *   slicing allows, so it is left to the extension-context check.
 *
 * Findings use the reference validator's wording, less the package version it appends to the
 * canonical (`patient-animal|5.3.0-ballot`): the generated models do not carry it.
 */
final class ComplexExtensionChecker
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
     * Report the sub-extensions of a typed complex extension that its definition does not declare.
     *
     * The declared ones are found by building the class again from the same sub-extensions: its
     * constructor keeps exactly the slices it declares.
     *
     * @param FHIRComplexExtensionInterface $extension the typed extension
     * @param string                        $path      root-relative path to $extension
     *
     * @return list<FHIRValidationViolation> one error per undeclared slice-style sub-extension
     */
    public function checkUndeclaredSubExtensions(FHIRComplexExtensionInterface $extension, string $path): array
    {
        $read = $this->models->readInitializedValue($extension, 'extension');

        if (!is_array($read) || $read === []) {
            return [];
        }

        /** @var list<FHIRExtensionInterface> $read */
        $read = array_values(array_filter($read, static fn (mixed $sub): bool => $sub instanceof FHIRExtensionInterface));

        try {
            $rebuilt = $extension::fromSubExtensions($read);
        } catch (\InvalidArgumentException) {
            // Built by hand without a required slice; the reader never produces this shape.
            return [];
        }

        /** @var list<FHIRExtensionInterface> $declared */
        $declared   = $this->models->readInitializedValue($rebuilt, 'extension') ?? [];
        $url        = $extension->getExtensionUrl()                              ?? '';
        $violations = [];

        foreach (ComplexExtensionSubExtensions::undeclared($read, $declared) as $undeclared) {
            $subUrl = $undeclared->getExtensionUrl();

            // An absolute url is another extension, which open slicing allows. A declared slice
            // left over holds a value type the model did not capture, which is not this finding.
            if (!ComplexExtensionSubExtensions::isSliceUrl($subUrl)
                || ComplexExtensionSubExtensions::declaresSlice($extension::class, (string) $subUrl)
            ) {
                continue;
            }

            $violations[] = new FHIRValidationViolation(
                severity: 'error',
                path: sprintf('%s.extension[%d]', $path, (int) array_search($undeclared, $read, true)),
                message: sprintf("Sub-extension url '%s' is not defined by the Extension %s", (string) $subUrl, $url),
                constraintClass: self::class,
                profileGroup: null,
                invariantKey: null,
            );
        }

        return $violations;
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
