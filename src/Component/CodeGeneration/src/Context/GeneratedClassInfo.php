<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Context;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;

/**
 * Data Transfer Object for storing generated class information
 *
 * This class encapsulates all metadata about a generated FHIR class,
 * including the class itself, its namespace, FHIR URL, and fully qualified
 * class name for easy searchability.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
final readonly class GeneratedClassInfo
{
    /**
     * The fully qualified class name (namespace + class name)
     */
    public string $fqcn;

    /**
     * @param ClassType|EnumType $class     The generated class or enum
     * @param string             $namespace The namespace the class is assigned to
     * @param string             $fhirUrl   The FHIR URL for this definition
     */
    public function __construct(
        public ClassType|EnumType $class,
        public string $namespace,
        public string $fhirUrl,
    ) {
        $className  = $this->class->getName() ?? '';
        $this->fqcn = $this->namespace !== '' && $className !== ''
            ? '\\' . $this->namespace . '\\' . $className
            : $className;
    }

    /**
     * Get the short class name (without namespace)
     */
    public function getClassName(): string
    {
        return $this->class->getName() ?? '';
    }

    /**
     * Check if this is an enum type
     */
    public function isEnum(): bool
    {
        return $this->class instanceof EnumType;
    }

    /**
     * Check if this is a class type
     */
    public function isClass(): bool
    {
        return $this->class instanceof ClassType;
    }

    /**
     * Get the class as ClassType (throws if it's an enum)
     *
     * @throws \LogicException if the class is not a ClassType
     */
    public function asClassType(): ClassType
    {
        if (!$this->class instanceof ClassType) {
            throw new \LogicException('Cannot get ClassType: this is an EnumType');
        }

        return $this->class;
    }

    /**
     * Get the class as EnumType (throws if it's a class)
     *
     * @throws \LogicException if the class is not an EnumType
     */
    public function asEnumType(): EnumType
    {
        if (!$this->class instanceof EnumType) {
            throw new \LogicException('Cannot get EnumType: this is a ClassType');
        }

        return $this->class;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
