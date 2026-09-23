---
goat-flow-reference-version: "1.16.0"
goat-flow-ownership: "user-owned"
---
# Release

Use this playbook to cut a release of the `ardenexal/*` packages: choosing the version, bumping
every version site in lockstep, proving the result resolves, and publishing it through a GitHub
Release that fans out to eight split repositories and Packagist. Load it whenever a request
mentions a release, a version bump, a tag, or `gh release`.

**Publishing cannot be undone.** A tag on this repo triggers `split-monorepo.yml`, which tags all
eight split repos within about a minute. Packagist indexes them within minutes more and never
forgets a version. Every rule below exists so that the tag is created only on a commit already
proven correct.

## Availability Check

Run these before starting. The agent prepares and verifies; a human runs every commit and every
`gh` write, because the policy hook blocks both for agents.

```bash
gh auth status                      # must show a logged-in account
command -v jq composer goat-flow    # all three must resolve
git config --get user.signingkey    # empty = do NOT use `git commit -S` (it fails here)
```

Prefix every `composer` call with `COMPOSER_ALLOW_SUPERUSER=1` when running as root.

## Hard Rules

1. **Two separate handoffs.** Block A covers commit, push and PR. Block B is the release, and it
   is sent in a later message, only after the merge has been verified (Step 7). Never put them in
   one paste block: a comment such as `# after merge:` is not a gate, and a pasted block keeps
   running after a failed command.
2. **Chain Block A with `&&`,** so a failed commit stops the push and the PR.
3. **Tag an exact SHA,** never a branch name: `--target <merge-commit-sha>`.
4. **Tag names are bare:** `0.6.1`, never `v0.6.1`. The CHANGELOG compare links depend on it.
5. **Never delete or move a published tag.** Recover with the next patch (see Recovery).
6. **Local gates can't see resolution failures.** `composer test-ai`, `phpstan-ai` and `lint` all
   pass on a half-done bump. Step 4's isolation replay is the only local check that catches one.
   Never use `composer validate --no-check-all` as proof, because that flag hides unresolvable
   requirements.

## Step 1: Choose the version

- Read `## [Unreleased]` in `CHANGELOG.md` and run `git diff <last-tag>..HEAD --stat`. Confirm any
  `**BREAKING**` entry against the code diff, not the CHANGELOG alone.
- Pre-1.0 caret rules apply: `^0.6` means `>=0.6.0 <0.7.0`.
  - A breaking change bumps the minor (`0.5.x` → `0.6.0`).
  - Anything else bumps the patch.
- Last tag: `git describe --tags --abbrev=0`. Existing releases: `gh release list --limit 3`.

## Step 2: Bump every lockstep site

Branch first (`git switch -c chore/release-X-Y-Z`). There are four shapes, and a half-done bump
breaks CI or consumers:

| Shape | Where | Minor bump (e.g. 0.6.0) | Patch bump (e.g. 0.6.2) |
|---|---|---|---|
| Cross-pins `"ardenexal/<pkg>": "^0.N"` (14 as of 0.6.1) | `src/Component/*/composer.json`, `src/Bundle/FHIRBundle/composer.json` | `^0.6` | Unchanged, unless a sibling fix is required, then floor at `^0.6.2` |
| Branch aliases `"dev-main": "0.N.x-dev"` (8) | Same manifests | `0.6.x-dev` | Unchanged |
| CI sibling stamp `composer config version 0.N.x-dev` and its comments | `.github/workflows/package-integrity.yml` (search: "composer config version") | `0.6.x-dev` | Unchanged |
| Compat harness `0.N.999` (3 sites) and the floor quoted in its README | `tests/Compat/symfony-6.4/composer.json`, `tests/Compat/symfony-6.4/README.md` | `0.6.999` | Unchanged |

Also update the `^0.N` wording in `.github/workflows/split-monorepo.yml` comments and error text.
Changes under `.github/workflows/` are Confirm-tier in `CLAUDE.md`: get the user's approval first.

**Floor rule.** Floor the pins at the first version whose siblings are correct. If a mis-pinned
release is already on Packagist, floor above it, as 0.6.1 did with `^0.6.1`, so that nothing can
resolve a sibling from the bad set.

If two packages must be upgraded together but neither requires the other, declare a `conflict`
instead of documenting the pairing. For example, `cda-sd-models` declares
`"ardenexal/fhir-serialization": "<0.6"`.

Check that no stale site remains. Substitute the old version, and expect no output:

```bash
grep -rnE '0\.5\.x-dev|0\.5\.999|\^0\.5' --include=composer.json --include=*.yml --include=README.md \
  src tests/Compat .github | grep -v /vendor/
```

