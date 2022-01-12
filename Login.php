<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
if (isset($_SESSION["User_ID"])) {
    Redirect_to("Dashboard.php");
}
if (isset($_POST["Submit"])) {
    $UserName = $_POST["Username"];
    $Password = $_POST["Password"];
    if (empty($UserName) || empty($Password)) {
        $_SESSION["ErrorMessage"] = "Todos los campos deben llenarse";
        Redirect_to("Login.php");
    } else {
        $Found_Account = Login_Attempt($UserName, $Password);
        if ($Found_Account) {
            $_SESSION["User_ID"] = $Found_Account["id"]; //con esta linea creamos la variable User_ID dentro del array SESSION
            $_SESSION["Username"] = $Found_Account["username"]; //Found_Account es una variable que contiene el array recibido como respuesta de la base de datos sql
            $_SESSION["AdminName"] = $Found_Account["aname"];
            $_SESSION["SuccessMessage"] = "Welcome " . $_SESSION["AdminName"];
            if (isset($_SESSION["TrackingURL"])) {
                Redirect_to($_SESSION["TrackingURL"]);
            } else {
                Redirect_to("Dashboard.php");
            }
        } else {
            $_SESSION["ErrorMessage"] = "Usuario o Password incorrecto";
            Redirect_to("Login.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Filoblog Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="Images/iconoFilo.png">
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f622bf9c0b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
    <!--NAVBAR START-->
    <div style="height:10px; background:#dfd653;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Filoblog.com</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS" aria-controls="navbarcollapseCMS" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarcollapseCMS">
            </div>
        </div>
    </nav>
    <div style="height:10px; background:#dfd653;"></div>
    <!--NAVBAR END-->
    <!--HEADER-->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <!--Main area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-sm-3 col-sm-6 style=" min-height:500px;">
                <br><br><br>
                <?php echo ErrorMessage();
                echo SuccessMessage(); ?>
                <div class="card bg-secondary text-light">
                    <div class="card-header">
                        <h4>Bienvenido!</h4>
                    </div>
                    <div class="card-body bg-dark">

                        <form class="" action="Login.php" method="post">
                            <div class="form-group">
                                <label for="username"><span class="FieldInfo">Usuario:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="Username" id="username" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password"><span class="FieldInfo">Password:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="Password" id="password" value="">
                                </div>
                            </div>
                            <input type="submit" name="Submit" class="btn btn-info btn-block" value="Login">
                        </form>
                    </div>
                </div>
                <div style="color: red; text-align: center;">Para probar una demostracion de la vista de administrador<br> pueden comunicarse en la seccion de <a href="contacto.html">Contacto</a></div>

            </div>
        </div>
    </section>
    <!--End of Main area-->
    <!--footer-->
    <div style=" position: absolute; bottom: 0px; left:0;right:0;">
        <div style="height:10px; background:#dfd653;"></div>
        <footer class="bg-dark text-white" style="padding-top: 1rem">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <p class="lead text-center">Portafolio Demo de Diego Portillo <span id="year"></span> &copy; Todos los derechos reservados</p>

                    </div>
                </div>
            </div>
        </footer>
        <div style="height:10px; background:#dfd653;"></div>
    </div>
    <script src="jquery.js"></script>
    <script>
        $('#year').text(new Date().getFullYear());
    </script>

</html>