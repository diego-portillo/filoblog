<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login();
if (isset($_POST["Submit"])) {
    $UserName = $_POST["Username"];
    $Name = $_POST["Name"];
    $Password = $_POST["Password"];
    $ConfirmPassword = $_POST["ConfirmPassword"];
    $Admin = $_SESSION["AdminName"];
    date_default_timezone_set("America/Asuncion");
    $CurrentTime = time(); //en segundos
    $DateTime = strftime("%B %m %d %H: %M %S", $CurrentTime);
    echo $DateTime;
    if (empty($UserName) || empty($Password) || empty($ConfirmPassword)) {
        $_SESSION["ErrorMessage"] = "Todos los campos deben llenarse";
        Redirect_to("Admins.php");
    } elseif (strlen($Password) < 6) {
        $_SESSION["ErrorMessage"] = "El password debe tener mas de 6 caracteres";
        Redirect_to("Admins.php");
    } elseif ($Password !== $ConfirmPassword) {
        $_SESSION["ErrorMessage"] = "Password no coincide con la confirmacion";
        Redirect_to("Admins.php");
    } elseif (CheckUserNameExistence($UserName)) {
        $_SESSION["ErrorMessage"] = "Ese usuario ya existe! prueba otro!";
        Redirect_to("Admins.php");
    } else {
        //insercion de datos en sql
        global $ConnectingDB;
        $sql = "INSERT INTO admins(datetime, username, password, aname,addedby)";
        $sql .= "VALUES(:dateTime, :userName, :password, :aName, :adminName)"; //primero pasamos los nombres falsos por seguridad
        $stmt = $ConnectingDB->prepare($sql); // es necesario para enviar los datos
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':userName', $UserName); // con bindValue vinculamos la variable creada en php con la ubicacion en la tabla sql
        $stmt->bindValue(':password', $Password);
        $stmt->bindValue(':aName', $Name);
        $stmt->bindValue(':adminName', $Admin);
        $Execute = $stmt->execute(); //normalmente cuando algo no funciona es un error en statement SQL
        //        var_dump($Execute);
        if ($Execute) {
            $_SESSION["SuccessMessage"] = "El nuevo Admin llamado " . $Name . " fue agregado con exito!";
            Redirect_to("Admins.php");
        } else {
            $_SESSION["ErrorMessage"] = "Algo salió mal! Intenta de nuevo!";
            Redirect_to("Admins.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pagina de Admin</title>
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
                        <a href="Admins.php" class="nav-link">Gestionar Admin</a>
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
                    <h1><i class="fas fa-user" style="color:#dfd653;"></i>Gestionar Admins</h1>
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
                <form action="Admins.php" method="post">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Agregar Admin</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="Username"><span class="FieldInfo">Nombre Usuario: </span></label>
                                <input class="form-control mb-2" type="text" name="Username" id="username" value="">
                            </div>
                            <div class="form-group">
                                <label for="Name"><span class="FieldInfo">Nombre: </span></label>
                                <input class="form-control mb-2" type="text" name="Name" id="Name" value="">
                                <small class="text-warning text-muted">*Nombre Opcional</small>
                            </div>
                            <div class="form-group">
                                <label for="Password"><span class="FieldInfo">Password: </span></label>
                                <input class="form-control mb-2" type="password" name="Password" id="Password" value="">
                            </div>
                            <div class="form-group">
                                <label for="ConfirmPassword"><span class="FieldInfo">Confirmar Password: </span></label>
                                <input class="form-control mb-2" type="password" name="ConfirmPassword" id="ConfirmPassword" value="">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i>Volver a Resumen</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button type="submit" name="Submit" class="btn btn-success btn-block"><i class="fas fa-check"></i>Registrar</button>
                                </div>
                            </div>
                        </div>
                </form>
                <h2 class="card-header">Admins Registrados</h2>
                <table class="table table-striped table-hover" style="background-color: white;">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Agregado Por</th>
                            <th>Accion</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM admins ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $AdminId = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $AdminUsername = $DataRows["username"];
                        $AdminName = $DataRows["aname"];
                        $AddedBy = $DataRows["addedby"];
                        $SrNo++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($DateTime); ?></td>
                                <td><?php echo htmlentities($AdminUsername); ?></td>
                                <td><?php echo htmlentities($AdminName); ?></td>
                                <td><?php echo htmlentities($AddedBy); ?></td>
                                <td><a href="DeleteAdmin.php?id=<?php echo $AdminId; ?>" class="btn btn-danger">Borrar</a> </td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
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