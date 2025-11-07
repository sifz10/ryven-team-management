# PowerShell script to update PHP upload limits
# Run this script as Administrator

$phpIniPath = "C:\Program Files\php-8.4.13\php.ini"

Write-Host "Updating PHP configuration..." -ForegroundColor Green
Write-Host "File: $phpIniPath" -ForegroundColor Yellow

# Read the file content
$content = Get-Content $phpIniPath

# Update the lines
$content = $content -replace '^upload_max_filesize\s*=\s*\d+M', 'upload_max_filesize = 100M'
$content = $content -replace '^post_max_size\s*=\s*\d+M', 'post_max_size = 100M'

# Write back to file
$content | Set-Content $phpIniPath

Write-Host "`nPHP configuration updated successfully!" -ForegroundColor Green
Write-Host "New settings:" -ForegroundColor Yellow
Write-Host "  upload_max_filesize = 100M"
Write-Host "  post_max_size = 100M"
Write-Host "`nPlease restart your development server for changes to take effect." -ForegroundColor Cyan
Write-Host "Run: composer run dev" -ForegroundColor White
