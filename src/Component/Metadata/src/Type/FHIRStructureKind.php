<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * What kind of FHIR structure a generated class represents.
 *
 * The generator marks every model class with exactly one of these through a class-level attribute.
 * Before this enum existed each caller asked the question by reading the attribute it happened to
 * care about, which meant "is this a backbone element" and "is this a complex type" were answered by
 * different code in different components with no shared notion of the answers being exclusive.
 *
 * Extension definitions are deliberately absent. A class marked `#[FHIRExtensionDefinition]` also
 * carries a structural attribute -- an extension is still a complex type -- so extension-ness is a
 * separate question, asked through {@see FHIRStructureKindProviderInterface::isExtensionDefinition()}.
 *
 * @author Ardenexal
 */
enum FHIRStructureKind: string
{
    case Resource        = 'resource';
    case ComplexType     = 'complexType';
    case PrimitiveType   = 'primitive';
    case BackboneElement = 'backboneElement';
    case LogicalModel    = 'logicalModel';
}
