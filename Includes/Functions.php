<?php
require_once("Includes/DB.php");
function Redirect_to($New_Location){
    header("Location:".$New_Location);
    exit;
}
function CheckUserNameExistence($UserName){
    global $ConnectingDB;
    $sql="SELECT username FROM admins WHERE username=:userName";
    $stmt=$ConnectingDB->prepare($sql);
    $stmt->bindValue(':userName', $UserName);
    $stmt->execute();
    $Result=$stmt->rowcount();
    if($Result==1){
        return true;
    }else{
        return false;
    }
}
function Login_Attempt($UserName, $Password){
    global $ConnectingDB;
        $sql="SELECT * FROM admins WHERE username=:userName AND password=:passWord LIMIT 1";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':userName', $UserName);
        $stmt->bindValue(':passWord', $Password);
        $stmt->execute();
        $Result = $stmt->rowcount();
        if($Result==1){
            return $Found_Account=$stmt->fetch();
        }else{
            return null;
        }
}
function Confirm_Login(){
    if(isset($_SESSION["User_ID"])){
        return true;
    }else{
        $_SESSION["ErrorMessage"]="Login Requerido!";
        Redirect_to("Login.php");
    }
}
function countTableRows($TableName){
global $ConnectingDB;
$sql="SELECT COUNT(*) FROM {$TableName}";
                    $stmt=$ConnectingDB->query($sql);
                    $TotalRows=$stmt->fetch();
                    $TotalPosts= array_shift($TotalRows);//esta conversion es necesaria porque fetch devuelve un array entero en lugar del resultado de la cuenta
                    echo $TotalPosts;
}
function countRenderPosts($PostId, $PostStatus){
      global $ConnectingDB;
      $sqlAprroved="SELECT COUNT(*) FROM comments WHERE post_id='$PostId' AND status='$PostStatus'";
      $stmtApproved=$ConnectingDB->query($sqlAprroved);
      $RowsTotal = $stmtApproved->fetch();
      $Total= array_shift($RowsTotal);
                        return $Total;
}
