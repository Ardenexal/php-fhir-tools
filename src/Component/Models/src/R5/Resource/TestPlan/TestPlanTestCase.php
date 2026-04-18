<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\TestPlan;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @description The individual test cases that are part of this plan, when they they are made explicit.
 */
#[FHIRBackboneElement(parentResource: 'TestPlan', elementPath: 'TestPlan.testCase', fhirVersion: 'R5')]
class TestPlanTestCase extends BackboneElement
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
        /** @var int|null sequence Sequence of test case in the test plan */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $sequence = null,
        /** @var array<Reference> scope The scope or artifact covered by the case */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $scope = [],
        /** @var array<TestPlanTestCaseDependency> dependency Required criteria to execute the test case */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\TestPlan\TestPlanTestCaseDependency',
        )]
        public array $dependency = [],
        /** @var array<TestPlanTestCaseTestRun> testRun The actual test to be executed */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\TestPlan\TestPlanTestCaseTestRun',
        )]
        public array $testRun = [],
        /** @var array<TestPlanTestCaseTestData> testData The test data used in the test case */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\TestPlan\TestPlanTestCaseTestData',
        )]
        public array $testData = [],
        /** @var array<TestPlanTestCaseAssertion> assertion Test assertions or expectations */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\TestPlan\TestPlanTestCaseAssertion',
        )]
        public array $assertion = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
