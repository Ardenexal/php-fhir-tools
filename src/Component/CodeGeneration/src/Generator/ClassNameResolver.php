<?php

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use function Symfony\Component\String\u;

class ClassNameResolver
{
    private const array DEFINITION_TO_CLASS_OVERRIDES = [
        'http://hl7.org/fhir/ValueSet/claim-use' => 'ClaimUse',
    ];

    /**
     * PHP reserved words that cannot be used as a class name (case-insensitive). A logical-model
     * type code such as the CDA datatype `INT` pascal-cases to a value that collides with the
     * reserved `int` type keyword; these get a `Type` suffix via {@see logicalModelClassName()}.
     */
    private const array RESERVED_CLASS_NAMES = [
        'int', 'float', 'bool', 'string', 'true', 'false', 'null', 'void',
        'iterable', 'object', 'mixed', 'never', 'self', 'parent', 'static',
        'enum', 'callable', 'array',
    ];

    public static function resolveClassName(string $definitionUrl, string $definitionName): string
    {
        return self::DEFINITION_TO_CLASS_OVERRIDES[$definitionUrl] ?? u($definitionName)->pascal()->toString();
    }

    /**
     * Class name for a logical-model (CDA) type: the standard resolved name, with a `Type` suffix
     * appended when it would otherwise be a PHP reserved word (e.g. `INT` → `INTType`). Kept
     * separate from {@see resolveClassName()} so FHIR primitive naming (e.g. `StringPrimitive`)
     * is unaffected.
     */
    public static function logicalModelClassName(string $definitionUrl, string $definitionName): string
    {
        $name = self::resolveClassName($definitionUrl, $definitionName);

        if (in_array(strtolower($name), self::RESERVED_CLASS_NAMES, true)) {
            $name .= 'Type';
        }

        return $name;
    }

    /**
     * Class name for a CDA ValueSet enum: the resolved name with a leading `CDA` qualifier
     * stripped (the bundled CDA ValueSets are named `CDANullFlavor`, `CDAActClass`, … but the
     * generated enums live in the CDA-specific `CdaModels\Enum\` namespace, so the prefix is
     * redundant — e.g. `CDANullFlavor` → `NullFlavor`). The reserved-word `Type` suffix from
     * {@see logicalModelClassName()} still applies (e.g. a hypothetical `CDAInt` → `IntType`).
     */
    public static function cdaEnumClassName(string $definitionUrl, string $definitionName): string
    {
        $name = self::logicalModelClassName($definitionUrl, $definitionName);

        // Strip the redundant `CDA` qualifier when the remainder still starts a valid PascalCase
        // class name (e.g. `CDANullFlavor` → `NullFlavor`). All 26 bundled CDA ValueSet names are
        // `CDA` + an uppercase word, so this is exact; the guard keeps a hypothetical non-prefixed
        // name like `Cdash...` untouched.
        if (str_starts_with($name, 'CDA') && strlen($name) > 3 && ctype_upper($name[3])) {
            $name = substr($name, 3);
        }

        return $name;
    }
}
