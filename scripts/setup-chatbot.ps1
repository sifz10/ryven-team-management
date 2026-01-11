# Setup Chatbot Widget System - Windows PowerShell
# Run: .\scripts\setup-chatbot.ps1

Write-Host "🤖 Setting up Chatbot Widget System..." -ForegroundColor Cyan

# Run migrations
Write-Host "Running migrations..." -ForegroundColor Yellow
php artisan migrate

# Create a test widget
Write-Host "Creating test widget..." -ForegroundColor Yellow

$tinkerCode = @'
$widget = App\Models\ChatbotWidget::create([
    "name" => "Test Widget",
    "domain" => "localhost",
    "installed_in" => "Development",
    "welcome_message" => "Welcome! How can we help you today?",
    "is_active" => true,
]);

echo "\n✅ Widget created!\n";
echo "API Token: " . $widget->api_token . "\n";
echo "Copy this token to use in your external app.\n";
'@

php artisan tinker --execute=$tinkerCode

Write-Host "`n✅ Setup complete!" -ForegroundColor Green
Write-Host "You can now:`n" -ForegroundColor Green
Write-Host "1. Access admin dashboard at: /admin/chatbot" -ForegroundColor Cyan
Write-Host "2. Install widget using the token generated above" -ForegroundColor Cyan
Write-Host "3. Make sure Reverb is running: php artisan reverb:start" -ForegroundColor Cyan
