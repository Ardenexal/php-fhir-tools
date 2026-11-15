<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * Side table carrying complex extensions read as plain `Extension` from deserialization to validation.
 *
 * Same shape and reasoning as {@see UnknownInputRecorder}: `validate()` receives only the resource,
 * so what the reader learned travels on a static table keyed on the object it was read into. The
 * `WeakMap` drops an entry with its object, so nothing needs resetting between documents.
 */
final class IncompleteComplexExtensionRecorder
{
    /**
     * @var \WeakMap<object, IncompleteComplexExtension>|null lazily created, so an unused table costs nothing
     */
    private static ?\WeakMap $records = null;

    /**
     * Record that $target is the plain fallback for a complex extension that could not be built.
     *
     * @param object                     $target the plain `Extension` the element was read into
     * @param IncompleteComplexExtension $record what it could not become, and why
     */
    public static function record(object $target, IncompleteComplexExtension $record): void
    {
        /** @var \WeakMap<object, IncompleteComplexExtension> $records the table, created on first write */
        $records = self::$records ??= new \WeakMap();

        $records[$target] = $record;
    }

    /**
     * Has anything been recorded at all?
     *
     * Lets a reader skip walking an object graph that cannot hold a record. Counts a table whose
     * entries have all been collected as empty, for the reason {@see UnknownInputRecorder::isEmpty()}
     * gives.
     *
     * @return bool true when the table holds no records
     */
    public static function isEmpty(): bool
    {
        return self::$records === null || self::$records->count() === 0;
    }

    /**
     * Read back the record for one object.
     *
     * @param object $target the model object to look up
     *
     * @return list<IncompleteComplexExtension> the record for $target, or an empty list when it was
     *                                          read as the class it resolved to
     */
    public static function forObject(object $target): array
    {
        if (self::$records === null || !isset(self::$records[$target])) {
            return [];
        }

        return [self::$records[$target]];
    }

    /**
     * Drop the whole table. For tests that assert on an empty starting state.
     */
    public static function reset(): void
    {
        self::$records = null;
    }
}
