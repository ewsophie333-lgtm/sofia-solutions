param(
  [string]$ProjectRoot = "C:\Users\sgomez\Desktop\sofia-solutions"
)

$ErrorActionPreference = "Stop"

function Convert-MarkdownLine {
  param([string]$Line)

  $escaped = [System.Net.WebUtility]::HtmlEncode($Line)
  $escaped = [regex]::Replace($escaped, '\*\*(.+?)\*\*', '<strong>$1</strong>')
  $escaped = [regex]::Replace($escaped, '`(.+?)`', '<code>$1</code>')
  return $escaped
}

function Convert-MarkdownToHtml {
  param(
    [string]$Markdown,
    [string]$BasePath
  )

  $lines = $Markdown -split "`r?`n"
  $html = New-Object System.Collections.Generic.List[string]
  $inList = $false
  $inCode = $false

  foreach ($rawLine in $lines) {
    $line = $rawLine.TrimEnd()

    if ($line -match '^```') {
      if ($inCode) {
        $html.Add("</pre>")
        $inCode = $false
      } else {
        if ($inList) {
          $html.Add("</ul>")
          $inList = $false
        }
        $html.Add("<pre>")
        $inCode = $true
      }
      continue
    }

    if ($inCode) {
      $html.Add([System.Net.WebUtility]::HtmlEncode($rawLine))
      continue
    }

    if ([string]::IsNullOrWhiteSpace($line)) {
      if ($inList) {
        $html.Add("</ul>")
        $inList = $false
      }
      continue
    }

    if ($line.StartsWith("### ")) {
      if ($inList) {
        $html.Add("</ul>")
        $inList = $false
      }
      $html.Add("<h3>$(Convert-MarkdownLine $line.Substring(4))</h3>")
      continue
    }

    if ($line.StartsWith("## ")) {
      if ($inList) {
        $html.Add("</ul>")
        $inList = $false
      }
      $html.Add("<h2>$(Convert-MarkdownLine $line.Substring(3))</h2>")
      continue
    }

    if ($line.StartsWith("# ")) {
      if ($inList) {
        $html.Add("</ul>")
        $inList = $false
      }
      $html.Add("<h1>$(Convert-MarkdownLine $line.Substring(2))</h1>")
      continue
    }

    if ($line -match '^\- ') {
      if (-not $inList) {
        $html.Add("<ul>")
        $inList = $true
      }
      $html.Add("<li>$(Convert-MarkdownLine $line.Substring(2))</li>")
      continue
    }

    if ($line -match '^\d+\.\s+') {
      if ($inList) {
        $html.Add("</ul>")
        $inList = $false
      }
      $content = $line -replace '^\d+\.\s+', ''
      $html.Add("<p><strong>$(Convert-MarkdownLine $content)</strong></p>")
      continue
    }

    if ($line -match '^!\[(.*?)\]\((.*?)\)$') {
      if ($inList) {
        $html.Add("</ul>")
        $inList = $false
      }
      $alt = [System.Net.WebUtility]::HtmlEncode($matches[1])
      $src = $matches[2]
      $resolved = [System.IO.Path]::GetFullPath((Join-Path $BasePath $src))
      $html.Add("<p><img src=""$resolved"" alt=""$alt"" style=""max-width:100%; border:1px solid #ddd; margin:8pt 0;"" /></p>")
      continue
    }

    if ($inList) {
      $html.Add("</ul>")
      $inList = $false
    }

    $html.Add("<p>$(Convert-MarkdownLine $line)</p>")
  }

  if ($inList) {
    $html.Add("</ul>")
  }

  if ($inCode) {
    $html.Add("</pre>")
  }

  return ($html -join "`r`n")
}

function Build-WordCompatibleDoc {
  param(
    [string]$Title,
    [string]$MarkdownPath,
    [string]$OutputPath
  )

  $markdown = Get-Content $MarkdownPath -Raw -Encoding UTF8
  $basePath = Split-Path -Parent $MarkdownPath
  $body = Convert-MarkdownToHtml -Markdown $markdown -BasePath $basePath

  $document = @"
<html>
<head>
  <meta charset="utf-8">
  <title>$Title</title>
  <style>
    body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; color: #111; margin: 2.2cm; line-height: 1.5; }
    h1 { font-size: 19pt; margin: 0 0 14pt; }
    h2 { font-size: 15pt; margin: 18pt 0 8pt; }
    h3 { font-size: 12.5pt; margin: 14pt 0 6pt; }
    p { margin: 0 0 8pt; }
    ul { margin: 0 0 10pt 20pt; }
    li { margin: 0 0 4pt; }
    pre { background: #f4f4f4; border: 1px solid #ddd; padding: 10pt; white-space: pre-wrap; font-family: Consolas, monospace; font-size: 9.5pt; }
    code { font-family: Consolas, monospace; font-size: 9.5pt; }
  </style>
</head>
<body>
$body
</body>
</html>
"@

  [System.IO.File]::WriteAllText($OutputPath, $document, [System.Text.UTF8Encoding]::new($false))
}

$parts34Source = Join-Path $ProjectRoot "MEMORIA-APARTADOS-3-4.md"
$fullSource = Join-Path $ProjectRoot "sofia-backend\DOCUMENTACION-APA.md"

Build-WordCompatibleDoc -Title "Memoria ASIX Apartados 3 y 4" -MarkdownPath $parts34Source -OutputPath (Join-Path $ProjectRoot "Memoria-ASIX-Apartados-3-4.doc")
Build-WordCompatibleDoc -Title "Memoria ASIX Completa" -MarkdownPath $fullSource -OutputPath (Join-Path $ProjectRoot "Memoria-ASIX-Completa.doc")
Build-WordCompatibleDoc -Title "Memoria Apartados 3 y 4" -MarkdownPath $parts34Source -OutputPath (Join-Path $ProjectRoot "MEMORIA-APARTADOS-3-4.doc")
