<?php 
include("../../db.php");
session_start();

if(!isset($_SESSION['utilizador'])){
    header("Location: ../../login.php");
    exit();
}

$isAdmin = ($_SESSION['tipo'] == "administrador");

?>


<?php include("../../template/header.php"); ?>




<?php include("../../template/footer.php"); ?>