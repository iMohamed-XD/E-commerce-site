#!/usr/bin/env bash
set -euo pipefail

MODE="${1:-deploy}"
BASE_URL="${COOLIFY_BASE_URL:-}"
API_TOKEN="${COOLIFY_API_TOKEN:-}"
RESOURCE_UUID="${COOLIFY_RESOURCE_UUID:-}"
APP_URL="${COOLIFY_APP_URL:-}"
WAIT_SECONDS="${WAIT_SECONDS:-120}"
SKIP_MANIFEST_CHECK="${SKIP_MANIFEST_CHECK:-false}"

if [[ "${MODE}" != "deploy" && "${MODE}" != "start" ]]; then
  echo "Usage: scripts/coolify-force-redeploy.sh [deploy|start]"
  exit 1
fi

if [[ -z "${BASE_URL}" ]]; then
  echo "COOLIFY_BASE_URL is required."
  exit 1
fi

if [[ -z "${API_TOKEN}" ]]; then
  echo "COOLIFY_API_TOKEN is required."
  exit 1
fi

if [[ -z "${RESOURCE_UUID}" ]]; then
  echo "COOLIFY_RESOURCE_UUID is required."
  exit 1
fi

normalize_url() {
  local url="$1"
  printf "%s" "${url%/}"
}

manifest_hash() {
  local app_url="$1"
  local normalized manifest_url content
  normalized="$(normalize_url "${app_url}")"
  manifest_url="${normalized}/build/manifest.json"
  content="$(curl -fsSL "${manifest_url}")"
  printf "%s" "${content}" | sha256sum | awk '{print $1}'
}

BASE_URL="$(normalize_url "${BASE_URL}")"

if [[ "${MODE}" == "deploy" ]]; then
  ENDPOINT="${BASE_URL}/deploy?uuid=${RESOURCE_UUID}&force=true"
else
  ENDPOINT="${BASE_URL}/applications/${RESOURCE_UUID}/start?force=true"
fi

BEFORE_HASH=""
if [[ "${SKIP_MANIFEST_CHECK}" != "true" && -n "${APP_URL}" ]]; then
  set +e
  BEFORE_HASH="$(manifest_hash "${APP_URL}")"
  STATUS=$?
  set -e
  if [[ ${STATUS} -eq 0 ]]; then
    echo "Manifest hash before deploy: ${BEFORE_HASH}"
  else
    echo "Warning: Could not read pre-deploy manifest hash."
  fi
fi

echo "Triggering Coolify forced rebuild using mode '${MODE}'..."
echo "Endpoint: ${ENDPOINT}"

RESPONSE="$(
  curl -fsSL \
    -H "Authorization: Bearer ${API_TOKEN}" \
    -H "Accept: application/json" \
    "${ENDPOINT}"
)"

echo "API response:"
echo "${RESPONSE}"

if echo "${RESPONSE}" | grep -Eq '"force_rebuild"[[:space:]]*:[[:space:]]*true'; then
  echo "force_rebuild=true detected in response."
else
  echo "Warning: force_rebuild flag not explicitly present in this response payload."
fi

if [[ "${SKIP_MANIFEST_CHECK}" != "true" && -n "${APP_URL}" ]]; then
  echo "Waiting ${WAIT_SECONDS} seconds before post-deploy hash check..."
  sleep "${WAIT_SECONDS}"
  set +e
  AFTER_HASH="$(manifest_hash "${APP_URL}")"
  STATUS=$?
  set -e
  if [[ ${STATUS} -ne 0 ]]; then
    echo "Warning: Could not read post-deploy manifest hash."
  else
    echo "Manifest hash after deploy: ${AFTER_HASH}"
    if [[ -n "${BEFORE_HASH}" && "${BEFORE_HASH}" == "${AFTER_HASH}" ]]; then
      echo "Warning: Manifest hash did not change. If no frontend changes were deployed, this can be expected."
    else
      echo "Manifest hash changed."
    fi
  fi
fi
