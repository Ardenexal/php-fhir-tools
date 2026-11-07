#!/usr/bin/env bash
#
# Runs the shared acceptance checks against this harness's install of Symfony 8.1, the newest line
# ardenexal/fhir-code-generation declares. The checks and the console application live in
# ../symfony-6.4, so both ends of the supported range are held to the same assertions.
#
# Usage:
#   composer install --working-dir=tests/Compat/symfony-8.1
#   tests/Compat/symfony-8.1/acceptance.sh [--with-generation]

set -euo pipefail

harness_dir="$(cd "$(dirname "$0")" && pwd)"

COMPAT_HARNESS_DIR="$harness_dir" exec "$harness_dir/../symfony-6.4/acceptance.sh" "$@"
