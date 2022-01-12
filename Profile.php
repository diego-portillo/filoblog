<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
//fetching data from sql database
$SearchQueryParameter = $_GET["username"];
global $ConnectingDB;
$sql = 'SELECT aname, aheadline, abio, aimage FROM admins WHERE username=:userName';
$stmt = $ConnectingDB->prepare($sql);
$stmt->bindValue(':userName', $SearchQueryParameter);
$stmt->execute();
$Result = $stmt->rowcount();
if ($Result == 1) {
    while ($DataRows = $stmt->fetch()) {
        $ExistingName = $DataRows["aname"];
        $ExistingBio = $DataRows["abio"];
        $ExistingImage = $DataRows["aimage"];
        $ExistingHeadline = $DataRows["aheadline"];
    }
} else {
    $_SESSION["ErrorMessage"] = "Bad Request!!!";
    Redirect_to("Blog.php?page=1");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Perfil de Admin</title>
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
                <ul class="navbar-nav mr-auto text-center" style="padding-left: 16rem; padding-right: 4rem;">

                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.html" class="nav-link">Sobre Nosotros</a>
                    </li>

                    <li class="nav-item">
                        <a href="contacto.html" class="nav-link">Contacto</a>

                </ul>
                <ul class="navbar-nav" style="padding-left: 8rem; padding-right: 1rem;">
                    <form class="navbar-nav mr-auto" action="Blog.php" method="GET">

                        <input class="form-control nav-item" type="text" name="Search" placeholder="...">

                        <button style="background:#dfd653;" class="btn btn-primary nav-item" name="SearchButton">Buscar</button>
                    </form>
                </ul>
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
                    <h1><i class="fas fa-user text-success mr-2" style="color:#dfd653;"></i><?php echo $ExistingName; ?></h1>
                    <h3><?php echo $ExistingHeadline; ?></h3>
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-md-3">
                <img src="images/<?php echo $ExistingImage; ?>" class="d-block img-fluid mb-3 rounded-circle">
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body" style="min-height: 300px;">
                        <p class="lead"><?php echo $ExistingBio; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--footer-->
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