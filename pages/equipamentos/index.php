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


if(isset($_GET['txtID'])){
    $sentencia=$conexion->prepare("SELECT foto_equip  FROM `tbl_equipamento` WHERE id_equipamento= :id_equipamento");
    $sentencia->bindParam(":id_equipamento",$txtID);
    $sentencia->execute();
    $registo_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);
    $sentencia = $conexion->prepare("DELETE FROM tbl_equipamento WHERE id_equipamento=:id_equipamento");
    $sentencia->bindParam(":id_equipamento",$txtID);
    $sentencia->execute();
    $mensagem="Registo Eliminado com Sucesso";
    header("location:index.php?mensagem=".$mensagem);
}
if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia = $conexion->prepare("DELETE FROM tbl_equipamento WHERE id_equipamento=:id_equipamento");
    $sentencia->bindParam(":id_equipamento",$txtID);
    $sentencia->execute();
    $mensagem="Registo Eliminado com Sucesso";
    header("location:index.php?mensagem=".$mensagem);
    
}



if(isset($registo_recuperado['foto_equip']) && $registo_recuperado['foto_equip'] != ""){
    if(file_exists("./".$registo_recuperado["foto_equip"])){
        unlink("./".$registo_recuperado["foto_equip"]);

    }
}
$sentencia = $conexion->prepare("SELECT e.*, c.nome_cat as categoria 
                               FROM tbl_equipamento e 
                               LEFT JOIN tbl_categoria c ON e.id_categoria = c.id_categoria");

$sentencia->execute();
$lista_equip=$sentencia->fetchALL(PDO::FETCH_ASSOC);
?>




<?php include("../../template/header.php");?>

<div class="card">
    <div class="card-header">

    <a
        name=""
        id=""
        class="btn btn-info"
        href="<?php echo $url_base;?>/pages/equipamentos/createcategory.php"
        role="button"
        >Adicionar  Categoria</a
    >

    <a
        name=""
        id=""
        class="btn btn-primary"
        href="<?php echo $url_base;?>/pages/equipamentos/create.php"
        role="button"
        >Adicionar  Equipamento</a
    >
    
    </div>
    
    <div class="card-body">
        <div
            class="table-responsive-sm"
        >
            <table
                class="table" id="table_id"
            >
                <thead>
                    <tr>
                        <th scope="col">Imagem</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_equip as $registo) {?>
                    <tr class="">
                        <td scope="row">
                            <img width="70" 
                                    src="<?php echo $registo['foto_equip']?>"
                                    class="img-fluid rounded-top"
                                    alt="" />
                        </td>
                        <td><?php echo $registo['nome_equip'];?></td>
                        <td><?php echo $registo['estado'];?></td>
                        <td><?php echo $registo['quantidade'];?></td>
                        <td><?php echo $registo['categoria'];?></td>
                       
                      <td>
                       <a
                        name=""
                        id=""
                        class="btn btn-success"
                        href="update.php?txtID=<?php echo $registo['id_equipamento'];?>"
                        role="button"
                        >Editar</a>
                        <a
                        name=""
                        id=""
                        class="btn btn-danger"
                        href="javascript:eliminar(<?php echo $registo['id_equipamento']; ?>)"
                        role="button"
                        >Eliminar</a>

                       </td>
                    </tr>

                    <?php }?>
                    
                    
        
                    
                    
                </tbody>
            </table>
        </div>
        
</div>

<script>
        function eliminar(id){
            Swal.fire({
            title: "Deseja eliminar o registo ?",
            showDenyButton: true,
            
            confirmButtonText: "Sim, Eliminar",
            }).then((result) => {
            if (result.isConfirmed){
                window.location="index.php?txtID="+id;
            }
            });
           

        }

   </script> 



<?php include("../../template/footer.php");?>