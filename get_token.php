<?php
$db = mysqli_connect('localhost', 'root', '', 'ryven_team');
$result = mysqli_query($db, "SELECT api_token, id, name FROM chatbot_widgets LIMIT 1");
$row = mysqli_fetch_assoc($result);
echo "Token: " . $row['api_token'] . "\n";
echo "ID: " . $row['id'] . "\n";
echo "Name: " . $row['name'] . "\n";
