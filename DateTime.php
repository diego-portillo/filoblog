<?php
//para ajustar la zona horaria usamos:
date_default_timezone_set("America/Asuncion");
$CurrentTime=time(); //en segundos
$DateTime= strftime("%h %d %Y %H:%M",$CurrentTime);//con el primer parametro determinamos el formato que queremos darle al tiempo (dias, anhos horas, etc)
//segundo parametro es current time en segundos
echo $DateTime;
?>