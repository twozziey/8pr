<?php
session_start();
if(!isset($_SESSION["code"]) || !isset($_SESSION['preuser'])) {
    echo "error";
    exit;
}
if(!isset($_POST["code"])) {
    echo "error";
    exit;
}
if($_SESSION["code"] == $_POST["code"]) {
    $_SESSION['user'] = $_SESSION['preuser'];
    unset($_SESSION['code']);
    unset($_SESSION['preuser']);
    echo "success";
} else {
    unset($_SESSION['code']);
    unset($_SESSION['preuser']);
    echo "error";
}
?>