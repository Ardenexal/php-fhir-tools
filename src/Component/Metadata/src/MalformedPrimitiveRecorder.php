<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * Side table carrying unreadable `boolean` and `integer` text from deserialization to validation.
 *
 * The same shape as {@see UnknownInputRecorder}, for the same reason: validation receives only the
 * model, and the model has no room for the text. Entries are keyed on the object the value was read
 * into, so nothing needs resetting between documents, and `WeakMap` bounds the table.
 *
 * A record describes the document as read. Changing the property afterwards does not clear it, so
 * validating an object that was deserialized and then edited still reports the original text.
 */
final class MalformedPrimitiveRecorder
{
    /**
     * @var \WeakMap<object, list<MalformedPrimitive>>|null lazily created, so an unused table costs nothing
     */
    private static ?\WeakMap $records = null;

    /**
     * Record a value read into a property of $target that its type could not hold as written.
     *
     * @param object             $target    the model object the property belongs to
     * @param MalformedPrimitive $primitive what was wrong, and the text as written
     */
    public static function record(object $target, MalformedPrimitive $primitive): void
    {
        $records = self::$records ??= new \WeakMap();

        $existing = $records[$target] ?? [];

        $records[$target] = [...$existing, $primitive];
    }

    /**
     * Has anything been recorded at all?
     *
     * Lets validation skip the walk on a clean document. A table whose entries have all been collected
     * counts as empty too; see {@see UnknownInputRecorder::isEmpty()} for why that matters.
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
     * @return list<MalformedPrimitive> in the order recorded; empty when there are none
     */
    public static function forObject(object $target): array
    {
        if (self::$records === null) {
            return [];
        }

        return self::$records[$target] ?? [];
    }

    /**
     * Drop every record. For tests; production code never needs it, because records die with their objects.
     */
    public static function reset(): void
    {
        self::$records = null;
    }
}
