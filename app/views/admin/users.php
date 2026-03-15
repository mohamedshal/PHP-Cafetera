<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/User.php";
require_once __DIR__ . "/../../models/Room.php";

// Check admin access
require_once __DIR__ . "/auth_check.php";

// Initialize database connection
$database = new Database();
$db = $database->connect();

// Fetch all users
$userModel = new User($db);
$users = $userModel->getAll();

$success = $_GET["success"] ?? null;
$error = $_GET["error"] ?? null;

// Fetch all rooms for reference
$roomModel = new Room($db);
$rooms = $roomModel->getAll();

// Create a room lookup array (id => name)
$roomLookup = [];
foreach ($rooms as $room) {
    $roomLookup[$room["id"]] = $room["name"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users Management</title>

<?php include __DIR__ . "/../layouts/jsCDN.php"; ?>
    <style>
        .page-title {
            font-weight: 700;
            color:#4E342E;
        }

        .user-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }

        .card {
            border: 1px solid #4E342E;
            border-radius:14px;
            overflow:hidden;
        }

        .card-header{
            background:#4E342E;
            color:#fff;
        }

        .table thead {
            background: #4E342E;
            color: white;
        }

        .table th{
            background:#4E342E;
            color:#fff;
            font-weight:600;
            border:none;
        }

        .btn-action{
            border-radius:20px;
            padding:4px 12px;
            font-size:0.85rem;
            transition:all .25s ease;
            border-color:#4E342E;
            background:#4E342E;
            color:#fff;
        }

        .btn-action:hover{
            background:#6f4e37;
            border-color:#6f4e37;
            color:#fff;
        }

         .btn-delete{
            border-radius:20px;
            padding:4px 12px;
            font-size:0.85rem;
            transition:all .25s ease;
            border-color: #dc3545;
            background:#dc3545;
            color:#fff;
            text-decoration: none;
         }
        .btn-delete:hover{
            color:#dc3545;
            background:#fff;
            border-color:#dc3545;
            text-decoration: none;
        }

        .badge{
            padding:6px 12px;
            border-radius:20px;
            font-size:0.8rem;
        }

        .alert{
            border-radius:12px;
        }
    </style>

</head>

<body>

    <?php include __DIR__ . "/../layouts/navbar.php"; ?>
    <div class="container py-5">

<div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="page-title mb-0">
                    Users Management
                </h4>

                <a href="/admin/add-user" class="btn btn-action">
                    + Add User
                </a>

            </div>

            <div class="card-body">

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($users)): ?>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Room</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($users as $user): ?>

                                    <tr>

                                        <td><?= $user["id"] ?></td>

                                        <td>

                                            <div class="d-flex align-items-center gap-2">

                                                <?php
                                                $userName = (string)($user["name"] ?? "Unknown");
                                                $userImage = trim((string)($user["image"] ?? ""));
                                                if ($userImage !== '' && filter_var($userImage, FILTER_VALIDATE_URL)) {
                                                    $userImageSrc = $userImage;
                                                } elseif ($userImage !== '') {
                                                    $userImageSrc = "/uploads/" . rawurlencode($userImage);
                                                } else {
                                                    $userImageSrc = "https://ui-avatars.com/api/?name=" . rawurlencode($userName);
                                                }
                                                ?>

                                                <img src="<?= htmlspecialchars($userImageSrc) ?>" class="user-img" alt="<?= htmlspecialchars($userName) ?>">

                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars(
                                                        $user["name"],
                                                    ) ?>
                                                </span>

                                            </div>

                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $user["email"],
                                            ) ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                Room <?= isset(
                                                    $roomLookup[
                                                        $user["room_id"]
                                                    ],
                                                )
                                                    ? $roomLookup[
                                                        $user["room_id"]
                                                    ]
                                                    : $user["room_id"] ?>
                                            </span>
                                        </td>

                                        <td>

                                            <?php if (
                                                $user["role"] == "admin"
                                            ): ?>

                                                <span class="badge bg-danger">
                                                    Admin
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-success">
                                                    User
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <?= date(
                                                "d M Y",
                                                strtotime($user["created_at"]),
                                            ) ?>
                                        </td>

                                        <td class="text-center">

                                            <form action="/admin/users/delete" method="POST"
                                                style="display:inline">

                                                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">

                                                <button class="btn-delete">
                                                    Delete
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <div class="text-center py-5">

                        <h5 class="text-muted">
                            No users found
                        </h5>

                        <a href="/admin/add-user" class="btn btn-primary mt-3">
                            Create First User
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>
</body>

</html>
