<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $pizza = getPizzaById($id);
} else {
    echo 'Pizza unfound';
}
?>