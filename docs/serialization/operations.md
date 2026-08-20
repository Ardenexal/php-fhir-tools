---
description: Map typed operation inputs and outputs to and from a FHIR Parameters resource.
icon: right-left
---

# Operations & the Parameters Mapping

FHIR operations exchange their arguments as a `Parameters` resource — a flat list of name/value
entries with nested `part` groups. `OperationParameterMapper` converts between that wire format and
the [typed operation classes](../code-generation/operations.md), so you construct an object and read
an object instead of building and walking a `Parameters` by hand.

{% hint style="info" %}
This library maps and serializes; it does not make HTTP requests. You bring your own client — send
the JSON below with whatever you already use, and hand the response body back to the mapper.
{% endhint %}

The first four sections below are executed by `tests/Integration/OperationDocsExamplesTest.php` —
one test method per section, named after it. If you change an example, change its test. The last two
sections cite the tests that cover them instead, since both need service wiring that would make a
doc example longer than the point it illustrates.

## Building a request

Construct the Input, map it to a `Parameters`, serialize it:

```php
use Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetValidateCode\ValueSetValidateCodeInput;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;

$mapper  = OperationParameterMapper::createDefault(FhirVersion::R4);
$service = FHIRSerializationService::createDefault(FhirVersion::R4);

$input = new ValueSetValidateCodeInput(
    url:    'http://hl7.org/fhir/ValueSet/administrative-gender',
    code:   'female',
    system: 'http://hl7.org/fhir/administrative-gender',
);

$json = $service->serializeToJson($mapper->toParameters($input));
```

`$json` is:

```json
{
  "resourceType": "Parameters",
  "parameter": [
    { "name": "url",    "valueUri":  "http://hl7.org/fhir/ValueSet/administrative-gender" },
    { "name": "code",   "valueCode": "female" },
    { "name": "system", "valueUri":  "http://hl7.org/fhir/administrative-gender" }
  ]
}
```

Note `valueCode` and `valueUri` rather than `valueString`. The class declares those three properties
as plain `?string`, and the `value[x]` key comes from the parameter's **declared FHIR type**, not
from the PHP type — which is exactly the derivation that goes wrong when a `Parameters` is assembled
by hand.

`serializeToXml()` works identically on the same object; the mapping layer is format-agnostic
because it produces a normal `Parameters` resource that the existing serializer already knows.

## Reading a response

Deserialize the body, then hand it to `fromResponse()` with the **operation holder** class:

```php
use Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetValidateCode\ValueSetValidateCodeOperation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ParametersResource;

$parameters = $service->deserializeFromJson($responseBody, ParametersResource::class);
$output     = $mapper->fromResponse($parameters, ValueSetValidateCodeOperation::class);

$output->result;   // false
$output->message;  // "Unknown code 'flase'"
$output->display;  // "Female"
```

