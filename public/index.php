<?php

// Start session
session_start();

// Delegate all routing to web.php
require_once __DIR__ . "/../routes/web.php";
