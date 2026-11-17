<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * The core vital-signs profile an Observation must conform to, chosen by its LOINC code.
 *
 * The spec says an Observation that records one of these vital signs SHALL conform to the
 * matching profile, whether or not its `meta.profile` names it. The table is the "Magic Value"
 * table on the spec's `observation-vitalsigns.html` page. It is the same in R4 and R5, and R4B
 * uses the R4 profiles. The systolic (8480-6) and diastolic (8462-4) codes are left out: they
 * name components of a blood pressure Observation, not an Observation of their own.
 *
 * @internal
 *
 * @author Ardenexal
 */
final class VitalSignsProfiles
{
    public const string LOINC = 'http://loinc.org';

    /** LOINC code => core profile canonical URL. */
    public const array BY_CODE = [
        '85353-1' => 'http://hl7.org/fhir/StructureDefinition/vitalspanel',
        '9279-1'  => 'http://hl7.org/fhir/StructureDefinition/resprate',
        '8867-4'  => 'http://hl7.org/fhir/StructureDefinition/heartrate',
        '2708-6'  => 'http://hl7.org/fhir/StructureDefinition/oxygensat',
        '8310-5'  => 'http://hl7.org/fhir/StructureDefinition/bodytemp',
        '8302-2'  => 'http://hl7.org/fhir/StructureDefinition/bodyheight',
        '9843-4'  => 'http://hl7.org/fhir/StructureDefinition/headcircum',
        '29463-7' => 'http://hl7.org/fhir/StructureDefinition/bodyweight',
        '39156-5' => 'http://hl7.org/fhir/StructureDefinition/bmi',
        '85354-9' => 'http://hl7.org/fhir/StructureDefinition/bp',
    ];

    /**
     * The profile URLs an Observation's codings imply, in coding order and without repeats.
     *
     * Only codings whose system is LOINC count: the same code string in another system means
     * something else.
     *
     * @param iterable<array{system: string|null, code: string|null}> $codings
     *
     * @return list<string>
     */
    public static function forCodings(iterable $codings): array
    {
        $urls = [];
        foreach ($codings as $coding) {
            if ($coding['system'] !== self::LOINC || $coding['code'] === null) {
                continue;
            }

            $url = self::BY_CODE[$coding['code']] ?? null;
            if ($url !== null && !in_array($url, $urls, true)) {
                $urls[] = $url;
            }
        }

        return $urls;
    }
}
