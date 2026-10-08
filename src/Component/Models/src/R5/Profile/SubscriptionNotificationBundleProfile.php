<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle
 *
 * @description This profile holds all the requirements and constraints related to a Subscription Notification Bundle.
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle',
    baseType: 'Bundle',
    fhirVersion: 'R5',
)]
#[FHIRProfileConstraint(
    path: 'total',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle'],
)]
#[FHIRSlicingRules(
    property: 'entry',
    rules: 'openAtEnd',
    groups: ['http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle'],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'first',
    min: 1,
    max: 1,
    discriminatorType: 'exists',
    discriminatorPath: 'entry',
    orderedIndex: 0,
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'search', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle'],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'other',
    min: 0,
    max: '*',
    discriminatorType: 'exists',
    discriminatorPath: 'entry',
    orderedIndex: 1,
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'search', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle'],
)]
class SubscriptionNotificationBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle';
}
