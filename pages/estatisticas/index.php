<?php 
include("../../db.php");
session_start();

if(!isset($_SESSION['utilizador'])){
    header("Location: ../../login.php");
    exit();
}

if($_SESSION['tipo']!="administrador"){
    echo "Acesso negado!";
   header("Location: ../../login.php");
    exit();
}


?>


<?php include("../../template/header.php");?>


<?php include("../../template/footer.php");?>