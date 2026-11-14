<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MediaResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment as R5Attachment;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive as R5Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\DocumentReference\DocumentReferenceContent;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\DocumentReferenceResource;
use Ardenexal\FHIRTools\Component\Validation\AttachmentIntegrityChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\TestCase;

/**
 * Shapes and wording from `R4.attachment-with-wrong-size` and `R5.dr-bad-att-hash`.
 */
final class AttachmentIntegrityCheckerTest extends TestCase
{
    /** base64 of the 14 bytes "help i'm a bug" */
    private const string DATA = 'aGVscCBpJ20gYSBidWc=';

    public function testStatedSizeThatDisagreesWithTheDataIsReportedAtTheAttachment(): void
    {
        $violations = (new AttachmentIntegrityChecker())->check(new MediaResource(content: new Attachment(
            data: new Base64BinaryPrimitive(value: self::DATA),
            size: new UnsignedIntPrimitive(value: 100),
        )));

        self::assertSame(['Stated Attachment Size 100 does not match actual attachment size 14'], $this->messages($violations));
        self::assertSame('content', $violations[0]->path);
        self::assertSame('error', $violations[0]->severity);
    }

    public function testMatchingSizeAndHashAreAccepted(): void
    {
        self::assertSame([], (new AttachmentIntegrityChecker())->check(new MediaResource(content: new Attachment(
            data: new Base64BinaryPrimitive(value: self::DATA),
            size: new UnsignedIntPrimitive(value: 14),
            hash: new Base64BinaryPrimitive(value: base64_encode(sha1("help i'm a bug", true))),
        ))));
    }

    /**
     * R5 types `size` as integer64. The stated hash here is base64 of the hex digest rather than of the
     * digest bytes, which is the mistake `dr-bad-att-hash` makes.
     */
    public function testWrongSizeAndHashAreBothReportedInR5(): void
    {
        $statedHash = base64_encode(sha1("help i'm a bug"));

        $violations = (new AttachmentIntegrityChecker())->check(new DocumentReferenceResource(content: [
            new DocumentReferenceContent(attachment: new R5Attachment(
                data: new R5Base64BinaryPrimitive(value: self::DATA),
                size: new Integer64Primitive(value: 15),
                hash: new R5Base64BinaryPrimitive(value: $statedHash),
            )),
        ]));

        self::assertSame([
            'Stated Attachment Size 15 does not match actual attachment size 14',
            sprintf(
                "The hash of the data did not match the data (stated: '%s', actual: '%s')",
                $statedHash,
                base64_encode(sha1("help i'm a bug", true)),
            ),
        ], $this->messages($violations));
        self::assertSame('content[0].attachment', $violations[0]->path);
    }

    /**
     * Content behind `url` is not fetched, and undecodable `data` is the format rule's finding
     * (`R4.attachment-with-invalid-binary`), not a size mismatch.
     */
    public function testAttachmentWithoutDecodableDataIsNotChecked(): void
    {
        $checker = new AttachmentIntegrityChecker();

        self::assertSame([], $checker->check(new MediaResource(content: new Attachment(
            size: new UnsignedIntPrimitive(value: 100),
        ))));
        self::assertSame([], $checker->check(new MediaResource(content: new Attachment(
            data: new Base64BinaryPrimitive(value: '%%%2@()()'),
            size: new UnsignedIntPrimitive(value: 100),
        ))));
    }

    /**
     * base64 may carry whitespace, so a correct hash split across lines still matches.
     */
    public function testHashWithWhitespaceIsComparedAsBytes(): void
    {
        $hash = chunk_split(base64_encode(sha1("help i'm a bug", true)), 8, "\n");

        self::assertSame([], (new AttachmentIntegrityChecker())->check(new MediaResource(content: new Attachment(
            data: new Base64BinaryPrimitive(value: self::DATA),
            hash: new Base64BinaryPrimitive(value: $hash),
        ))));
    }

    /**
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<string>
     */
    private function messages(array $violations): array
    {
        return array_map(static fn (FHIRValidationViolation $v): string => $v->message, $violations);
    }
}