Nothing under the root `composer.json` or `composer.lock` changes: the root requires no
`ardenexal/*` package.

## Step 3: Cut the CHANGELOG

Follow `changelog.md`. For this repo:

- Rename `## [Unreleased]` to `## [X.Y.Z] - YYYY-MM-DD` and add a fresh empty `## [Unreleased]`
  above it.
- Add a `[Core]` entry for the constraint and alias move, and an `[Infrastructure]` `[CI]` entry
  for the stamp.
- Update the footer links: `[Unreleased]: .../compare/X.Y.Z...HEAD` and
  `[X.Y.Z]: .../compare/<prev>...X.Y.Z`.

## Step 4: Prove it resolves

Run all of these fresh, and quote the literal pass lines in the handoff.

**Local gates:** `composer test-ai` (`OK N tests`), `composer phpstan-ai` (`OK 0 errors`),
`composer lint` (`"result":"passed"`).

**Isolation replay.** This reproduces `package-integrity.yml`'s "Wire sibling path repositories"
step for every package that has siblings, on scratch copies so the real manifests are never
stamped. Save it to the scratchpad, not the repo:

```bash
#!/usr/bin/env bash
# usage: STAMP=0.6.x-dev bash iso.sh   (STAMP = the new branch alias)
set -u; export COMPOSER_ALLOW_SUPERUSER=1
W="$PWD/iso"; SRC=/srv/php-fhir-tools; mkdir -p "$W"
for d in "$SRC"/src/Component/* "$SRC"/src/Bundle/*; do
  [ -f "$d/composer.json" ] || continue
  mkdir -p "$W/$(basename "$d")"; cp "$d/composer.json" "$W/$(basename "$d")/"
done
fail=0
for pkg in "$W"/*; do
  self=$(jq -r .name "$pkg/composer.json")
  jq -e '.require|keys[]|select(startswith("ardenexal/"))' "$pkg/composer.json" >/dev/null || continue
  ( cd "$pkg"; composer config minimum-stability dev; composer config prefer-stable true
    for dir in "$W"/*; do name=$(jq -r '.name//empty' "$dir/composer.json"); [ "$name" = "$self" ] && continue
      composer config "repositories.$(echo "$name"|tr / -)" path "$dir"
      composer config version "$STAMP" --working-dir="$dir"; done
    composer update --no-dev --dry-run -n >out.log 2>&1 && echo "OK   $self" || { echo "FAIL $self"; tail -8 out.log; exit 1; }
  ) || fail=1
  for dir in "$W"/*; do jq 'del(.version)' "$dir/composer.json" >"$dir/t" && mv "$dir/t" "$dir/composer.json"; done
done; exit $fail
```

- Run it with the new alias, e.g. `STAMP=0.6.x-dev`. Expect 7 `OK` lines; `fhir-metadata` has no
  siblings and is skipped.
- **Negative control:** re-run it with the previous alias, e.g. `STAMP=0.5.x-dev`, and expect
  `FAIL` with `requires ardenexal/fhir-metadata ^0.6, found ...[0.5.x-dev]`. Unless it fails, the
  passing run proves nothing.

**Declared conflicts.** For each `conflict`, resolve a scratch consumer that requires both
packages. It must succeed with the new version and be refused with the old one.

**Symfony 6.4 harness.** Its `composer.lock` is gitignored and survives the bump. A stale one fails
locally with "is in the lock file as 0.5.999 but that does not satisfy 0.6.999", while CI, which
has no lock, passes. Move the lock aside first, then:

```bash
COMPOSER_ALLOW_SUPERUSER=1 composer install --working-dir=tests/Compat/symfony-6.4 --prefer-dist -n
tests/Compat/symfony-6.4/acceptance.sh     # expect "all checks passed against symfony/console 6.4.x"
```

## Step 5: Handoff Block A (commit, push, PR)

Send only this block. List the files explicitly (never `git add -A`), chain with `&&`, and follow
`.github/git-commit-instructions.md`: conventional subject of 72 characters or fewer, body saying
why, no AI mentions, and no `-S` unless a signing key exists.

```bash
git add CHANGELOG.md src/Bundle/FHIRBundle/composer.json src/Component/*/composer.json \
  tests/Compat/symfony-6.4/composer.json tests/Compat/symfony-6.4/README.md \
  .github/workflows/package-integrity.yml .github/workflows/split-monorepo.yml \
&& git commit -m "chore(release): prepare X.Y.Z" -m "<why>" \
&& git push -u origin chore/release-X-Y-Z \
&& gh pr create --title "chore(release): prepare X.Y.Z" --body "<summary>"
```

