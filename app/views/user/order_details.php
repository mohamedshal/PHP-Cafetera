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
</head>

<body>
    <?php include __DIR__ . "/../layouts/navbar.php"; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Order #<?= (int)$order['id'] ?></h2>
            <a href="/my-orders" class="btn btn-outline-secondary">Back to Orders</a>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card mb-4">
                    <div class="card-header">
                        Order Items
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
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
                                                    <img src="assets/images/<?= htmlspecialchars($item['product_image']) ?>" 
                                                         alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                         style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                                <?php endif; ?>
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </div>
                                        </td>
                                        <td><?= number_format((float)$item['price'], 2) ?></td>
                                        <td><?= (int)$item['quantity'] ?></td>
                                        <td><?= number_format((float)($item['price'] * $item['quantity']), 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (!empty($order['notes'])): ?>
                    <div class="card">
                        <div class="card-header">
                            Notes
                        </div>
                        <div class="card-body">
                            <?= nl2br(htmlspecialchars($order['notes'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
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
                                <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
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
                            <strong><?= number_format((float)$order['total_price'], 2) ?></strong>
                        </div>

                        <?php if ($order['status'] === 'processing'): ?>
                            <hr>
                            <a href="/order/cancel?id=<?= (int)$order['id'] ?>" 
                               class="btn btn-outline-danger w-100"
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
