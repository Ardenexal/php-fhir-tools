<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * A registry holds a StructureDefinition it cannot read: the file is missing, unreadable, or not a
 * JSON object.
 *
 * {@see RuntimeProfileResolver} turns it into a profile that is reported as not checked, so one
 * broken file in a package never stops validation of resources that do not use it.
 *
 * @author Ardenexal
 */
final class UnreadableStructureDefinitionException extends \RuntimeException
{
}
