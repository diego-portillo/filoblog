<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login();
//fetching admin data
$AdminId = $_SESSION["User_ID"];
global $ConnectingDB;
$sql = "SELECT * FROM admins WHERE id='$AdminId'";
$stmt = $ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()) {
    $ExistingUsername = $DataRows['username'];
    $ExistingName = $DataRows['aname'];
    $ExistingHeadline = $DataRows['aheadline'];
    $ExistingBio = $DataRows['abio'];
    $ExistingImage = $DataRows['aimage'];
}
if (isset($_POST["Submit"])) { //los nombres del array post son tomados de los html input tags names
    $AName = $_POST["Name"];
    $AHeadline = $_POST["Headline"];
    $ABio = $_POST["Bio"];
    $AImage = $_FILES["Image"]["name"]; //para imagenes u otros tipos de datos usamos Files en lugar de post
    //lo que se guarda en files no es el archivo si no el nombre y ruta para ser ubicado
    $Target = "Images/" . basename($_FILES["Image"]["name"]);
    if (strlen($AHeadline) > 30) {
        $_SESSION["ErrorMessage"] = "El titulo debe tener menos de 30 caracteres";
        Redirect_to("MyProfile.php");
    } elseif (strlen($ABio) > 500) {
        $_SESSION["ErrorMessage"] = "La biografia debe tener menos de 500 caracteres";
        Redirect_to("MyProfile.php");
    } else {
        global $ConnectingDB;
        //condicion para arreglar el bug de eliminar la imagen al modificar otro campo
        if (!empty($_FILES["Image"]["name"])) {
            $sql = "UPDATE admins SET aname='$AName', aheadline='$AHeadline', abio='$ABio', aimage='$AImage' WHERE id='$AdminId'";
        } else {
            $sql = "UPDATE admins SET aname='$AName', aheadline='$AHeadline', abio='$ABio' WHERE id='$AdminId'";
        }
        $Execute = $ConnectingDB->query($sql);
        move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);
        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Información actualizada";
            Redirect_to("MyProfile.php");
        } else {
            $_SESSION["ErrorMessage"] = "Algo salió mal... Intenta de nuevo!";
            Redirect_to("MyProfile.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Mi Perfil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="Images/iconoFilo.png">
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f622bf9c0b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/style.css" type="text/css">

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
                <ul class="navbar-nav mr-auto" style="padding-left: 7rem">
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link"><i class="fas fa-user" style="color: greenyellow"></i>Mi Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Resumen</a>
                    </li>
                    <li class="nav-item">
                        <a href="Posts.php" class="nav-link">Posteos</a>
                    </li>
                    <li class="nav-item">
                        <a href="Categories.php" class="nav-link">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a href="Admins.php" class="nav-link">Gestionar Admins</a>
                    </li>
                    <li class="nav-item">
                        <a href="Comments.php" class="nav-link">Comentarios</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav" style=" padding-left: 9rem">
                    <li class="nav-item"><a href="Logout.php" class="nav-link"><i class="fas fa-user-times" style="color: red"></i>Cerrar Sesion</a></li>
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
                    <h1><i class="fas fa-user text-success mr-2" style="color:#27aae1;"></i>@<?php echo $ExistingUsername; ?></h1>
                    <small><?php echo $ExistingHeadline; ?></small>
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <!--Main area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <!--left area-->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h3><?php echo $ExistingName ?></h3>
                    </div>
                    <div class="card-body">
                        <img src="Images/<?php echo $ExistingImage; ?>" class="block img-fluid mb-3">
                        <div>
                            <?php echo $ExistingBio; ?>
                            <!--Random text to fill all the gaps in this paragraph. This really has no sense, the same as the israel stablishment trying to claim palestine as his own territory. Niether Mario Abdo makes sense with his disastrous goverment.-->
                        </div>
                    </div>
                </div>
            </div>
            <!--right area-->
            <div class="col-lg-9" style="min-height: 400px">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <form action="MyProfile.php" method="post" enctype="multipart/form-data">
                    <!--El enctype multiform se utiliza para especificar que
                ademas de texto agregamos otros tipos de datos como imagenespor ejemplo-->
                    <div class="card bg-dark text-light">
                        <div class="card-header">
                            <h4>Editar Perfil</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-6">
                                <input class="form-control" type="text" name="Name" id="title" placeholder="Tu nombre" value="">
                            </div>
                            <div class="form-group mb-6">
                                <input class="form-control" type="text" id="title" name="Headline" placeholder="Titular" value="">
                                <small class="text-muted">Agregue un titular como Dr, Ing, etc.</small>
                                <span class="text-danger mb-6">No mas de 30 caracteres</span>
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Bio" class="form-control" id="Post" name="Bio" rows="8" cols="80"></textarea>
                            </div>

                            <div class="form-group mb-1">
                                <label for="imageSelect"><span class="FieldInfo">Seleccionar Imagen </span></label>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="File" name="Image" id="imageSelect" value="">
                                    <label for="imageSelect" class="custom-file-label"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i>Volver a Resumen</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button type="submit" name="Submit" class="btn btn-success btn-block"><i class="fas fa-check"></i>Publicar</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>

        </div>
    </section>
    <!--end of main area-->
    <!--footer-->

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

    <script src="jquery.js"></script>
    <script>
        $('#year').text(new Date().getFullYear());
    </script>

</html>