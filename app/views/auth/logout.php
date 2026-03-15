<?php
session_start();
session_unset();
session_destroy();
header("Location: /login?success=Logged out successfully");
exit();
