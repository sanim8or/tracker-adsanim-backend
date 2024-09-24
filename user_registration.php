<?php
require_once('../access.php');
include_once('./connection.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];
    $username = $data['username'];
    $id = $data["user_id"];

    if ($username && $email && $password) {
        // checking for existing email
        $conn = DatabaseConnection::getConnection();
        $sql = "SELECT * FROM users WHERE email='$email'";
        $res = mysqli_query($conn, $sql);
        if (mysqli_num_rows($res) > 0) {
            echo json_encode(array('error' => 'This email has already registered'));
            exit();
        }
        // add to database
        $hasPass = password_hash("$password", PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`user_id`,`username`,`email`,`password`) VALUES ('$id','$username','$email','$hasPass')";
        $result = mysqli_query($conn, $sql);
        // echo var_dump($result);
        if ($result) {
            echo json_encode(array('message' => 'registered successfully'));
        } else {
            echo json_encode(array('error' => 'can not register due to server error'));
        }
        $conn->close();
    } else {
        echo json_encode(array('error' => 'Plesae fill all details'));
    }
}
