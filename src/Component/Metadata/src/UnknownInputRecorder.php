<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * Side table carrying unplaceable input from deserialization to validation.
 *
 * Nothing between the two carries it: `FHIRValidationServiceInterface::validate()` receives the
 * resource and nothing else, and the serialization context is not reachable from there. The store
 * is static because the writer and the reader hold no shared instance.
 *
 * Entries are keyed on the object the input was read into, so there is no shared bucket to reset
 * between documents and a read that throws cannot leak onto the next. `WeakMap` bounds the table.
 */
final class UnknownInputRecorder
{
    /**
     * @var \WeakMap<object, list<UnknownInput>>|null lazily created, so an unused table costs nothing
     */
    private static ?\WeakMap $records = null;

    /**
     * Record input read into $target that no property of $target could hold.
     *
     * @param object       $target the model object the document element was being read into
     * @param UnknownInput $input  the element or property that could not be placed
     */
    public static function record(object $target, UnknownInput $input): void
    {
        /** @var \WeakMap<object, list<UnknownInput>> $records the table, created on first write */
        $records = self::$records ??= new \WeakMap();

        $existing = $records[$target] ?? [];

        $records[$target] = [...$existing, $input];
    }

    /**
     * Has anything been recorded at all?
     *
     * Lets a reader skip walking an object graph that cannot hold a record. Clean documents are the
     * overwhelming majority, and a reflection walk per resource costs more than double the R4
     * conformance harness wall-clock.
     *
     * A table that has been written to and has since emptied counts as empty. Checking only for a null
     * field would latch this false for the rest of the process: the `WeakMap` stays allocated after every
     * entry it held has been collected, so one document carrying unknown input would cost every later
     * clean resource the full walk this guard exists to skip.
     *
     * @return bool true when the table holds no records
     */
    public static function isEmpty(): bool
    {
        return self::$records === null || self::$records->count() === 0;
    }

    /**
     * Read back everything recorded against one object.
     *
     * @param object $target the model object to look up
     *
     * @return list<UnknownInput> what could not be placed on $target, in the order it was read;
     *                            empty when the object was read from a document with no unknown input
     */
    public static function forObject(object $target): array
    {
        if (self::$records === null) {
            return [];
        }

        return self::$records[$target] ?? [];
    }

    /**
     * Drop the whole table.
     *
     * For tests that assert on an empty starting state. Production code never needs this: entries
     * are keyed on objects and expire with them.
     */
    public static function reset(): void
    {
        self::$records = null;
    }
}
