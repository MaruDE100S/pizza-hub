<?php
function checkAccess($page) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $accessRules = [
        'cart' => [login_required => true],
        'orders' => [login_required => true],
        'profile' => [login_required => true],

        'employee' => ['roles' => [employee, admin]],
        'admin' => ['roles' => [admin]], 
        'admin_pizzas' => ['roles' => [admin]], 
    ];

    if (!isset($accessRules[$page])) {
        return;
    }

    $rule = $accessRules[$page];

    if(isset($rule['login_required']) && $rule['login_required']) {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    if(isset($rule['roles'])) {
        if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $rule['roles'])) {
            header("Location: index.php?page=home");
            exit;
        }
    }
}