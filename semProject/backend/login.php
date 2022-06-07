<?php
session_start();

require_once('../classes/admin_handler.php');
$admin = new AdminHandler();

if (isset($_POST["email"]) && !empty($_POST["email"])) {
    if (isset($_POST["password"]) && !empty($_POST["password"])) {
        $result = $admin->checkUser($_POST["email"], $_POST["password"]);
        if ($result == 0) {
            echo json_encode(array("response" => "error", "msg" => "You're not registered! Please register first."));
        } else {
            if ($result[0]["id"] == 1) {
                $_SESSION["user"] = $result[0]["id"];
                $_SESSION["login"] = true;
            }
            echo json_encode(array("response" => "success", "msg" => "Login successful!"));
        }
    } else {
        echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
    }
} else {
    echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
}
