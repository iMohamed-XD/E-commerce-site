param(
    [ValidateSet("deploy", "start")]
    [string]$Mode = "deploy",
    [string]$BaseUrl = $env:COOLIFY_BASE_URL,
    [string]$ApiToken = $env:COOLIFY_API_TOKEN,
    [string]$ResourceUuid = $env:COOLIFY_RESOURCE_UUID,
    [string]$AppUrl = $env:COOLIFY_APP_URL,
    [int]$WaitSeconds = 120,
    [switch]$SkipManifestCheck
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Get-HashHex {
    param([byte[]]$Bytes)
    return ([System.BitConverter]::ToString($Bytes)).Replace("-", "").ToLowerInvariant()
}

function Get-ManifestHash {
    param([string]$PublicAppUrl)

    if ([string]::IsNullOrWhiteSpace($PublicAppUrl)) {
        return $null
    }

    $normalized = $PublicAppUrl.TrimEnd("/")
    $manifestUrl = "$normalized/build/manifest.json"
    $response = Invoke-WebRequest -Uri $manifestUrl -Method Get -UseBasicParsing
    $content = $response.Content
    $bytes = [System.Text.Encoding]::UTF8.GetBytes($content)
    $hash = [System.Security.Cryptography.SHA256]::HashData($bytes)

    return @{
        url = $manifestUrl
        hash = (Get-HashHex -Bytes $hash)
    }
}

if ([string]::IsNullOrWhiteSpace($BaseUrl)) {
    throw "COOLIFY_BASE_URL is required."
}

if ([string]::IsNullOrWhiteSpace($ApiToken)) {
    throw "COOLIFY_API_TOKEN is required."
}

if ([string]::IsNullOrWhiteSpace($ResourceUuid)) {
    throw "COOLIFY_RESOURCE_UUID is required."
}

$normalizedBaseUrl = $BaseUrl.TrimEnd("/")

if ($Mode -eq "deploy") {
    $endpoint = "$normalizedBaseUrl/deploy?uuid=$ResourceUuid&force=true"
} else {
    $endpoint = "$normalizedBaseUrl/applications/$ResourceUuid/start?force=true"
}

$headers = @{
    Authorization = "Bearer $ApiToken"
    Accept        = "application/json"
}

$beforeManifest = $null
if (-not $SkipManifestCheck -and -not [string]::IsNullOrWhiteSpace($AppUrl)) {
    try {
        $beforeManifest = Get-ManifestHash -PublicAppUrl $AppUrl
        Write-Host "Manifest hash before deploy:" $beforeManifest.hash
    } catch {
        Write-Warning "Could not read pre-deploy manifest hash: $($_.Exception.Message)"
    }
}

Write-Host "Triggering Coolify forced rebuild using mode '$Mode'..."
Write-Host "Endpoint:" $endpoint

$response = Invoke-RestMethod -Uri $endpoint -Method Get -Headers $headers
$responseJson = $response | ConvertTo-Json -Depth 20

Write-Host "API response:"
Write-Output $responseJson

if ($responseJson -match '"force_rebuild"\s*:\s*true') {
    Write-Host "force_rebuild=true detected in response."
} else {
    Write-Warning "force_rebuild flag not explicitly present in this response payload."
}

if (-not $SkipManifestCheck -and -not [string]::IsNullOrWhiteSpace($AppUrl)) {
    Write-Host "Waiting $WaitSeconds seconds before post-deploy hash check..."
    Start-Sleep -Seconds $WaitSeconds

    try {
        $afterManifest = Get-ManifestHash -PublicAppUrl $AppUrl
        Write-Host "Manifest hash after deploy:" $afterManifest.hash

        if ($beforeManifest -and $beforeManifest.hash -eq $afterManifest.hash) {
            Write-Warning "Manifest hash did not change. If this deploy had no frontend changes, this may be expected."
        } else {
            Write-Host "Manifest hash changed."
        }
    } catch {
        Write-Warning "Could not read post-deploy manifest hash: $($_.Exception.Message)"
    }
}