**Use `fromResponse()`, not `fromParameters()`.** `fromResponse()` reads the operation's declared
[output shape](../code-generation/operations.md#output-shapes) and does the right thing for each:
returns a typed `Output` for `Parameters`-shaped operations, the resource itself for the bare-resource
shapes, and `null` for operations that declare no output. `fromParameters()` is the lower-level call
that assumes a `Parameters` — correct only when you already know that is what you have. Since roughly
three-quarters of operations do **not** answer with a `Parameters`, guessing here is a real bug and
not a rare one.

This replaces a specific piece of hand-rolled parsing. `MemberOfFunction` still string-builds
`ValueSet/$validate-code?…` and then walks the decoded JSON looking for `name === 'result'` to pull
`valueBoolean` out — no type safety, no completion, and silent breakage if a server orders the
parameters differently.

## Nested parameter groups

`part` groups are where hand-parsing genuinely hurts. `$lookup` returns `property` groups that
themselves contain `subproperty` groups, and each carries a polymorphic `value`:

```php
use Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup\CodeSystemLookupOutput;

$parameters = $service->deserializeFromJson($responseBody, ParametersResource::class);
$output     = $mapper->fromParameters($parameters, CodeSystemLookupOutput::class);

$output->name;                                    // 'SNOMED CT'
$output->property[0]->code;                       // 'parent'
$output->property[0]->subproperty[0]->code;       // 'inherited'
```

against a body like:

```json
{
  "resourceType": "Parameters",
  "parameter": [
    { "name": "name",    "valueString": "SNOMED CT" },
    { "name": "display", "valueString": "Left displacement" },
    {
      "name": "property",
      "part": [
        { "name": "code",  "valueCode": "parent" },
        { "name": "value", "valueCode": "263678003" },
        {
          "name": "subproperty",
          "part": [
            { "name": "code",  "valueCode": "inherited" },
            { "name": "value", "valueCode": "263679000" }
          ]
        }
      ]
    }
  ]
}
```

Each group is a real class — `CodeSystemLookupOutProperty`, `CodeSystemLookupOutPropertySubproperty`
— and the mapping round-trips: `toParameters()` on the result reproduces the body above exactly.

## Missing required parameters fail loudly

Generated model classes make every property nullable regardless of cardinality, so a
cardinality-invalid payload constructs happily and passes PHPStan. The mapper is the layer that
notices:

```php
// `name` and `display` are both min:1 on $lookup's output.
$mapper->toParameters(new CodeSystemLookupOutput(name: 'SNOMED CT'));
// OperationMappingException: Operation parameter "display" is required (min >= 1) but was not set on …
```

An exception is the point. Hand-building a `Parameters` in this situation produces a resource that
is missing a required parameter and looks perfectly well-formed.

Two related behaviours worth knowing, both deliberate:

- **Undeclared response parameters are dropped, not carried or rejected.** Servers add parameters,
  and a strict reader would reject conformant-enough bodies — but this is a real data-loss path, so
  do not rely on a round trip preserving something the class does not declare.
- **`false` and `0` are values, not absences.** They serialize; only `null` counts as unset.

## Validating what you emit

An emitted `Parameters` is an ordinary FHIR resource, so `FHIRValidationService` applies to it —
including the invariants declared on `Parameters.parameter` itself, such as `inv-1` ("a parameter
must have one and only one of value, resource, part"):

```php
$outcome = $validationService->validate($mapper->toParameters($input));
$outcome->errors(); // []
```

Reaching a nested constraint depends on the validator descending into `Parameters.parameter`, which
it does because the generated models carry the `Valid` cascade. If you ever see nested invariants
going unreported, that cascade is the first thing to check.

`tests/Integration/GeneratedOperationParametersAreValidFhirTest.php` pins this section: it asserts
that emitted `Parameters` report zero errors across all three versions, and its guard constructs a
deliberately `inv-1`-violating nested parameter to prove the validator is actually reaching that far
rather than passing because it never looked.

## Using operation classes with a framework

Generated payload classes work through the plain Symfony Serializer, so they can be used directly as
an API Platform `input:`:

```php
#[Post(
    uriTemplate: '/ValueSet/{id}/$validate-code',
    input:       ValueSetValidateCodeInput::class,
    processor:   ValueSetValidateCodeProcessor::class,
)]
```

This works because the bundle registers dedicated operation-payload normalizers. Without them
`ObjectNormalizer` claims the class, looks for constructor arguments named `url`, `code` and `system`
in a body shaped `{"resourceType":"Parameters","parameter":[…]}`, finds none, throws nothing, and
hands the processor an object with **every property null**. If you wire the serializer yourself
rather than through the bundle, register `FHIROperationPayloadJsonNormalizer` and
`FHIROperationPayloadXmlNormalizer` above `ObjectNormalizer`.

Pinned by `src/Component/Serialization/tests/Unit/Operation/OperationPayloadNormalizerTest.php`,
including the silent-null failure mode, and by
`src/Bundle/FHIRBundle/tests/Unit/OperationPayloadNormalizerRegistrationTest.php` for the bundle
wiring and normalizer priority.
