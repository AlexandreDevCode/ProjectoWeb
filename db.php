<?php 
$servername ="localhost";
$database ="lab_db";
$username="root";
$password="";


try{
    $conexion = new PDO("mysql:host=$servername;dbname=$database",$username,$password);


}catch(Exception $ex){
    echo $ex->getMessage();
}




?>