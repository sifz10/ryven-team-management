<?php
$db = mysqli_connect('localhost', 'root', '', 'ryven_team');
$result = mysqli_query($db, "SELECT id, name, api_token, is_active FROM chatbot_widgets LIMIT 1");
$row = mysqli_fetch_assoc($result);
echo "ID: " . $row['id'] . "\n";
echo "Name: " . $row['name'] . "\n";
echo "Token: " . $row['api_token'] . "\n";
echo "Is Active: " . ($row['is_active'] ? 'Yes (1)' : 'No (0)') . "\n";
?>
