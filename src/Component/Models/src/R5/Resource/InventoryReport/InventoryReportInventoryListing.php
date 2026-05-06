<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryReport;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;

/**
 * @description An inventory listing section (grouped by any of the attributes).
 */
#[FHIRBackboneElement(parentResource: 'InventoryReport', elementPath: 'InventoryReport.inventoryListing', fhirVersion: 'R5')]
class InventoryReportInventoryListing extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Reference|null location Location of the inventory items */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $location = null,
        /** @var CodeableConcept|null itemStatus The status of the items that are being reported */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $itemStatus = null,
        /** @var DateTimePrimitive|null countingDateTime The date and time when the items were counted */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $countingDateTime = null,
        /** @var array<InventoryReportInventoryListingItem> item The item or items in this listing */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InventoryReport\InventoryReportInventoryListingItem',
        )]
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