Then stop and wait for the user.

## Step 6: PR checks

`gh pr checks <n>`. The bump-sensitive checks are all seven `Undeclared deps (<pkg>)` jobs,
`package-integrity-passed` and `symfony-lower-bound`. A stale CI stamp fails every
`Undeclared deps` job except `fhir-metadata`'s, which has no siblings; a single green job among
red ones is the sign of that. Merging needs an approving review (`REVIEW_REQUIRED`).

## Step 7: Verify the merge before tagging

```bash
git fetch origin --tags
gh pr view <n> --json state,mergeCommit --jq '"\(.state) \(.mergeCommit.oid)"'    # MERGED <sha>
git log -1 --format=%H origin/main                                                # same <sha>
git show origin/main:src/Bundle/FHIRBundle/composer.json | grep -E 'ardenexal/|dev-main'
git show origin/main:CHANGELOG.md | grep -m2 '^## \['                             # [Unreleased], [X.Y.Z]
git ls-remote --tags origin X.Y.Z                                                 # must print nothing
gh run list --branch main --workflow "Split Monorepo" --limit 1                   # merge-commit run: completed
```

Wait for the main-branch split run on the merge commit to finish, so the tag's split doesn't push
to the same repos at the same time.

Check `Main Branch` on `main`. As of 0.6.1, `component-tests (codegen)` and `cross-version-test`
fail on every run because they never download the FHIR core packages. That is pre-existing and not
caused by the release. Any other red job blocks the tag.

## Step 8: Handoff Block B (publish)

Send it in its own message, naming the SHA verified in Step 7. Following `release-notes.md`,
draft the notes to a scratch file: lead with the user-visible change, then a heads-up for breaking
changes, highlights, a breaking-change migration table, upgrade steps, and CHANGELOG and compare
links.

```bash
gh release create X.Y.Z --target <merge-commit-sha> --title X.Y.Z --latest --notes-file <notes.md>
```

`gh release create` creates the tag with the user's token, so it fires the `push: tags` trigger
exactly as `git push origin X.Y.Z` would.

## Step 9: Verify the publish

```bash
gh run list --workflow "Split Monorepo" --limit 3 --json headBranch,status,conclusion,databaseId \
  --jq '.[]|select(.headBranch=="X.Y.Z")'                        # completed success
gh run view <id> --json jobs --jq '.jobs[]|"\(.conclusion) \(.name)"'   # all 10 jobs success
for r in fhir-bundle fhir-code-generation fhir-serialization fhir-validation fhir-path \
         fhir-metadata fhir-models cda-sd-models; do
  printf '%-22s %s\n' "$r" "$(git ls-remote --tags https://github.com/Ardenexal/$r.git X.Y.Z | cut -c1-9)"
done                                                              # every row has a SHA
curl -s https://repo.packagist.org/p2/ardenexal/fhir-bundle.json \
  | jq -r '.packages["ardenexal/fhir-bundle"][0] | "\(.version) \(.require)"'   # X.Y.Z with the new pins
```

If `split-complete` fails, the release is partially published. Re-run the workflow and confirm
every `Split <package>` leg is green.

## Recovery: a bad version is already published

Leave it, and ship the next patch:

1. Fix the manifests. Floor the pins above the bad version, e.g. `^0.6.1`, so nothing resolves a
   sibling from the bad set.
2. Add a `## [X.Y.Z+1]` CHANGELOG section. Put a `**Known issue:** ... Use X.Y.Z+1.` line under the
   bad version's heading, and keep that section to what its tag actually shipped.
3. Run Steps 4-9 as normal. The release notes open with "skip X.Y.Z".

Deleting and re-tagging looks cleaner but breaks consumers who already locked the version and can
leave Composer caches serving the old content.

## Why this playbook exists

On 2026-09-23 the 0.6.0 handoff was a single paste block: commit, push, PR, then
`# after the PR merges:` followed by the tag push. `git commit -S` failed because no signing key is
configured, the block kept running, and `0.6.0` was tagged on the unbumped `main`. Within a minute
the split published all eight packages with `^0.5` sibling pins, which paired the new CDA models
with a serializer that cannot read them. Packagist indexed them, and 0.6.1 superseded them. Hard
Rules 1-3 and Step 7 are the structural fix.

## Related

- `changelog.md` (CHANGELOG discipline) and `release-notes.md` (release-body structure)
- `.github/git-commit-instructions.md` (commit format)
- `.github/workflows/split-monorepo.yml` and `.github/workflows/package-integrity.yml`
- `tests/Compat/symfony-6.4/README.md`
