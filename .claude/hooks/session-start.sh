#!/usr/bin/env bash
# SessionStart hook: give a session what this project assumes is already installed -
# Composer dependencies everywhere, and the goat-flow CLI in a remote container.
#
# Neither step is fatal. A failed install costs a warning rather than the session, and
# the warning says which capability went with it.
set -euo pipefail

project_dir="${CLAUDE_PROJECT_DIR:-$(git rev-parse --show-toplevel)}"

# The Composer dependencies, ahead of the guard below because every kind of session
# loads `.mcp.json`.
#
# vendor/ is gitignored, so a fresh clone or a new worktree has none. The symfony-ai-mate
# MCP server runs vendor/symfony/ai-mate/bin/mate.php, so it exits before its first
# message and the session reports CONNECTION_CLOSED with nothing naming vendor/. An
# existing vendor/ is left alone: this restores a missing install, it does not update one.
#
# No --ignore-platform-req: roave/security-advisories refuses a PHP with published
# advisories, and bypassing that is the owner's call rather than a startup default.
install_composer_deps() {
  local composer_log

  if [ -f "${project_dir}/vendor/autoload.php" ]; then
    echo "[session-start] composer dependencies already present"
    return 0
  fi
  if ! command -v composer >/dev/null 2>&1; then
    echo "[session-start] WARNING: no composer; vendor/ is missing, so tests, PHPStan and the symfony-ai-mate MCP server are unavailable" >&2
    return 0
  fi

  echo "[session-start] installing composer dependencies"
  if composer_log="$(composer install --working-dir="$project_dir" --no-interaction --prefer-dist --no-progress 2>&1)"; then
    echo "[session-start] composer dependencies ready"
  else
    echo "[session-start] WARNING: composer install failed" >&2
    echo "$composer_log" | tail -8 >&2
    echo "[session-start] WARNING: tests, PHPStan and the symfony-ai-mate MCP server are unavailable this session" >&2
  fi
}
install_composer_deps

# Only a fresh remote container needs the rest. Desktop and bridge sessions use whatever
# the host has installed.
if [ "${CLAUDE_CODE_REMOTE:-}" != "true" ]; then
  exit 0
fi

# The goat-flow CLI.
#
# `goat-flow index`, `goat-flow stats --check` and the skills' redaction step all need
# it, but it is an npm package rather than part of the checkout, so a fresh container
# has none of them.
#
# The version is read from .goat-flow/config.yaml rather than pinned here, because that
# file is what declares which framework version this project is installed at. A CLI
# ahead of it validates against rules the installed surface does not carry.
install_goat_flow() {
  local wanted npm_log
  wanted="$(sed -n 's/^version:[[:space:]]*"\{0,1\}\([0-9][^"[:space:]]*\)"\{0,1\}.*/\1/p' \
    "${project_dir}/.goat-flow/config.yaml" 2>/dev/null | head -1)"

  if [ -z "$wanted" ]; then
    echo "[session-start] WARNING: no version: line in .goat-flow/config.yaml" >&2
    echo "[session-start] WARNING: skipping the goat-flow CLI; index / stats --check are unavailable" >&2
    return 0
  fi

  # `goat-flow --version` prints exactly "goat-flow v1.18.0", so match the whole line -
  # a substring match would accept v1.18.0 for a wanted 1.1.
  if command -v goat-flow >/dev/null 2>&1 &&
     goat-flow --version 2>/dev/null | grep -qFx "goat-flow v${wanted}"; then
    echo "[session-start] goat-flow ${wanted} already present"
    return 0
  fi

  echo "[session-start] installing goat-flow ${wanted}"
  if npm_log="$(npm install -g --no-fund --no-audit "@blundergoat/goat-flow@${wanted}" 2>&1)"; then
    echo "[session-start] goat-flow ready: $(goat-flow --version 2>&1 | head -1)"
  else
    echo "[session-start] WARNING: could not install @blundergoat/goat-flow@${wanted}" >&2
    echo "$npm_log" | tail -5 >&2
    echo "[session-start] WARNING: goat-flow index / stats --check are unavailable this session" >&2
  fi
}
install_goat_flow
