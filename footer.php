<!--side area start-->
<div class="col-sm-3">
    <div class="card mt-4">
        <div class="card-body">
            <img src="./Images/ad.jpg" alt="ad2" class="d-block img-fluid mb-3" style="margin: auto;" />
        </div>

    </div>
    <br>
    <div class="card">
        <div class="card-header bg-dark text-light">
            <h2 class="lead">Registrarse !</h2>
        </div>
        <div class="card-body d-grid gap-2 col-12 mx-auto">
            <a href="contacto.html" type="button" class="btn btn-success text-center text-white" name="button">Unirse al Foro</a>
            <a href="Dashboard.php" type="button" class="btn btn-danger text-center text-white" name="button">Iniciar Sesion</a>
            <a href="contacto.html" type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribirse!</a>

        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header bg-primary text-light">
            <h2 class="lead">Categorias</h2>
        </div>
        <div class="card-body">
            <?php
            global $ConnectingDB;
            $sql = "SELECT * FROM category ORDER BY id desc";
            $stmt = $ConnectingDB->query($sql);
            while ($DataRows = $stmt->fetch()) { //cuando da el error Call to a member function fetch() on bool in significa que fetch no encuentra un PDO sino un boolean, muy probablemente un FALSE por un error de escritura en el $sql
                $CategoryId = $DataRows["id"];
                $CategoryName = $DataRows["title"];
            ?>
                <a href="Blog.php?category=<?php echo $CategoryName; ?>"><span class="heading"><?php echo $CategoryName; ?></span></a><br>
            <?php } ?>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header bg-info text-white">
            <h2 class="lead">Ultimos Posteos</h2>
        </div>
        <div class="card-body">
            <?php
            global $ConnectingDB;
            $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
            $stmt = $ConnectingDB->query($sql);
            while ($DataRows = $stmt->fetch()) {
                $Id = $DataRows['id'];
                $Title = $DataRows['title'];
                $DateTime = $DataRows['datetime'];
                $Image = $DataRows['image'];
            ?>
                <div class="media">
                    <img src="Uploads/<?php echo htmlentities($Image); ?>" height="90" class="d-block img-fluid align-self-start" alt="">
                    <div class="media-body ml-2">
                        <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
                            <h6 class="lead"><?php echo htmlentities($Title); ?></h6>
                        </a>
                        <p class="small"><?php echo htmlentities($DateTime); ?></p>
                    </div>
                </div>
                <hr>
            <?php } ?>
        </div>
    </div>
</div>
<!--side area end-->
</div>
</div>
<!--END OF HEADER-->
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