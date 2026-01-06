<?php
require_once 'includes/config.php';

// Check tables
$result = $conn->query("SHOW TABLES");
echo "=== TABLES IN DATABASE ===\n";
while($row = $result->fetch_row()) {
    echo "- " . $row[0] . "\n";
}

// Check if kecamatans exists and its structure
echo "\n=== CHECK KECAMATANS TABLE ===\n";
$check = $conn->query("SHOW TABLES LIKE 'kecamatans'");
if($check->num_rows == 0) {
    echo "Table 'kecamatans' DOES NOT EXIST!\n";
} else {
    echo "Table 'kecamatans' EXISTS!\n";
    $fields = $conn->query("DESCRIBE kecamatans");
    while($row = $fields->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
}

// Check kecamatan field in keluarga table
echo "\n=== CHECK KELUARGA TABLE STRUCTURE ===\n";
$fields = $conn->query("DESCRIBE keluarga");
echo "Columns with 'kecamatan':\n";
while($row = $fields->fetch_assoc()) {
    if(strpos(strtolower($row['Field']), 'kecamatan') !== false) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
}

?>
