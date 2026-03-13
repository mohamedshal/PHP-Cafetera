<?php
    class UserController {
        private $user;

        public function __construct($db) {
            require_once __DIR__ . '/../models/User.php';
            $this->user = new User($db);
        }

        public function index() {
            $users = $this->user->getAll();
            require_once __DIR__ . '/../views/admin/users.php';
        }

        public function show($id) {
            $user = $this->user->getById($id);
            if (!$user) {
                echo "User not found!";
                return;
            }
        }

        public function create() {
            require_once __DIR__ . '/../views/admin/add_user.php';
        }

        public function handleDeleteRequest() {
            if (!isset($_SESSION['user_id']) || (($_SESSION['user_role'] ?? '') !== 'admin')) {
                header("Location: /login?error=Please login first");
                exit();
            }

            if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
                header("Location: /admin/users?error=Invalid request method");
                exit();
            }

            $userId = (int)($_POST['id'] ?? 0);
            if ($userId <= 0) {
                header("Location: /admin/users?error=Invalid user id");
                exit();
            }

            $result = $this->delete($userId);

            if ($result === 'deleted') {
                header("Location: /admin/users?success=User deleted successfully");
            } elseif ($result === 'in_use') {
                header("Location: /admin/users?error=Cannot delete user because they have related orders");
            } elseif ($result === 'self') {
                header("Location: /admin/users?error=You cannot delete your own account");
            } else {
                header("Location: /admin/users?error=Failed to delete user");
            }
            exit();
        }

        public function delete($id) {
            $user = $this->user->getById($id);
            if (!$user) {
                return 'not_found';
            }

            if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$id) {
                return 'self';
            }

            if ($this->user->hasOrders($id)) {
                return 'in_use';
            }

            return $this->user->delete($id) ? 'deleted' : 'error';
        }
    }
?>