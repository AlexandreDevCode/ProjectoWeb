<?php
include("../../db.php"); 
if($_POST){
    print_r($_POST);
    $nome_cat = (isset($_POST["nome_cat"])?$_POST["nome_cat"]:"");
    $sentencia= $conexion-> prepare("INSERT INTO tbl_categoria(id_categoria,nome_cat) VALUES (null, :nome_cat)");
    $sentencia->bindParam(":nome_cat", $nome_cat);
    $sentencia->execute();
    header("location:index.php");
}
?>




<?php include("../../template/header.php");?>

<div class="card">
    <div class="card-header">Categoria de Equipamento</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome_cat" class="form-label">Nome da Categoria </label>
            <input
                type="text"
                class="form-control"
                name="nome_cat"
                id="nome_cat"
                aria-describedby="helpId"
                placeholder="Nome "
            />
        </div>


       

        <button
            type="submit"
            class="btn btn-success"
        >
            Adicionar Categoria
        </button>
        <a
            name=""
            id=""
            class="btn btn-danger"
            href="index.php"
            role="button"
            >Cancelar</a
        >
     </form>    
        
        

    </div>
    
</div>

<?php include("../../template/footer.php");?>