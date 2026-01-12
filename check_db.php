<?php
$db = mysqli_connect('localhost', 'root', '', 'ryven_team');

// Check if table exists
$result = mysqli_query($db, "SHOW TABLES LIKE 'chatbot_widgets'");
$tableExists = mysqli_num_rows($result) > 0;

echo "Table 'chatbot_widgets' exists: " . ($tableExists ? 'Yes' : 'No') . "\n";

if ($tableExists) {
    // Check columns
    $result = mysqli_query($db, "DESCRIBE chatbot_widgets");
    echo "\nTable structure:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    // Check record count
    $result = mysqli_query($db, "SELECT COUNT(*) as count FROM chatbot_widgets");
    $row = mysqli_fetch_assoc($result);
    echo "\nTotal records: " . $row['count'] . "\n";
}
?>
