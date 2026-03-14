<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../controllers/OrderController.php";

$database = new Database();
$db = $database->connect();

$orderController = new OrderController($db);
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: /login");
    exit();
}

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    header("Location: /my-orders?error=Order not found");
    exit();
}

$order = $orderController->getById($orderId);

if (!$order || $order['user_id'] != $userId) {
    header("Location: /my-orders?error=Order not found");
    exit();
}

$items = $orderController->getItems($orderId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order Details - Cafeteria</title>

<?php include_once __DIR__ . "/../layouts/jsCDN.php"; ?>
<link rel="stylesheet" href="assets/css/myOrder.css">

<style>
.page-title{
    font-weight:700;
    color:#4E342E;
}

.orders-table thead{
    background:#4E342E;
}

.orders-table th{
    background:#4E342E;
    color:#fff;
    font-weight:600;
    border:none;
}

.orders-table td{
    vertical-align:middle;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:0.8rem;
}

.btn-action{
    border-radius:20px;
    padding:6px 14px;
    font-size:0.9rem;
    transition:all .25s ease;
    border:1px solid #4E342E;
    background:#4E342E;
    color:#fff;
}

.btn-action:hover{
    background:#6f4e37;
    border-color:#6f4e37;
    color:#fff;
    transform:translateY(-1px);
}

.cancel{
    border-radius:20px;
    transition:all .25s ease;
}

.cancel:hover{
    background:#ffffff !important;
    color:#dc3545 !important;
    border-color:#dc3545 !important;
}

.summary-card,
.order-items-card{
    border:1px solid #4E342E;
    border-radius:14px;
    overflow:hidden;
}

.summary-header,
.order-items-header{
    background:#4E342E;
    color:#fff;
    font-weight:600;
}
</style>

</head>

<body>

<?php include __DIR__ . "/../layouts/navbar.php"; ?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="page-title">Order #<?= (int)$order['id'] ?></h2>

<a href="/my-orders" class="btn btn-action">
← Back
</a>

</div>

<div class="row">

<!-- LEFT -->
<div class="col-lg-8 mb-4">

<div class="card order-items-card mb-4">

<div class="card-header order-items-header">
Order Items
</div>

<div class="card-body p-0">

<table class="table orders-table mb-0">

<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
</tr>
</thead>

<tbody>

<?php foreach ($items as $item): ?>

<tr>

<td>
<div class="d-flex align-items-center">

<?php if (!empty($item['product_image'])): ?>

<?php
$imageFile = (string) $item['product_image'];

$productImageFs = __DIR__ . '/../../../public/assets/images/products/' . $imageFile;
$legacyImageFs = __DIR__ . '/../../../public/assets/images/' . $imageFile;

if (file_exists($productImageFs)) {
$imageSrc = '/assets/images/products/' . rawurlencode($imageFile);
}
elseif (file_exists($legacyImageFs)) {
$imageSrc = '/assets/images/' . rawurlencode($imageFile);
}
else {
$imageSrc = '';
}
?>

<?php if ($imageSrc !== ''): ?>

<img
src="<?= htmlspecialchars($imageSrc) ?>"
alt="<?= htmlspecialchars($item['product_name']) ?>"
style="width:50px;height:50px;object-fit:cover;"
class="me-2">

<?php endif; ?>

<?php endif; ?>

<?= htmlspecialchars($item['product_name']) ?>

</div>
</td>

<td>
<?= number_format((float)$item['price'],2) ?> $
</td>

<td>
<?= (int)$item['quantity'] ?>
</td>

<td>
<?= number_format((float)($item['price'] * $item['quantity']),2) ?> $
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<?php if (!empty($order['notes'])): ?>

<div class="card order-items-card">

<div class="card-header order-items-header">
Notes
</div>

<div class="card-body">
<?= nl2br(htmlspecialchars($order['notes'])) ?>
</div>

</div>

<?php endif; ?>

</div>


<!-- RIGHT -->
<div class="col-lg-4">

<div class="card summary-card">

<div class="card-header summary-header">
Order Summary
</div>

<div class="card-body">

<div class="mb-3">
<strong>Status:</strong>

<?php
$statusClass = match($order['status']) {
'processing' => 'warning',
'out_for_delivery' => 'info',
'done' => 'success',
'cancelled' => 'danger',
default => 'secondary'
};
?>

<span class="badge bg-<?= $statusClass ?>">
<?= ucfirst(str_replace('_',' ',$order['status'])) ?>
</span>

</div>

<div class="mb-3">
<strong>Room:</strong>
<?= htmlspecialchars($order['room_name'] ?? 'N/A') ?>
</div>

<div class="mb-3">
<strong>Date:</strong>
<?= date('M d, Y H:i', strtotime($order['created_at'])) ?>
</div>

<hr>

<div class="d-flex justify-content-between">
<strong>Total:</strong>
<strong><?= number_format((float)$order['total_price'],2) ?> $</strong>
</div>

<?php if ($order['status'] === 'processing'): ?>

<hr>

<a
href="/order/cancel?id=<?= (int)$order['id'] ?>"
class="btn btn-danger cancel w-100"
onclick="return confirm('Are you sure you want to cancel this order?')">

Cancel Order

</a>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.js"></script>

</body>
</html>