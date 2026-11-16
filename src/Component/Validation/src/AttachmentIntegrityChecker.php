<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports an Attachment whose stated `size` or `hash` does not describe its inline `data`.
 *
 * `Attachment.size` is the number of bytes of the content and `Attachment.hash` is the base64-encoded
 * SHA-1 of it. When the content travels inline as `data`, both can be checked without fetching
 * anything: decode `data`, count its bytes, hash them. The reference validator reports each mismatch
 * as an error located at the Attachment, in the wording used here.
 *
 * FHIR fixes the hash algorithm, so a `hash` computed with anything other than SHA-1, or a hex digest
 * encoded as base64 instead of the digest bytes, is reported as a mismatch, as the reference validator
 * reports it.
 *
 * ## What is not checked
 *
 * - An Attachment without `data`. Its content sits behind `url`, and nothing is fetched.
 * - `data` that is not valid base64. The primitive's own format rule reports that, and a byte count of
 *   undecodable text would only add a second, misleading error on top of it.
 */
final class AttachmentIntegrityChecker
{
    /** @var array<class-string, bool> keyed by class name */
    private array $isAttachment = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Walk a resource, nested resources included, and check every Attachment that carries `data`.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $visited    = [];
        $violations = [];
        $this->walk($resource, '', $visited, $violations);

        return $violations;
    }

    /**
     * @param array<int, true>              $visited    spl_object_id keys of the objects on the current descent (cycle guard)
     * @param list<FHIRValidationViolation> $violations collects the findings
     */
    private function walk(object $node, string $path, array &$visited, array &$violations): void
    {
        $objectId = spl_object_id($node);

        if (isset($visited[$objectId])) {
            return;
        }

        $visited[$objectId] = true;

        if ($this->isAttachment($node)) {
            $this->checkAttachment($node, $path, $violations);
        }

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;

            if (is_object($value)) {
                $this->walk($value, $subPath, $visited, $violations);
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $this->walk($item, $subPath . '[' . $i . ']', $visited, $violations);
                    }
                }
            }
        }

        unset($visited[$objectId]);
    }

    /**
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkAttachment(object $attachment, string $path, array &$violations): void
    {
        $data = $this->primitiveString($this->models->readInitializedValue($attachment, 'data'));

        if ($data === null) {
            return;
        }

        $content = base64_decode($data, true);

        if ($content === false) {
            return;
        }

        $statedSize = $this->primitiveString($this->models->readInitializedValue($attachment, 'size'));

        if ($statedSize !== null && $statedSize !== (string) strlen($content)) {
            $violations[] = $this->violation(
                $path,
                sprintf('Stated Attachment Size %s does not match actual attachment size %d', $statedSize, strlen($content)),
            );
        }

        $statedHash = $this->primitiveString($this->models->readInitializedValue($attachment, 'hash'));
        $actualHash = sha1($content, true);

        // Compared as digest bytes: base64 may carry whitespace, so equal digests can differ as text
        if ($statedHash !== null && base64_decode($statedHash, true) !== $actualHash) {
            $violations[] = $this->violation(
                $path,
                sprintf("The hash of the data did not match the data (stated: '%s', actual: '%s')", $statedHash, base64_encode($actualHash)),
            );
        }
    }

    /**
     * The lexical value of a primitive: a generated primitive wrapper, or a bare scalar where the model
     * holds one. Null when the element is absent or carries no value (only an id or extensions).
     */
    private function primitiveString(mixed $value): ?string
    {
        if (is_object($value)) {
            $value = $this->models->readInitializedValue($value, 'value');
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_string($value) && $value !== '') {
            return $value;
        }

        return null;
    }

    private function violation(string $path, string $message): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * Read through the hierarchy so a data-type profile on Attachment counts too.
     */
    private function isAttachment(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isAttachment[$key])) {
            return $this->isAttachment[$key];
        }

        $found = false;

        foreach ($this->attributes->classAttributesInHierarchy($node, FHIRComplexType::class) as $complex) {
            $found = $found || $complex->typeName === 'Attachment';
        }

        return $this->isAttachment[$key] = $found;
    }
}
