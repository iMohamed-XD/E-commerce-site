# Coolify Clean Rebuild Workflow (Hetzner + Webhook)

This project keeps webhook auto-deploy as the default fast path.
When frontend UI/CSS looks stale, trigger a one-time forced no-cache rebuild.

## 1) Keep normal webhook deploys

- Keep webhook auto-deploy enabled in Coolify.
- Keep normal deploy settings optimized for speed.

## 2) One-time force rebuild options

### Option A: Coolify UI (manual)

1. Open your application in Coolify.
2. Start a new deploy/restart action.
3. Enable **Force Rebuild / Without Cache**.
4. Run deployment.

### Option B: Coolify API (scripted)

Set environment variables (or pass parameters to the script):

- `COOLIFY_BASE_URL` (example: `https://coolify.example.com/api/v1`)
- `COOLIFY_API_TOKEN`
- `COOLIFY_RESOURCE_UUID`
- `COOLIFY_APP_URL` (optional, used for manifest hash check)

PowerShell (Windows):

```powershell
pwsh ./scripts/coolify-force-redeploy.ps1 -Mode deploy
```

Bash (Linux/macOS):

```bash
bash ./scripts/coolify-force-redeploy.sh deploy
```

Alternative start endpoint mode:

```powershell
pwsh ./scripts/coolify-force-redeploy.ps1 -Mode start
```

```bash
bash ./scripts/coolify-force-redeploy.sh start
```

## 3) Required API endpoints

- `GET /deploy?uuid=<RESOURCE_UUID>&force=true`
- `GET /applications/<RESOURCE_UUID>/start?force=true`

The scripts above use these endpoints and try to detect `force_rebuild=true` in API responses.

## 4) Test checklist

1. Push a small visible frontend change.
2. Let webhook deploy run normally.
3. If stale UI remains, run one forced rebuild.
4. Confirm deployment response/logs include force rebuild metadata when available.
5. Compare `public/build/manifest.json` hash before/after (script does this if `COOLIFY_APP_URL` is set).
6. Hard refresh browser (`Ctrl+F5`) and verify the UI update is visible.

## 5) Coolify setting recommendation

- Keep **Include Source Commit in Build** disabled for normal deploy speed.
- Use manual forced rebuild only when stale assets are suspected.
