#!/bin/bash

# Chatbot Widget Production Setup Verification Script
# Run this on your production server to verify everything is set up correctly

echo "🔍 Chatbot Widget Production Verification"
echo "=========================================="
echo ""

# 1. Check if migrations are run
echo "1️⃣  Checking migrations..."
php artisan migrate:status 2>/dev/null | grep -q "chat_conversations" && {
    echo "✅ Migrations appear to be run"
} || {
    echo "❌ Migrations may not be run - tables might not exist"
    echo "   Run: php artisan migrate"
}
echo ""

# 2. Check if database tables exist
echo "2️⃣  Checking database tables..."
php artisan tinker --execute="
    echo 'Chatbot Widgets: ' . \App\Models\ChatbotWidget::count() . \"\n\";
    echo 'Chat Conversations: ' . \App\Models\ChatConversation::count() . \"\n\";
    echo 'Chat Messages: ' . \App\Models\ChatMessage::count() . \"\n\";
" 2>/dev/null

echo ""

# 3. Check if any widget exists
echo "3️⃣  Checking chatbot widgets..."
php artisan tinker --execute="
    \$widget = \App\Models\ChatbotWidget::first();
    if (\$widget) {
        echo '✅ Widget found: ' . \$widget->name . \"\n\";
        echo '   Domain: ' . \$widget->domain . \"\n\";
        echo '   Active: ' . (\$widget->is_active ? 'Yes' : 'No') . \"\n\";
        echo '   Token: ' . substr(\$widget->api_token, 0, 10) . '...' . \"\n\";
    } else {
        echo '❌ No chatbot widgets found in database' . \"\n\";
        echo '   Create one with: php artisan tinker' . \"\n\";
    }
" 2>/dev/null

echo ""
echo "=========================================="
echo "✅ Verification complete!"
echo ""
echo "Next steps:"
echo "1. If migrations are not run: php artisan migrate"
echo "2. If no widgets exist: php artisan tinker"
echo "3. Run: \$widget = App\Models\ChatbotWidget::create(['name' => 'Website Chat', 'domain' => 'team.ryven.co', 'is_active' => true]); echo \$widget->api_token;"
