<?php
$db = mysqli_connect('localhost', 'root', '', 'ryven_team');

// Delete existing widget
mysqli_query($db, "DELETE FROM chatbot_widgets");

// Create new widget
$name = 'Test Chat Widget';
$domain = 'localhost';
$token = 'cbw_' . bin2hex(random_bytes(30));
$welcome_message = 'Hello! How can we help you today?';

$query = "INSERT INTO chatbot_widgets (name, domain, api_token, welcome_message, is_active, created_at, updated_at) 
          VALUES ('$name', '$domain', '$token', '$welcome_message', 1, NOW(), NOW())";

if (mysqli_query($db, $query)) {
    echo "Widget created successfully!\n";
    echo "Token: $token\n";
    
    // Verify
    $result = mysqli_query($db, "SELECT * FROM chatbot_widgets LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    echo "ID: " . $row['id'] . "\n";
    echo "Name: " . $row['name'] . "\n";
    echo "Is Active: " . $row['is_active'] . "\n";
} else {
    echo "Error: " . mysqli_error($db) . "\n";
}
?>
