<?php
include("../../db.php");

if(isset($_GET['txtID'])){

    $txtID = $_GET['txtID'];

    $sentencia = $conexion->prepare("
        SELECT * FROM tbl_categoria
        WHERE id_categoria=:id_categoria
    ");

    $sentencia->bindParam(":id_categoria", $txtID);
    $sentencia->execute();

    $registo = $sentencia->fetch(PDO::FETCH_LAZY);

    $nome_cat = $registo['nome_cat'];
}

if($_POST){

    $txtID = $_POST['txtID'];
    $nome_cat = $_POST['nome_cat'];

    $sentencia = $conexion->prepare("
        UPDATE tbl_categoria 
        SET nome_cat=:nome_cat 
        WHERE id_categoria=:id_categoria
    ");

    $sentencia->bindParam(":nome_cat", $nome_cat);
    $sentencia->bindParam(":id_categoria", $txtID);

    $sentencia->execute();

    $mensagem = "Categoria Atualizada com Sucesso";

    header("Location:index.php?mensagem=".$mensagem);
    exit();
}
?>

<?php include("../../template/header.php"); ?>

<div class="card">

    <div class="card-header">
        <h4>Editar Categoria</h4>
    </div>

    <div class="card-body">

        <form method="post">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text"
                       class="form-control"
                       name="txtID"
                       value="<?php echo $txtID; ?>"
                       readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Nome da Categoria</label>
                <input type="text"
                       class="form-control"
                       name="nome_cat"
                       value="<?php echo $nome_cat; ?>"
                       placeholder="Nome da categoria">
            </div>

            <button class="btn btn-success" type="submit">
                Atualizar
            </button>

            <a href="index.php" class="btn btn-danger">
                Cancelar
            </a>

        </form>

    </div>

</div>

<?php include("../../template/footer.php"); ?>