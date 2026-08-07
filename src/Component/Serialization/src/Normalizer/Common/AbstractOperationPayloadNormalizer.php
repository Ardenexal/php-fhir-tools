<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface as SymfonyDenormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizer;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Shared logic for the JSON and XML operation-payload normalizers.
 *
 * Generated operation payload classes (`…Input`, `…Output`, and their nested `part[]` classes) are
 * ergonomic DTOs, not FHIR objects: they carry no `#[FhirResource]` or `#[FhirProperty]`, so none of
 * the sibling normalizers claims them. Without a normalizer that does, a framework deserializing a
 * request body straight into one — API Platform's `input:` option is the motivating case — falls
 * through to `ObjectNormalizer`, which looks for constructor arguments named `url`/`code`/`system`
 * in a body shaped `{"resourceType":"Parameters","parameter":[…]}`, finds none, and returns an
 * object with **every property null**. No exception, no validation error.
 *
 * That silence is why this exists. The wire format of an operation payload is a `Parameters`
 * resource; the payload class is a typed view over it, and
 * {@see OperationParameterMapper} is the translation. This normalizer is the seam that lets the
 * Symfony Serializer — and therefore any framework built on it — cross between them.
 *
 * ## Both directions delegate; neither formats anything
 *
 * `normalize()` maps the payload to a real `Parameters` resource and hands it to the sibling
 * normalizers; `denormalize()` asks them for a `Parameters` and hands that to the mapper. This class
 * never touches JSON or XML syntax, which is precisely why one implementation serves both formats
 * and why the format-specific subclasses differ only in which `$format` they accept.
 *
 * ## Bare-resource operations are not claimed
 *
 * Only the `Parameters` output shape has a generated Output class. For the majority of operations —
 * 57% of R4 and 64% of R5 — the response *is* a resource, a handler returns that resource directly,
 * and the resource normalizers already serialize it. Nothing here should intercept those.
 *
 * @author Ardenexal
 */
abstract class AbstractOperationPayloadNormalizer extends AbstractFHIRNormalizer
{
    /**
     * Built once from the injected resolver, not per call.
     *
     * Sharing the resolver with the rest of the chain is what makes a registered IG profile of
     * `Parameters` honoured here too — building a mapper with its own resolver would quietly
     * produce base-spec classes while every sibling normalizer honoured the profile.
     */
    private ?OperationParameterMapper $mapper = null;

    public function __construct(
        FHIRMetadataExtractorInterface $metadataExtractor,
        private readonly FHIRTypeResolverInterface $typeResolver,
        ?NormalizerInterface $normalizer = null,
        ?DenormalizerInterface $denormalizer = null,
        private readonly string $version = 'R4',
        ?FHIRIGTypeRegistry $igTypeRegistry = null,
        /**
         * Serializer used for the inner `Parameters` leg, when it differs from the outer one.
         *
         * Inside the FHIR chain this stays null: the sibling resource/complex/primitive normalizers
         * are right there, so `$this->normalizer` (injected by `SerializerAwareInterface`) can do
         * the job. Registered into an *application's* serializer — which is how API Platform reaches
         * these classes — those siblings are absent, and delegating there would fall through to
         * `ObjectNormalizer` and reintroduce the silent-null failure one level down. Pointing this
         * at the version-scoped FHIR serializer keeps the inner leg correct wherever the outer one
         * happens to live.
         */
        private readonly SymfonyNormalizer|SymfonyDenormalizer|null $fhirSerializer = null,
    ) {
        parent::__construct($metadataExtractor, $normalizer, $denormalizer, $version, $igTypeRegistry);
    }

    /**
     * True when this normalizer handles the given payload — right attribute *and* right version.
     *
     * Version-aware so that all three version stacks can be registered side by side and exactly one
     * claims any given payload class. Without it the first-registered stack would claim every
     * payload and then throw on mismatch.
     */
    protected function claims(mixed $subject): bool
    {
        $class = is_object($subject) ? $subject::class : $subject;

        if (!is_string($class)) {
            return false;
        }

        return self::payloadAttribute($class)?->version === $this->version;
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>|string|int|float|bool|\ArrayObject<string, mixed>|null
     *
     * @throws OperationMappingException
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Expected object, got ' . gettype($object));
        }

        $payload = self::payloadAttribute($object::class);

        if ($payload === null) {
            throw new InvalidArgumentException('Object is not a FHIR operation payload');
        }

        $this->assertVersionMatches($payload, $object::class);

        // The mapper produces a real Parameters resource; the sibling normalizers turn it into the
        // target format. Nothing about JSON or XML is decided here.
        $normalizer = $this->fhirSerializer instanceof SymfonyNormalizer ? $this->fhirSerializer : $this->normalizer;

        return $normalizer?->normalize($this->mapper()->toParameters($object), $format, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $context
     *
     * @throws OperationMappingException
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $payload = self::payloadAttribute($type);

        if ($payload === null) {
            throw new NotNormalizableValueException($type . ' is not a FHIR operation payload');
        }

        $this->assertVersionMatches($payload, $type);

        $mapper = $this->mapper();

        // Denormalize into the mapper's *resolved* Parameters class rather than a built name, so a
        // profiled Parameters registered with the resolver is produced here as well.
        $denormalizer = $this->fhirSerializer instanceof SymfonyDenormalizer
            ? $this->fhirSerializer
            : $this->denormalizer;

        $parameters = $denormalizer?->denormalize($data, $mapper->parametersResourceClass(), $format, $context);

        if (!is_object($parameters)) {
            throw new NotNormalizableValueException(sprintf('Expected a Parameters resource for %s, got %s.', $type, get_debug_type($parameters)));
        }

        /** @var class-string $type */
        return $mapper->fromParameters($parameters, $type);
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => true];
    }

    /**
     * True when the class carries `#[FhirOperationPayload]`.
     *
     * Attribute presence rather than a namespace check: IG-generated operations live under the
     * application's own namespace, and a path-based test would silently exclude them.
     */
    public static function isOperationPayload(mixed $subject): bool
    {
        if (is_object($subject)) {
            return self::payloadAttribute($subject::class) !== null;
        }

        return is_string($subject) && self::payloadAttribute($subject) !== null;
    }

    /**
     * Read `#[FhirOperationPayload]`, or null when the class is not a payload (or does not exist).
     */
    protected static function payloadAttribute(string $class): ?FhirOperationPayload
    {
        if (!class_exists($class)) {
            return null;
        }

        $attributes = self::reflClass($class)->getAttributes(FhirOperationPayload::class);

        return $attributes === [] ? null : $attributes[0]->newInstance();
    }

    /**
     * Refuse a payload from a different FHIR version than this normalizer is scoped to.
     *
     * The sibling normalizers are all version-scoped at construction and `FHIRSerializationService`
     * builds one chain per version. An R4 payload reaching an R5 chain would otherwise be mapped
     * against R5's `Parameters` and R5 primitive classes, failing later with a type error that says
     * nothing about the real cause.
     */
    private function assertVersionMatches(FhirOperationPayload $payload, string $class): void
    {
        if ($payload->version === $this->version) {
            return;
        }

        throw new InvalidArgumentException(sprintf('%s is a %s operation payload, but this serializer is scoped to %s. Use the %s serializer, or the payload will be mapped against the wrong version\'s models.', $class, $payload->version, $this->version, $payload->version));
    }

    private function mapper(): OperationParameterMapper
    {
        return $this->mapper ??= new OperationParameterMapper($this->typeResolver);
    }
}
