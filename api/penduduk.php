<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_penduduk':
        getPenduduk();
        break;
    case 'get_penduduk_by_keluarga':
        getPendudukByKeluarga();
        break;
    case 'search_penduduk':
        searchPenduduk();
        break;
    case 'update_penduduk':
        updatePenduduk();
        break;
    case 'delete_penduduk':
        deletePenduduk();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getPenduduk()
{
    global $conn;

    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    $query = "SELECT p.*, k.no_kartu_keluarga, k.kepala_keluarga 
              FROM penduduk p 
              JOIN keluarga k ON p.id_keluarga = k.id_keluarga 
              ORDER BY p.tanggal_input DESC 
              LIMIT $limit OFFSET $offset";

    $result = $conn->query($query);
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Get total count
    $result_count = $conn->query("SELECT COUNT(*) as total FROM penduduk");
    $total = $result_count->fetch_assoc()['total'];

    echo json_encode(['success' => true, 'data' => $data, 'total' => $total]);
}

function getPendudukByKeluarga()
{
    global $conn;

    $id_keluarga = isset($_GET['id_keluarga']) ? (int) $_GET['id_keluarga'] : 0;

    if ($id_keluarga == 0) {
        echo json_encode(['success' => false, 'message' => 'ID Keluarga tidak valid']);
        return;
    }

    $query = "SELECT * FROM penduduk WHERE id_keluarga = $id_keluarga ORDER BY hubungan_keluarga";

    $result = $conn->query($query);
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $data]);
}

function searchPenduduk()
{
    global $conn;

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 100;

    if ($search === '') {
        echo json_encode(['success' => true, 'data' => []]);
        return;
    }

    // Gunakan prepared statement untuk keamanan
    $like = "%" . $search . "%";
    $sql = "SELECT p.*, k.no_kartu_keluarga, k.kepala_keluarga
            FROM penduduk p
            JOIN keluarga k ON p.id_keluarga = k.id_keluarga
            WHERE p.nama_lengkap LIKE ? OR p.nik LIKE ?
            ORDER BY p.tanggal_input DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Query prepare failed']);
        return;
    }

    // Bind params: ss i (two strings for LIKE, one integer for limit)
    $stmt->bind_param('ssi', $like, $like, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'data' => $data]);
}

function updatePenduduk()
{
    global $conn;

    $id_penduduk = isset($_POST['id_penduduk']) ? (int) $_POST['id_penduduk'] : 0;
    $nik = isset($_POST['nik']) ? trim($_POST['nik']) : '';
    $nama = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $jk = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $agama = isset($_POST['agama']) ? $_POST['agama'] : '';
    $pekerjaan = isset($_POST['pekerjaan']) ? trim($_POST['pekerjaan']) : '';
    $status = isset($_POST['status_perkawinan']) ? $_POST['status_perkawinan'] : '';

    if ($id_penduduk == 0 || empty($nik) || empty($nama)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        return;
    }

    $stmt = $conn->prepare("UPDATE penduduk SET nik=?, nama_lengkap=?, jenis_kelamin=?, agama=?, pekerjaan=?, status_perkawinan=? WHERE id_penduduk=?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed']);
        return;
    }

    $stmt->bind_param("ssssssi", $nik, $nama, $jk, $agama, $pekerjaan, $status, $id_penduduk);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update gagal: ' . $stmt->error]);
    }
    $stmt->close();
}

function deletePenduduk()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id_penduduk']) ? (int) $input['id_penduduk'] : 0;

    if ($id == 0) {
        // Try GET if JSON body is empty
        $id = isset($_GET['id_penduduk']) ? (int) $_GET['id_penduduk'] : 0;
    }

    if ($id == 0) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM penduduk WHERE id_penduduk = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Hapus gagal: ' . $stmt->error]);
    }
    $stmt->close();
}
?>