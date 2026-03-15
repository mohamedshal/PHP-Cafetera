<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container py-4">

        <h2 class="mb-3">Your Cart</h2>

        <div class="card">

            <div class="card-header">
                Cart Items
            </div>

            <div class="card-body">

                <?php if (!empty($cartItems)): ?>

                    <ul class="list-group mb-3">

                        <?php foreach ($cartItems as $item): ?>
                            <?php $product = $item['product']; ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($product['name']) ?></div>
                                    <div class="small text-muted">
                                        <?= number_format($product['price'], 2) ?> each
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">

                                    <a href="/cart/minus?id=<?= $product['id'] ?>"
                                        class="btn btn-sm btn-outline-secondary me-2">-</a>

                                    <span><?= $item['quantity'] ?></span>

                                    <a href="/cart/plus?id=<?= $product['id'] ?>"
                                        class="btn btn-sm btn-outline-secondary ms-2">+</a>

                                </div>

                                <div>
                                    <?= number_format($item['line_total'], 2) ?>
                                </div>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                    <div class="d-flex justify-content-between">

                        <strong>Total Price</strong>

                        <strong>
                            <?= number_format($totalPrice, 2) ?>
                        </strong>

                    </div>

                <?php else: ?>

                    <p class="text-muted">Cart is empty</p>

                <?php endif; ?>

            </div>

        </div>

        <a href="/" class="btn btn-primary mt-3">
            Back to Products
        </a>

    </div>

</body>

</html>