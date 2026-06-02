<?php include("../../db.php"); 

if(isset($_GET['txtID'])){
    
    $txtID = $_GET['txtID'];

    $sentencia=$conexion->prepare("
    DELETE FROM tbl_utilizador 
    WHERE id_utilizador=:id_utilizador
    ");

    $sentencia->bindParam(":id_utilizador",$txtID);
    $sentencia->execute();
    $mensagem="Registo Eliminado com Sucesso";

    header("Location:index.php?mensagem=".$mensagem);
    exit();
}

$sentencia = $conexion->prepare("SELECT * FROM tbl_utilizador");
$sentencia->execute();
$lista_utilizadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../template/header.php");?> 
<div class="card">

    <div class="card-header">
        <a class="btn btn-primary" href="create.php" role="button">
            Adicionar Utilizador
        </a>
    </div>

    <div class="card-body">

        <div class="table-responsive-sm">

            <table class="table" id="table_id">

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach($lista_utilizadores as $registo){ ?>

                    <tr>

                        <td><?php echo $registo['utilizador']; ?></td>
                        <td><?php echo $registo['email']; ?></td>
                        <td><?php echo $registo['password']; ?></td>
                        <td><?php echo $registo['tipo']; ?></td>

                        <td>

                          <a class="btn btn-success"
                             href="update.php?txtID=<?php echo $registo['id_utilizador']; ?>">Editar
                            </a>

                            <a class="btn btn-danger"
                               href="javascript:eliminar('<?php echo $registo['id_utilizador']; ?>', '<?php echo $registo['utilizador']; ?>');"
                               role="button">Eliminar
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
        function eliminar(id, nome){
            Swal.fire({
            title: "Deseja eliminar o utilizador "+ nome + "?",
            showDenyButton: true,
            
            confirmButtonText: "Sim, Eliminar",
            }).then((result) => {
            if (result.isConfirmed){
                window.location="index.php?txtID="+id;
            }
            });
           

        }

   </script> 

<?php include("../../template/footer.php"); ?>