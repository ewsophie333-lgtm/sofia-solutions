param(
  [ValidateSet("vulnerable", "secure")]
  [string]$Mode = "vulnerable"
)

$ErrorActionPreference = "Stop"

Write-Host "== Ejecutando ataques en modo $Mode =="

if ($Mode -eq "vulnerable") {
  npm run attack:sqli:vuln
  npm run attack:xss:vuln
  npm run attack:traversal:vuln
  npm run attack:payment:vuln
  npm run attack:bruteforce:vuln
  npm run services:matrix:vuln
} else {
  npm run attack:sqli:secure
  npm run attack:xss:secure
  npm run attack:traversal:secure
  npm run attack:payment:secure
  npm run attack:bruteforce:secure
  npm run services:matrix:secure
}

