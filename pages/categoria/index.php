<?php
include("../../db.php");

if(isset($_GET['txtID'])){

    $txtID = (isset($_GET['txtID']) ? $_GET['txtID'] : "");

    $sentencia = $conexion->prepare("
    DELETE FROM tbl_categoria
    WHERE id_categoria=:id_categoria
    ");

    $sentencia->bindParam(":id_categoria", $txtID);
    $sentencia->execute();

    $mensagem = "Categoria Eliminada com Sucesso";

    header("Location:index.php?mensagem=".$mensagem);
    exit();
}

$sentencia = $conexion->prepare("SELECT * FROM tbl_categoria");
$sentencia->execute();
$lista_categoria = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../template/header.php"); ?>

<div class="card">

    <div class="card-header">

        <a
            class="btn btn-primary"
            href="create.php"
            role="button">
            Adicionar Categoria
        </a>

    </div>

    <div class="card-body">

        <div class="table-responsive-sm">

            <table class="table" id="table_id">

                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome da Categoria</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach($lista_categoria as $registo){ ?>

                    <tr>

                        <td><?php echo $registo['id_categoria']; ?></td>

                        <td><?php echo $registo['nome_cat']; ?></td>

                        <td>

                            <a
                                class="btn btn-success"
                                href="update.php?txtID=<?php echo $registo['id_categoria']; ?>"
                                role="button">
                                Editar
                            </a>

                            <a
                                class="btn btn-danger"
                                href="javascript:eliminar(<?php echo $registo['id_categoria']; ?>)"
                                role="button">
                                Eliminar
                            </a>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
function eliminar(id){
    Swal.fire({
        title: "Deseja eliminar a categoria?",
        showDenyButton: true,
        confirmButtonText: "Sim, Eliminar"
    }).then((result) => {

        if(result.isConfirmed){
            window.location = "index.php?txtID=" + id;
        }

    });
}
</script>

<?php include("../../template/footer.php"); ?>