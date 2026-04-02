# Demo App Deployment

## Architecture

```
push to main (demo/ changes)  ─┐
new version tag                ─┤─▶ GitHub Actions ─▶ build image ─▶ push to GHCR
                                      │
                                      ▼ (via Tailscale)
                                   SSH into server ─▶ docker compose pull + up -d
```

The container runs [FrankenPHP](https://frankenphp.dev/) in worker mode, serving HTTP on port 80. TLS termination is handled externally by your reverse proxy.

## Trigger Conditions

| Event | Condition | Result |
|-------|-----------|--------|
| Push to `main` | Files changed under `demo/` or `.github/workflows/deploy.yml` | Build + deploy `:latest` |
| Version tag (e.g. `0.3`) | Tag commit must include a `demo/` file change (GitHub paths filter applies to tags too) | Build + deploy `:0.3` and `:latest` |

> **Tagging tip**: If your release commit doesn't touch `demo/`, create the tag on a commit that does — for example, update `demo/DEPLOYMENT.md` or bump a version comment in any demo file.

## Required GitHub Secrets

Configure these under **Settings → Secrets and variables → Actions** in the repository:

| Secret | Description |
|--------|-------------|
| `TS_OAUTH_CLIENT_ID` | Tailscale OAuth client ID for the CI node |
| `TS_OAUTH_CLIENT_SECRET` | Tailscale OAuth client secret |
| `SERVER_TAILSCALE_IP` | Tailscale IP address of the deploy target |
| `SERVER_USER` | SSH username on the deploy target |
| `SERVER_SSH_KEY` | SSH private key (PEM) for the deploy target |
| `GHCR_TOKEN` | GitHub PAT with `read:packages` scope, used to pull the image on the target |
| `DEPLOY_COMPOSE_PATH` | Absolute path to the `docker-compose.yml` file on the deploy target |

`GITHUB_TOKEN` (auto-provided by Actions) is used for pushing to GHCR from CI.

## Target Host Setup

The deploy target needs Docker installed and a `docker-compose.yml` in the path configured by `DEPLOY_COMPOSE_PATH`:

```yaml
services:
  demo:
    image: ghcr.io/ardenexal/php-fhir-tools:latest
    restart: unless-stopped
    ports:
      - "8080:80"
    env_file:
      - .env
```

The `.env` file in the same directory must set at minimum:

```env
APP_SECRET=<random 32-char hex string>
```

Generate a value with: `openssl rand -hex 32`

## Reverse Proxy

The container serves plain HTTP on port 80 (`SERVER_NAME="http://:80"`). Configure your reverse proxy to forward to the host port you mapped (e.g. `8080`). Example Caddy block:

```
your-domain.example.com {
    reverse_proxy localhost:8080
}
```

## Tailscale ACL

The GitHub Actions runner joins your tailnet using an OAuth client. Before this will work:

1. Create an OAuth client at [tailscale.com/settings/oauth-clients](https://login.tailscale.com/admin/settings/oauthclients) with the `devices` write scope
2. Ensure `tag:ci` exists in your ACL policy (Tailscale requires OAuth clients to specify an existing tag)
3. Grant `tag:ci` SSH access to the deploy target in your ACL policy

Example ACL entry:

```json
{
  "action": "accept",
  "src": ["tag:ci"],
  "dst": ["tag:server:22"]
}
```

## Building Locally

From the repository root:

```bash
docker build -f demo/Dockerfile -t fhir-demo .
docker run -p 8080:80 -e APP_SECRET=$(openssl rand -hex 32) fhir-demo
```

Then open `http://localhost:8080`.
