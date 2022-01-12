<?php 
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
//$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//Confirm_Login();
if(isset($_GET["id"])){
    $SearchQueryParameter=$_GET["id"];
    global $ConnectingDB;
    $sql="DELETE FROM admins WHERE id='$SearchQueryParameter'";
    $Execute=$ConnectingDB->query($sql);
    if($Execute){
        $_SESSION["SuccessMessage"]="Admin borrado";
        Redirect_to("Admins.php");
    }else{
        $_SESSION["ErrorMessage"]="Algo salió mal... Intenta de nuevo!";
        Redirect_to("Admins.php");
    }
}
