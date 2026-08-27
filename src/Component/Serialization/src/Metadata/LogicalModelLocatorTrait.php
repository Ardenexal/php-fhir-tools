<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

/**
 * Locates the class-level #[LogicalModel] attribute for an object or class name.
 *
 * CDA datatypes and clinical classes carry #[LogicalModel] rather than #[FhirResource]/
 * #[FHIRComplexType], and AU classes inherit it from their core parent, so the lookup walks the
 * parent chain. Shared by the CDA XML/JSON normalizers and the serialization service so the
 * parent-chain walk lives in one place rather than being reimplemented per call site.
 *
 * @author Ardenexal
 */
trait LogicalModelLocatorTrait
{
    /**
     * Return the #[LogicalModel] attribute declared by the subject's class or an ancestor, or null
     * when none is present (i.e. the subject is not a CDA logical model).
     *
     * @param object|class-string|string $subject An instance or fully-qualified class name
     */
    private function findLogicalModelAttribute(object|string $subject): ?LogicalModel
    {
        if (is_string($subject) && !class_exists($subject)) {
            return null;
        }

        try {
            $refl = new \ReflectionClass($subject);

            do {
                $attributes = $refl->getAttributes(LogicalModel::class);

                if ($attributes !== []) {
                    return $attributes[0]->newInstance();
                }

                $refl = $refl->getParentClass();
            } while ($refl !== false);

            return null;
        } catch (\ReflectionException) {
            return null;
        }
    }
}
