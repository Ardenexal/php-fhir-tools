---
category: php-attribute-self-const-anonymous-class
last_reviewed: 2026-05-29
---

# Footguns: PHP Attribute `self::CONST` in Anonymous Classes

## Footgun: PHP attribute self::CONST in anonymous classes causes fatal "Undefined constant" error

**Status:** active | **Created:** 2026-05-29 | **Evidence:** OBSERVED
**hallucination-risk:** high

**Symptoms:** Fatal "Undefined constant self::PROFILE_A" (or similar) error at attribute
instantiation time when a test fixture uses an anonymous class decorated with a PHP 8
attribute that references `self::CONSTANT`.

**Why it happens:** Attributes are compile-time constant expressions. `self` in an attribute
resolves at definition time — the anonymous class scope, not the lexically enclosing class
scope. PHP resolves `self` to the class being *defined* (the anonymous class), not the
lexically enclosing class. Since anonymous classes have no constants, this produces a fatal
error.

**Evidence:** `src/Component/Validation/tests/Unit/FHIRTargetProfileValidatorTest.php`
(search: `ProfileATargetProfileFixture`) — discovered during M08; the fix added
`ProfileATargetProfileFixture` and `ProfileBTargetProfileFixture` as file-level classes.

**Prevention:** In test fixtures that need `#[FHIRProfile]` (or any attribute) with values
from test class constants, use **named file-level classes with hardcoded string literals**
instead of anonymous classes:

```php
// WRONG — fails with "Undefined constant self::PROFILE_A":
$obj = new #[FHIRProfile(profileUrl: self::PROFILE_A)] class {};

// CORRECT — define at file level with literal strings:
#[FHIRProfile(profileUrl: 'http://example.org/fhir/StructureDefinition/profile-a', ...)]
final class ProfileATargetProfileFixture {}
```
