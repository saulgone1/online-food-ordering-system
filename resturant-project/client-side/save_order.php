<?php
session_name('USERSESSID');
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Prepare order data
$order_id_str = uniqid('ORD');
$order_date = date('Y-m-d H:i:s');
$payment_method = $data['paymentMethod'] ?? '';
$name = $data['name'] ?? '';
$address = $data['address'] ?? '';
$phone = $data['mobile'] ?? '';
$email = $data['email'] ?? '';
$total = floatval($data['total'] ?? 0);
$status = 'Placed';

$user_id = $_SESSION['user_id']; // Use the logged-in user's ID

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, order_id, order_date, payment_method, name, address, phone, email, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssds", $user_id, $order_id_str, $order_date, $payment_method, $name, $address, $phone, $email, $total, $status);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Order insert failed: ' . $stmt->error]);
    exit;
}
$order_table_id = $stmt->insert_id; // This is the 'id' column in orders table

// Insert each item into order_items table
$cart = $data['cart'];
foreach ($cart as $item) {
    $item_title = $item['title'];
    $item_image = $item['img'];
    $quantity = intval($item['quantity']);
    $price = floatval($item['price']);
    $subtotal = $price * $quantity;

    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, item_title, item_image, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("issidd", $order_table_id, $item_title, $item_image, $quantity, $price, $subtotal);

    if (!$stmt2->execute()) {
        echo json_encode(['success' => false, 'message' => 'Order item insert failed: ' . $stmt2->error]);
        exit;
    }
}

echo json_encode(['success' => true]);
?>
