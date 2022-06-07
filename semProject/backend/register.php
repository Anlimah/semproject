<?php
session_start();

require_once('../classes/admin_handler.php');
$admin = new AdminHandler();

if (isset($_POST["full_name"]) && !empty($_POST["full_name"])) {
    if (isset($_POST["account_number"]) && !empty($_POST["account_number"])) {
        if (isset($_POST["momo_number"]) && !empty($_POST["momo_number"])) {
            if (isset($_POST["password"]) && !empty($_POST["password"])) {
                if (isset($_POST["gender"]) && !empty($_POST["gender"])) {
                    if (isset($_POST["email_address"]) && !empty($_POST["email_address"])) {
                        if (isset($_POST["user_password"]) && !empty($_POST["user_password"])) {
                            if (isset($_POST["password"]) && !empty($_POST["password"])) {
                                $result = $admin->checkUser($_POST["email"], $_POST["password"]);
                                if ($result) {
                                    echo json_encode(array("response" => "error", "msg" => "This email is already registered! Please login to continue"));
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
                    } else {
                        echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
                    }
                } else {
                    echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
                }
            } else {
                echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
            }
        } else {
            echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
        }
    } else {
        echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
    }
} else {
    echo json_encode(array("response" => "error", "msg" => "Invalid input!"));
}
