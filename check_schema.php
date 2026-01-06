<?php
require_once 'includes/config.php';

echo "=== TABLE STRUCTURES ===\n\n";

// Check keluarga structure
echo "KELUARGA TABLE:\n";
$fields = $conn->query("DESCRIBE keluarga");
while($row = $fields->fetch_assoc()) {
    echo "  - " . str_pad($row['Field'], 25) . ": " . $row['Type'] . "\n";
}

echo "\nPENDUDUK TABLE:\n";
$fields = $conn->query("DESCRIBE penduduk");
while($row = $fields->fetch_assoc()) {
    echo "  - " . str_pad($row['Field'], 25) . ": " . $row['Type'] . "\n";
}

echo "\nVERIFIKASI TABLE:\n";
$fields = $conn->query("DESCRIBE verifikasi");
while($row = $fields->fetch_assoc()) {
    echo "  - " . str_pad($row['Field'], 25) . ": " . $row['Type'] . "\n";
}

echo "\n=== SAMPLE DATA ===\n";

echo "\nKELUARGA (first 3):\n";
$result = $conn->query("SELECT * FROM keluarga LIMIT 3");
$cols = [];
if($result->num_rows > 0) {
    $first = $result->fetch_assoc();
    $cols = array_keys($first);
    echo "Columns: " . implode(", ", $cols) . "\n";
    foreach($cols as $col) {
        echo "  " . $col . ": " . $first[$col] . "\n";
    }
}

?>
