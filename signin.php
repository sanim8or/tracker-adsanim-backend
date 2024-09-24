<?php
require_once('../access.php');
require_once('../jwtTokenAuth.php');
include_once('./connection.php');
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $data = json_decode(file_get_contents('php://input'),true);
    $email = $data["email"];
    $password = $data["password"];
    if(!$email || !$password){
        echo json_encode(array("error" =>"please enter valid email and password"));
        exit;
    }
    $sql = "SELECT * FROM users WHERE email='$email'";
    $conn = DatabaseConnection::getConnection();
    $res =mysqli_query($conn,$sql);
    if(!$res){
        echo json_encode(array("error" =>"Unable to sign in due to server error"));
        $conn->close();
        exit;
    }
    if(mysqli_num_rows($res)<1){
        echo json_encode(array("error" =>"please enter valid email and password"));
        $conn->close();
        exit;
    }
    else{
        $row = mysqli_fetch_assoc($res);
        $match = password_verify($password,$row["password"]);
        if($match){
            $obj =(object)[
                "userID"=>$row["user_id"],
                "userName"=>$row["username"],
            ];
            $token = Token::Sign([$obj]);
            echo json_encode(array("message" =>"loged in successfully","data"=>$obj,"token"=>$token));

        }else{
            echo json_encode(array("error" =>"please enter valid password"));
        }
    }
    $conn->close();
}
?>