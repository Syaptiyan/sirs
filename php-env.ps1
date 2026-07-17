# SIRS PHP Environment Setup
$phpDir = "C:\Users\User\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
$env:Path = "$env:Path;$phpDir"
Write-Output "PHP environment loaded!"
Write-Output "PHP: $(php --version | Select-Object -First 1)"
Write-Output "Composer: $(composer --version)"
