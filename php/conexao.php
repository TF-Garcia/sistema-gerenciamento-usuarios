<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

	 $conn = mysqli_connect("localhost","adm_mecanica","AdMcar#2025$","adm_mecanica");
	// $conn = mysqli_connect(" ipDo000"login","senha","loginBD");	
    //$conn = mysqli_connect("172.16.0.8","23024","EtK193@","23024");
    if (!$conn) {
    	die("Não foi possivel conectar ao Banco de Dados!");
    } else {
        //echo("Logou bonito");
    }
    
    date_default_timezone_set("Brazil/East");
    mysqli_query($conn,"SET NAMES 'utf8'");
?> 