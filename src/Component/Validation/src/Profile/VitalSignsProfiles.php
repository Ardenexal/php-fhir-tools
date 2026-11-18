<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * The core vital-signs profile an Observation must conform to, chosen by its code.
 *
 * The spec says an Observation that records one of these vital signs SHALL conform to the
 * matching profile, whether or not its `meta.profile` names it. The LOINC codes come from the
 * "Magic Value" table on the spec's `observation-vitalsigns.html` page. That table is the same in
 * R4 and R5, and R4B uses the R4 profiles. The systolic (8480-6) and diastolic (8462-4) codes are
 * left out: they name components of a blood pressure Observation, not an Observation of their own.
 *
 * The rest are codes the HL7 reference validator also maps, though the spec table does not list
 * them: SNOMED CT 276885007 (body temperature), LOINC 59408-5 (oxygen saturation by pulse
 * oximetry, which the table names as the code 2708-6 replaced) and LOINC 76534-7 (blood pressure
 * by non-invasive measurement).
 *
 * @internal
 *
 * @author Ardenexal
 */
final class VitalSignsProfiles
{
    public const string LOINC = 'http://loinc.org';

    public const string SNOMED_CT = 'http://snomed.info/sct';

    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    /** Code system => code => core profile canonical URL. */
    public const array BY_CODING = [
        self::LOINC => [
            '85353-1' => self::CORE . 'vitalspanel',
            '9279-1'  => self::CORE . 'resprate',
            '8867-4'  => self::CORE . 'heartrate',
            '2708-6'  => self::CORE . 'oxygensat',
            '59408-5' => self::CORE . 'oxygensat',
            '8310-5'  => self::CORE . 'bodytemp',
            '8302-2'  => self::CORE . 'bodyheight',
            '9843-4'  => self::CORE . 'headcircum',
            '29463-7' => self::CORE . 'bodyweight',
            '39156-5' => self::CORE . 'bmi',
            '85354-9' => self::CORE . 'bp',
            '76534-7' => self::CORE . 'bp',
        ],
        self::SNOMED_CT => [
            '276885007' => self::CORE . 'bodytemp',
        ],
    ];

    /**
     * The profile URLs an Observation's codings imply, in coding order and without repeats.
     *
     * A coding counts only in its own system: the same code string in another system means
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
            if ($coding['system'] === null || $coding['code'] === null) {
                continue;
            }

            $url = self::BY_CODING[$coding['system']][$coding['code']] ?? null;
            if ($url !== null && !in_array($url, $urls, true)) {
                $urls[] = $url;
            }
        }

        return $urls;
    }
}
