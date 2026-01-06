<?php
require_once '../includes/config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'get_penduduk':
        getPenduduk();
        break;
    case 'get_penduduk_by_keluarga':
        getPendudukByKeluarga();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getPenduduk() {
    global $conn;
    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    $query = "SELECT p.*, k.no_kartu_keluarga, k.kepala_keluarga 
              FROM penduduk p 
              JOIN keluarga k ON p.id_keluarga = k.id_keluarga 
              ORDER BY p.tanggal_input DESC 
              LIMIT $limit OFFSET $offset";
    
    $result = $conn->query($query);
    $data = array();
    
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    // Get total count
    $result_count = $conn->query("SELECT COUNT(*) as total FROM penduduk");
    $total = $result_count->fetch_assoc()['total'];
    
    echo json_encode(['success' => true, 'data' => $data, 'total' => $total]);
}

function getPendudukByKeluarga() {
    global $conn;
    
    $id_keluarga = isset($_GET['id_keluarga']) ? (int)$_GET['id_keluarga'] : 0;
    
    if($id_keluarga == 0) {
        echo json_encode(['success' => false, 'message' => 'ID Keluarga tidak valid']);
        return;
    }
    
    $query = "SELECT * FROM penduduk WHERE id_keluarga = $id_keluarga ORDER BY hubungan_keluarga";
    
    $result = $conn->query($query);
    $data = array();
    
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $data]);
}
?>
