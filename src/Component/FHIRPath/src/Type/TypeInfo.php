<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Type;

/**
 * Represents type information returned by the FHIRPath type() function.
 *
 * Per FHIRPath specification, type() returns a ClassInfo structure with
 * namespace and name properties identifying the runtime type of a value.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class TypeInfo
{
    /**
     * @param string $namespace The type namespace (either 'System' or 'FHIR')
     * @param string $name      The type name (e.g., 'Integer', 'Patient', 'boolean')
     */
    public function __construct(
        public readonly string $namespace,
        public readonly string $name
    ) {
    }

    /**
     * Create TypeInfo for a System type (primitive PHP types).
     *
     * @param string $name The type name (e.g., 'Integer', 'String', 'Boolean')
     *
     * @return self
     */
    public static function system(string $name): self
    {
        return new self('System', $name);
    }

    /**
     * Create TypeInfo for a FHIR type (FHIR resources and primitives).
     *
     * @param string $name The type name (e.g., 'Patient', 'boolean', 'string')
     *
     * @return self
     */
    public static function fhir(string $name): self
    {
        return new self('FHIR', $name);
    }
}
