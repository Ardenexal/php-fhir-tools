<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;

/**
 * Reads values out of FHIR ElementDefinition arrays, as found in a StructureDefinition's
 * differential or snapshot.
 *
 * Shared by code generation and runtime profile compilation, so both read an element the same way.
 *
 * @author Ardenexal
 */
final class ElementDefinitionReader
{
    /**
     * Returns true when the element definition delegates its structure to another element via
     * contentReference. Such elements must not receive value constraints because the constraints
     * live on the referenced element, not on this one.
     *
     * @param array<string, mixed> $element
     */
    public static function hasContentReference(array $element): bool
    {
        return isset($element['contentReference']);
    }

    /**
     * Finds a FHIR polymorphic field (e.g. fixedString, patternCodeableConcept) in an element
     * definition by scanning for a key that starts with the given prefix.
     *
     * Returns an array with 'type' (the suffix after the prefix, e.g. 'String') and 'value'
     * (the raw field value), or null when no matching key is found.
     *
     * @param array<string, mixed> $element
     *
     * @return array{type: string, value: mixed}|null
     */
    public static function extractPolymorphicField(array $element, string $prefix): ?array
    {
        foreach ($element as $key => $value) {
            if ($key !== $prefix && str_starts_with($key, $prefix)) {
                return ['type' => substr($key, strlen($prefix)), 'value' => $value];
            }
        }

        return null;
    }

    private function __construct()
    {
    }

    /**
     * The target profile lists an element's Reference and canonical types restrict it to, one entry
     * per #[FHIRTargetProfile] the element needs.
     *
     * Reference and CodeableReference types are one kind, canonical types the other. A kind with any
     * type that lists no targetProfile allows any target, so it gets no entry. When the element has
     * only one kind, or both kinds list the same targets, the one entry names no kind, so it applies
     * to every value. Otherwise each restricted kind gets its own entry naming that kind: a merged list
     * would check `Reference(Group) | canonical(EvidenceVariable)` references against EvidenceVariable.
     *
     * @param array<string, mixed> $element
     *
     * @return list<array{targetProfiles: list<string>, kind?: string}>
     */
    public static function targetProfileRules(array $element): array
    {
        /** @var array<string, list<string>|null> $byKind kind => targets, null once a type of that kind is unrestricted */
        $byKind = [];
        foreach (is_array($element['type'] ?? null) ? $element['type'] : [] as $type) {
            $kind = is_array($type) ? self::targetKind($type['code'] ?? null) : null;
            if ($kind === null) {
                continue;
            }

            $targets = array_values(array_filter(
                is_array($type['targetProfile'] ?? null) ? $type['targetProfile'] : [],
                static fn (mixed $url): bool => is_string($url) && $url !== '',
            ));

            if (array_key_exists($kind, $byKind) && $byKind[$kind] === null) {
                continue;
            }

            /** @var list<string> $targets */
            $byKind[$kind] = $targets === [] ? null : array_values(array_unique([...$byKind[$kind] ?? [], ...$targets]));
        }

        $restricted = array_filter($byKind, static fn (?array $targets): bool => $targets !== null);
        if ($restricted === []) {
            return [];
        }

        if (count($byKind) === 1 || (count($restricted) === 2 && count(array_unique(array_map(static fn (array $targets): string => implode(' ', $targets), $restricted))) === 1)) {
            /** @var list<string> $targets */
            $targets = reset($restricted);

            return [['targetProfiles' => $targets]];
        }

        $rules = [];
        foreach ($restricted as $kind => $targets) {
            $rules[] = ['targetProfiles' => $targets, 'kind' => $kind];
        }

        return $rules;
    }

    /**
     * The FHIRTargetProfile kind a type code belongs to, or null for a type that has no targets.
     */
    private static function targetKind(mixed $code): ?string
    {
        return match ($code) {
            'Reference', 'CodeableReference' => FHIRTargetProfile::KIND_REFERENCE,
            'canonical'                      => FHIRTargetProfile::KIND_CANONICAL,
            default                          => null,
        };
    }
}
