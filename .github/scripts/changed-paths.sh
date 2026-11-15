#!/usr/bin/env bash
#
# Prints the paths a pull request touches, one per line, for changed-areas.sh to classify.
# Usage: changed-paths.sh <owner/repo> <pr-number>   (needs GH_TOKEN in the environment)
#
# Exits non-zero if the list cannot be proven complete, and prints nothing in that case. The
# caller must treat that as "every area changed" rather than as an empty change set: a list
# that is short by one file is indistinguishable from a smaller pull request, and the missing
# file is exactly the one that would have kept a check running.
#
# Two completeness hazards, both of which this guards:
#
#   Truncation. `gh api --paginate` streams each page as it arrives, so a failure on page N
#   leaves a partial file behind while the shell sees whatever exit status came last. The
#   endpoint also caps out at 3000 files with no error of any kind, and a regeneration diff
#   in this repository runs to several thousand. Both are caught by comparing the row count
#   against the pull request's own changed_files, rather than by trusting an exit status.
#
#   Renames. The API reports a rename as ONE entry carrying both `filename` (the new path)
#   and `previous_filename` (the old one). Reading only `filename` means
#   `git mv recipe/fhir-bundle/1.0/README.md docs/x.md` arrives as a lone docs/ path, and the
#   move that breaks FlexRecipeTest reads as documentation. Both paths are emitted, so the
#   change is significant under whichever one is.
set -euo pipefail

repo=${1:?usage: changed-paths.sh <owner/repo> <pr-number>}
number=${2:?usage: changed-paths.sh <owner/repo> <pr-number>}

work=$(mktemp -d)
trap 'rm -rf "$work"' EXIT

# One row per changed file, so the row count is comparable with changed_files even though a
# renamed row carries two paths.
if ! gh api --paginate "repos/${repo}/pulls/${number}/files" \
    --jq '.[] | [.filename, (.previous_filename // "")] | @tsv' > "$work/files.tsv"; then
    echo 'changed-paths: could not list the pull request files' >&2
    exit 1
fi

if ! expected=$(gh api "repos/${repo}/pulls/${number}" --jq '.changed_files'); then
    echo 'changed-paths: could not read changed_files to verify completeness' >&2
    exit 1
fi

actual=$(grep -c '' < "$work/files.tsv" || true)

if [ "$actual" != "$expected" ]; then
    printf 'changed-paths: got %s rows but the pull request reports %s changed files\n' \
        "$actual" "$expected" >&2
    exit 1
fi

# Split the two columns onto their own lines and drop the empty second column of a
# non-renamed row.
tr '\t' '\n' < "$work/files.tsv" | grep -v '^$' || true
