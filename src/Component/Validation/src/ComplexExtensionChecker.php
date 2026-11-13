<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\ComplexExtensionSubExtensions;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtension;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtensionRecorder;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledExtension;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Report complex extensions whose sub-extensions do not fit their definition.
 *
 * Two shapes, both of which used to be invisible, on a typed class or on a plain `Extension` checked
 * against a definition loaded at runtime ({@see checkAgainstDefinition()}):
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
    /**
     * FHIR types a generated class holds as a `<Name>Primitive` object: FHIRExtensionGenerator's list
     * less `decimal`, which it maps to a PHP string before the list is read.
     */
    private const array PRIMITIVE_TYPES = [
        'string', 'integer64', 'uri', 'url', 'canonical', 'base64Binary', 'instant',
        'date', 'dateTime', 'time', 'code', 'oid', 'id', 'markdown', 'unsignedInt',
        'positiveInt', 'uuid', 'xhtml',
    ];

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
     * Check a plain `Extension` against the definition of its url, as its generated class would be checked.
     *
     * A generated complex extension takes each sub-extension slice by url and by the first type its
     * value allows. When it cannot fill a required slice the reader keeps the plain object, and
     * {@see check()} reports the first such slice; this method reports it here instead. Only once
     * every required slice is filled does {@see checkUndeclaredSubExtensions()} look for slice-style
     * urls the definition does not declare, naming each by its place in the list the typed class
     * rebuilt. A plain extension whose definition was loaded at runtime gets those findings in the
     * same words and at the same paths. Findings inside a sub-extension's value are not this
     * method's: the typed class also exposes each value as a property, so those can be reported at
     * a different path on a typed class.
     *
     * @param FHIRExtensionInterface $extension  the plain extension
     * @param CompiledExtension      $definition its definition
     * @param string                 $release    'R4', 'R4B' or 'R5': the release of the model classes values are read as
     * @param string                 $path       root-relative path to $extension
     *
     * @return list<FHIRValidationViolation> the missing required slice, or each undeclared sub-extension
     */
    public function checkAgainstDefinition(FHIRExtensionInterface $extension, CompiledExtension $definition, string $release, string $path): array
    {
        if (!$definition->isComplex()) {
            return [];
        }

        $read = $this->models->isPropertyInitialized($extension, 'extension') ? $this->models->readInitializedValue($extension, 'extension') : [];
        /** @var list<FHIRExtensionInterface> $read */
        $read = array_values(array_filter(is_array($read) ? $read : [], static fn (mixed $sub): bool => $sub instanceof FHIRExtensionInterface));

        foreach ($definition->subExtensions as $slice) {
            if (!$slice->isRequired()) {
                continue;
            }

            $filled = false;
            foreach ($read as $sub) {
                if ($this->urlOf($sub) === $slice->sliceName && self::holds(self::valueOf($sub), $slice->valueType, $release)) {
                    $filled = true;

                    break;
                }
            }

            if (!$filled) {
                return [$this->violation(new IncompleteComplexExtension($extension::class, $slice->sliceName, 'missing'), $extension, $path)];
            }
        }

        // The generated class rebuilds its sub-extension list as the ones its slices took, then the
        // ones left over in document order, and an undeclared one is named by its place in that
        // list. Each slice takes every matching sub-extension when it repeats, and the last one when
        // it does not, as the generated fromSubExtensions() assigns them. The reader then accounts
        // for each value taken by the first sub-extension with the same url and value
        // (ComplexExtensionSubExtensions::undeclared()), which for equal scalars is not the one taken.
        $taken = [];
        foreach ($definition->subExtensions as $slice) {
            $matches = [];
            foreach ($read as $index => $sub) {
                if ($this->urlOf($sub) === $slice->sliceName && self::holds(self::valueOf($sub), $slice->valueType, $release)) {
                    $matches[] = $index;
                }
            }
            foreach ($slice->repeats() ? $matches : array_slice($matches, -1) as $match) {
                foreach ($read as $index => $sub) {
                    if (!isset($taken[$index]) && $this->urlOf($sub) === $slice->sliceName && self::valueOf($sub) === self::valueOf($read[$match])) {
                        $taken[$index] = true;

                        break;
                    }
                }
            }
        }

        // A slice-style url is declared when ComplexExtensionSubExtensions::declaresSlice() would
        // find a generated constructor parameter for it: the slice's own name, the name with `Slice`
        // appended where it is reserved (`valueSlice`), either with `Slice` removed, and `id`
        $declared = ['id'];
        foreach ($definition->subExtensions as $slice) {
            $name       = self::comparable($slice->sliceName);
            $parameter  = in_array($name, ['value', 'url', 'id', 'extension'], true) ? $name . 'slice' : $name;
            $declared[] = $parameter;
            if (str_ends_with($parameter, 'slice')) {
                $declared[] = substr($parameter, 0, -5);
            }
        }

        $position   = count($taken);
        $violations = [];
        foreach ($read as $index => $sub) {
            if (isset($taken[$index])) {
                continue;
            }
            $place  = $position++;
            $subUrl = $this->urlOf($sub);
            if (!ComplexExtensionSubExtensions::isSliceUrl($subUrl) || in_array(self::comparable($subUrl), $declared, true)) {
                continue;
            }

            $violations[] = new FHIRValidationViolation(
                severity: 'error',
                path: sprintf('%s.extension[%d]', $path, $place),
                message: sprintf("Sub-extension url '%s' is not defined by the Extension %s", $subUrl, $definition->url),
                constraintClass: self::class,
                profileGroup: null,
                invariantKey: null,
            );
        }

        return $violations;
    }

    private static function valueOf(FHIRExtensionInterface $sub): mixed
    {
        return property_exists($sub, 'value') ? ($sub->value ?? null) : null;
    }

    /**
     * Whether a sub-extension value has the type a generated class would accept for its slice.
     *
     * Mirrors the generated `fromSubExtensions()` check: scalars by PHP type, everything else as an
     * instance of the release's model class for the type. A type with no model class in the release
     * accepts any object. That covers an IG's own data type, where the generated class would name
     * the IG's class, and a core type the release has no class for (`integer64` before R5, a
     * profile name such as `SimpleQuantity`), where the generated class names a class that does not
     * exist and so never matches. Refusing those would report a missing slice that is there.
     */
    private static function holds(mixed $value, ?string $type, string $release): bool
    {
        return match ($type) {
            null                                                                                         => $value !== null,
            'boolean', 'http://hl7.org/fhirpath/System.Boolean'                                          => is_bool($value),
            'integer', 'http://hl7.org/fhirpath/System.Integer'                                          => is_int($value),
            'decimal', 'http://hl7.org/fhirpath/System.Decimal', 'http://hl7.org/fhirpath/System.String' => is_string($value),
            default                                                                                      => self::holdsModel($value, $type, $release),
        };
    }

    private static function holdsModel(mixed $value, string $type, string $release): bool
    {
        $namespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$release}\\";
        $class     = in_array($type, self::PRIMITIVE_TYPES, true)
            ? $namespace . 'Primitive\\' . ucfirst($type) . 'Primitive'
            : $namespace . 'DataType\\' . ucfirst($type);

        return class_exists($class) ? $value instanceof $class : is_object($value);
    }

    /**
     * A slice or url compared the way {@see ComplexExtensionSubExtensions::declaresSlice()} compares them.
     */
    private static function comparable(string $name): string
    {
        return strtolower((string) preg_replace('/[^A-Za-z0-9]/', '', $name));
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

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
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
