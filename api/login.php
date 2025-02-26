<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = $_POST['nisn'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nisn) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "NISN atau Password kosong"]);
        exit();
    }

    $query = "SELECT * FROM users WHERE nisn = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Query error"]);
        exit();
    }

    $stmt->bind_param("s", $nisn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(["status" => "success", "message" => "Login berhasil"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Password salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "NISN tidak ditemukan"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode request salah"]);
}
?>
