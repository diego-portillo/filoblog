<?php
//primer parametro del PDO(PHP database object): DSN(Data source network)
$DSN = 'mysql:host = localhost; dbname=filoblog';
$ConnectingDB = new PDO($DSN, 'root', '');
