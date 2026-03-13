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

$orders = $orderController->getByUserId($userId);

$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders - Cafeteria</title>
    <?php include_once __DIR__ . "/../layouts/jsCDN.php"; ?>
</head>

<body>
    <?php include __DIR__ . "/../layouts/navbar.php"; ?>

    <div class="container py-4">
        <h2 class="mb-4">My Orders</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($orders)): ?>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Room</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= (int)$order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['room_name'] ?? 'N/A') ?></td>
                                    <td><?= number_format((float)$order['total_price'], 2) ?></td>
                                    <td>
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
                                    </td>
                                    <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <a href="/order/details?id=<?= (int)$order['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                        <?php if ($order['status'] === 'processing'): ?>
                                            <a href="/order/cancel?id=<?= (int)$order['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to cancel this order?')">
                                                Cancel
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                You haven't placed any orders yet. <a href="/">Browse products</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>
