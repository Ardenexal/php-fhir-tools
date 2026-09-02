<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * Labels a reference finding we do not report with the capability needed to report it.
 *
 * {@see ViolationFamilyClassifier} labels *our* violations by the constraint that produced them, which
 * is the natural family when the question is "which of our checks is firing wrongly". This class answers
 * the opposite question — "what would we have to build to report this at all" — and the only signal
 * available is the reference validator's own wording, because we produced nothing to inspect.
 *
 * Labels name a **capability**, not a message shape, because that is the unit work gets planned in. Two
 * differently worded findings that both need a terminology server belong together; two similar-looking
 * findings where one needs a code system and the other needs a regular expression do not.
 *
 * Order matters in {@see SIGNATURES}: the first matching signature wins, so the narrow ones come first.
 * Anything unrecognised lands in {@see OTHER} rather than being dropped, so labels always sum to the
 * total — a classifier that silently loses findings would understate the very gap it is measuring.
 */
final class MissingFindingClassifier
{
    /** Catch-all so labels always sum to the finding total; a growing bucket here means a missing signature. */
    public const OTHER = 'other';

    /**
     * Capability label => substrings that identify it, in priority order.
     *
     * Signatures are taken from the reference validator's messages on the vendored corpus. They are
     * matched case-insensitively against the raw text.
     *
     * @var array<string, list<string>>
     */
    public const SIGNATURES = [
        // A named invariant the reference validator evaluated and we did not. First, because
        // `Constraint failed: <key>:` is an unambiguous statement about *how* Java produced the finding,
        // while every label below it infers a capability from description wording. Ordered last in an
        // earlier draft on the reasoning that an invariant mentioning a value set should be attributed to
        // terminology — which mislabelled `bdl-7` ("FullUrl must be unique in a bundle") as
        // `bundle:fullurl`. The capability an unevaluated invariant needs is invariant evaluation,
        // whatever its description happens to mention.
        'invariant:unevaluated' => ['constraint failed'],

        // Capabilities that own only a handful of findings each. They sit here, ahead of everything
        // broader, because each is identified by one distinctive phrase and every label below carries at
        // least one deliberately wide signature. A small capability is still a capability: the point of a
        // label is to give a finding an owner, not to be big enough to plan around.
        'attachment:integrity'  => ['stated attachment size', 'hash of the data did not match'],
        'signature:verify'      => ['signature did not verify', 'signature cannot be valid'],
        'measure:cql'           => ['no cql libraries found'],
        'questionnaire:rules'   => ['enablewhen'],
        'valueset:expression'   => [
            'filter parameter expression',
            'valueset expression language must be',
            'is not a valid fhirpath',
        ],
        'reference:target-type' => [
            'is not a valid target for this element',
            'refers to a resource that has the wrong type',
            'conditional reference is not a valid query string',
        ],

        // Authoring a profile, rather than validating an instance against one: the reference validator
        // fails to build a snapshot from the differential and says so. Distinct from `profile:structure`
        // because the defect is in the profile, not the resource.
        'profile:snapshot'      => [
            'error generating snapshot',
            'in the generated snapshot',
            'matching element in the snapshot',
            'slicing at the root',
            'slicing is not allowed at the root',
            'unknown discriminatortype',
            'this element has a binding but the types',
        ],

        // Rules that apply because HL7 is the publisher, not because the resource is wrong.
        'publication:hl7-rules' => [
            'example urls are not allowed',
            'owning committee must be stated',
            'experimental content is not allowed',
            'extension url must not contain a version',
            'canonical url cannot end in',
        ],

        // SearchParameter definitions, and the rules a search-result Bundle has to satisfy.
        'search:parameters'     => [
            'searchparameter',
            'not a matching resource type for the specified search',
            'this is not an operationoutcome',
            'used in search sets is prohibited',
            'search results must have ids',
        ],

        // The corpus counted an error but carries no message for it, so no capability can be inferred.
        // Its own label rather than `other`, because `other` means "we read the rule and it fits nothing"
        // while this means "there is nothing to read" — a different problem with a different fix.
        'unclassifiable:no-message' => ['records this error as a count with no message'],

        // Needs a code system we do not hold. ADR-004 keeps extensible and preferred bindings at
        // warning severity and defers a real terminology client, so nothing here is closable offline.
        'terminology:display'   => ['wrong display name', 'valid display is'],
        'terminology:code'      => ['magic loinc code', 'unknown code', 'code is not valid', 'not a valid code'],
        'terminology:valueset'  => [
            'references a value set, not a code system',
            'not found in the value set',
            'none of the codings provided are in the value set',
            'system uri could not be determined',
            'not in the value set',
        ],

        // Needs the document to be read at all. Only reachable when we did NOT reject the document —
        // where we did, JavaFindingMatcher pairs these away rather than counting them, because one
        // unreadable file is one finding however many places the reference validator noticed it.
        'reader:encoding'       => ['is not proper utf-8', 'content is not allowed in prolog'],
        'reader:xml'            => [
            'doctype is disallowed',
            'must be terminated by the matching end-tag',
            'invalid xml character',
            'is an invalid xml character',
            'wrong namespace - expected',
            'unknown or unrecognized xml root element',
            'saxexception',
            'undefined attribute',
        ],
        'reader:json'           => [
            'error parsing json',
            'expected one of',
            'a comma is missing',
            'has no quotes around',
            'comments are not allowed in json',
            'unexpected close marker',
            'extra trailing comma',
        ],

        // Needs resolution across a Bundle or Composition, which is a FHIRPath engine capability rather
        // than a validation rule. The original cascade plan's kill criteria called this out as a project
        // in its own right.
        //
        // Ordered before `bundle:fullurl` deliberately. Java explains a failed reference by describing
        // the fullUrl rules that defeated it — "Can't find 'Patient/x' … because of the fullUrl based
        // rules around matching relative references" — so a `fullurl` substring test run first captures
        // resolution failures and attributes them to the wrong capability. Observed on `bundle-urn`,
        // where seven resolution findings were labelled `bundle:fullurl`.
        'bundle:reachability'   => ["isn't reachable by traversing", 'is not reachable by traversing'],
        'bundle:resolve'        => [
            'unable to resolve resource with reference',
            "can't find",
            'cannot find',
            'matches for',
            'matching reference for reference',
            'relative urls must be of the format',
            'relative reference appears inside bundle',
            'is not referenced to from elsewhere',
            'matches in bundle for reference',
        ],
        'bundle:fullurl'        => ['fullurl'],

        // Needs a stricter primitive reader. These are ours, uncontroversially.
        //
        // Ordered before the profile and narrative labels: those carry deliberately broad
        // signatures (`is not valid`, `xhtml`) while every signature here names one primitive rule.
        // With profile first, `id value 'x' is not valid` landed in `profile:structure` — the same
        // steal as `fullurl` taking resolution failures, and this file's third instance of it.
        // A required binding demands that *a* code be present. Deciding that needs the binding strength,
        // which generated attributes carry, and nothing at all from the value set — so despite reading like
        // terminology it is closable offline. It sat in `terminology:valueset` until M03's licence split
        // asked which code system it needed and the answer was none.
        'binding:required'      => ['no code provided, and a code is required'],

        'primitive:format'      => [
            // Whitespace rules on a code are a string check, not a lookup. Same story as `binding:required`:
            // the word "code" made it look like terminology, and it moved here once nothing had to be
            // looked up to decide it.
            'whitespace rules',
            'primitive types must have a value',
            'not a valid instant format',
            'not a valid date format',
            'does not meet decimal regex',
            'boolean values must be',
            'is not a valid integer',
            'value cannot be empty',
            'id value',
            'invalid resource id',
            'not a valid uri',
            'not a valid base64 value',
            'must have a timezone',
            'canonical urls must be absolute',
            'must be an absolute reference',
            'not a valid decimal',
            'not a valid time',
            'does not meet instant regex',
            'oids must start with',
            'oids must be valid',
            'uri values cannot',
            'base64 encoded values are not allowed',
            'the url is not valid because',
        ],

        // Needs a profile or implementation guide to be loaded and its differential applied. Excluded
        // from the original cascade plan by charter.
        'profile:extension'     => [
            'extension.url must be an absolute url',
            'is for a modifier extension',
            'is not allowed to be used at this point',
            'latest version it can be used with',
            'could not be found',
            'unknown extension',
            'extension.url is required',
            'sub-extension url',
            'definition allows for the types',
            'must not be used as an extension',
        ],
        'profile:fixed-value'   => [
            'not allowed in the applicable fixed value',
            'is fixed to',
            'does not match the fixed value',
            'pattern',
        ],
        'profile:slicing'       => ['slice', 'does not match any known slice'],
        'profile:structure'     => [
            'minimum required',
            'max allowed',
            'unrecognized property',
            'undefined element',
            'is invalid',
            'unable to find a profile match',
            'as specified by profile',
            'can only occur once',
            'not allowed in the applicable profile',
            'element must have some content',
            'less than permitted minimum value',
            'fixed value for the extension url',
            'array cannot be empty',
            'longer than permitted maximum length',
            'requires an id, but none is present',
            'property meaningwhenmissing',
            'is for type',
            'is not valid',
        ],

        // Needs narrative parsing rather than resource validation.
        'narrative:xhtml'       => [
            'xhtml',
            'hyperlink',
            'stylesheet reference could not be resolved',
            'embedded html tag',
            'textlink data reference',
            'hyperlink scheme',
            'text should not be present',
            'looks like embedded html tags',
        ],

        // Two findings of the same kind on one artifact, which needs a whole-resource pass rather than a
        // per-element rule.
        'structure:duplicate'   => [
            'duplicate id for contained resource',
            'already exists at the location',
            'is a duplicate',
            'duplicate security label',
        ],
    ];

    /** Which capability would have to exist for us to report this reference finding. */
    public function classify(string $javaText): string
    {
        $haystack = strtolower($javaText);

        foreach (self::SIGNATURES as $label => $signatures) {
            foreach ($signatures as $signature) {
                if (str_contains($haystack, $signature)) {
                    return $label;
                }
            }
        }

        return self::OTHER;
    }

    /**
     * @param list<string> $javaTexts
     *
     * @return list<string> one label per text, in order
     */
    public function classifyAll(array $javaTexts): array
    {
        return array_map(fn (string $t): string => $this->classify($t), $javaTexts);
    }
}
