<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login();
if (isset($_POST["Submit"])) {
    $Category = $_POST["CategoryTitle"];
    $Admin = $_SESSION["AdminName"];
    date_default_timezone_set("America/Asuncion");
    $CurrentTime = time(); //en segundos
    $DateTime = strftime("%B %m %d %H: %M %S", $CurrentTime);
    echo $DateTime;
    if (empty($Category)) {
        $_SESSION["ErrorMessage"] = "Todos los campos deben llenarse";
        Redirect_to("Categories.php");
    } elseif (strlen($Category) < 3) {
        $_SESSION["ErrorMessage"] = "El titulo debe tener mas de 2 caracteres";
        Redirect_to("Categories.php");
    } elseif (strlen($Category) > 49) {
        $_SESSION["ErrorMessage"] = "El titulo debe tener menos de 50 caracteres";
        Redirect_to("Categories.php");
    } else {
        //insercion de datos en sql
        global $ConnectingDB;
        $sql = "INSERT INTO category(title,author,datetime)";
        $sql .= "VALUES(:categoryName, :adminName, :dateTime)"; //primero pasamos los nombres falsos por seguridad
        $stmt = $ConnectingDB->prepare($sql); // es necesario para enviar los datos
        $stmt->bindValue(':categoryName', $Category); // con bindValue vinculamos la variable creada en php con la ubicacion en la tabla sql
        $stmt->bindValue(':adminName', $Admin);
        $stmt->bindValue(':dateTime', $DateTime);
        $Execute = $stmt->execute();
        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Categoria agregada!";
            Redirect_to("Categories.php");
        } else {
            $_SESSION["ErrorMessage"] = "Algo salió mal... Intenta de nuevo";
            Redirect_to("Categories.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Categorias</title>
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
                    <h1><i class="fas fa-edit" style="color:#dfd653;"></i> Administrar Categorias</h1>
                </div>
            </div>
        </div>
    </header>
    <!--END OF HEADER-->
    <!--Main area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10" style="min-height: 400px">
                <?php echo ErrorMessage();
                echo SuccessMessage(); ?>
                <form action="Categories.php" method="post">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Agregar Nueva Categoria</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="FieldInfo">Titulo: </span></label>
                                <input class="form-control mb-2" type="text" name="CategoryTitle" id="title" placeholder="Escriba un título..." value="">
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
                <h2 class="card-header">Categorias Existentes</h2>
                <table class="table table-striped table-hover" style="background-color: white;">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Creador</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM category ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CategoryId = $DataRows["id"];
                        $CategoryDate = $DataRows["datetime"];
                        $CategoryName = $DataRows["title"];
                        $CreatorName = $DataRows["author"];
                        $SrNo++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($CategoryDate); ?></td>
                                <td><?php echo htmlentities($CategoryName); ?></td>
                                <td><?php echo htmlentities($CreatorName); ?></td>
                                <td><a href="DeleteCategory.php?id=<?php echo $CategoryId; ?>" class="btn btn-danger">Borrar</a> </td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>

        </div>
    </section>
    <!--end of main area-->
    <!--footer-->
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