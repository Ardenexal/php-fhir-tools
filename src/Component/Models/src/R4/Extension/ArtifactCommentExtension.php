<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-artifactComment
 *
 * @description A comment containing additional documentation, a review comment, usage guidance, or other relevant information from a particular user.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-artifactComment', fhirVersion: 'R4')]
class ArtifactCommentExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive type documentation | review | guidance */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $type,
        /** @var MarkdownPrimitive text The comment */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public MarkdownPrimitive $text,
        /** @var array<UriPrimitive> target What the comment is directed to */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $target = [],
        /** @var array<UriPrimitive> reference Supporting reference for the comment */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $reference = [],
        /** @var StringPrimitive|null user Who commented */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $user = null,
        /** @var DateTimePrimitive|null authoredOn Date and time the comment was made */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $authoredOn = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        $subExtensions[] = new Extension(url: 'text', value: $this->text);
        foreach ($this->target as $v) {
            $subExtensions[] = new Extension(url: 'target', value: $v);
        }
        foreach ($this->reference as $v) {
            $subExtensions[] = new Extension(url: 'reference', value: $v);
        }
        if ($this->user !== null) {
            $subExtensions[] = new Extension(url: 'user', value: $this->user);
        }
        if ($this->authoredOn !== null) {
            $subExtensions[] = new Extension(url: 'authoredOn', value: $this->authoredOn);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-artifactComment',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $type       = null;
        $text       = null;
        $target     = [];
        $reference  = [];
        $user       = null;
        $authoredOn = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodePrimitive) {
                $type = $ext->value;
            }
            if ($extUrl === 'text' && $ext->value instanceof MarkdownPrimitive) {
                $text = $ext->value;
            }
            if ($extUrl === 'target' && $ext->value instanceof UriPrimitive) {
                $target[] = $ext->value;
            }
            if ($extUrl === 'reference' && $ext->value instanceof UriPrimitive) {
                $reference[] = $ext->value;
            }
            if ($extUrl === 'user' && $ext->value instanceof StringPrimitive) {
                $user = $ext->value;
            }
            if ($extUrl === 'authoredOn' && $ext->value instanceof DateTimePrimitive) {
                $authoredOn = $ext->value;
            }
        }

        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($text === null) {
            throw new \InvalidArgumentException('Required sub-extension "text" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($type, $text, $target, $reference, $user, $authoredOn, $id);
    }
}
