<?php
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$widget = \App\Models\ChatbotWidget::first();
echo "Token: " . $widget->api_token . "\n";
echo "ID: " . $widget->id . "\n";
echo "Name: " . $widget->name . "\n";
