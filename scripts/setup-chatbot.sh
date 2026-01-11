#!/bin/bash
# Quick setup script for chatbot system

echo "🤖 Setting up Chatbot Widget System..."

# Run migrations
echo "Running migrations..."
php artisan migrate --table=chatbot_widgets
php artisan migrate --table=chat_conversations  
php artisan migrate --table=chat_messages

# Create a test widget
echo "Creating test widget..."
php artisan tinker --execute='
App\Models\ChatbotWidget::create([
    "name" => "Test Widget",
    "domain" => "localhost",
    "installed_in" => "Development",
    "welcome_message" => "Welcome! How can we help you today?",
    "is_active" => true,
]);

$widget = App\Models\ChatbotWidget::latest()->first();
echo "\n✅ Widget created!\n";
echo "API Token: " . $widget->api_token . "\n";
echo "Copy this token to use in your external app.\n";
'

echo "✅ Setup complete! You can now:"
echo "1. Access admin dashboard at: /admin/chatbot"
echo "2. Install widget using the token generated above"
echo "3. Make sure Reverb is running: php artisan reverb:start"
