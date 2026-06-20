<?php 
include("../../db.php");



$sentencia = $conexion->prepare("SELECT e.*, c.nome_cat as categoria 
                               FROM tbl_equipamento e 
                               LEFT JOIN tbl_categoria c ON e.id_categoria = c.id_categoria");

$sentencia->execute();
$lista_equip=$sentencia->fetchALL(PDO::FETCH_ASSOC);





?>
<?php include("../../template/header.php");?>
<div class="card">
    <div class="card">
        <h3 class="text-center">Requerer Equipamento</h3>
    
    <div class="card-body">
        <div
            class="table-responsive-sm"
        >
            <table
                class="table" 
            >
                <thead>
                    <tr>
                        <th scope="col">Imagem</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_equip as $registo) {?>
                    <tr class="">
                        <td scope="row">
                            <img width="70" 
                                    src="../equipamentos/<?php echo $registo['foto_equip']?>"
                                    class="img-fluid rounded-top"
                                    alt="" />
                        </td>
                        <td><?php echo $registo['nome_equip'];?></td>
                        <td><?php if($registo['estado'] == "Disponível"){ ?>
                                    <span class="badge bg-success">
                                        <?php echo $registo['estado']; ?>
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">
                                        <?php echo $registo['estado']; ?>
                                    </span>
                                <?php } ?></td>
                        <td><?php echo $registo['categoria'];?></td>
                        
                       
                      <td>
                       <?php if($registo['estado'] == "Disponível"){ ?>
                                    <a
                                        class="btn btn-success"
                                        href="create.php?id_equipamento=<?php echo $registo['id_equipamento'];?>"
                                        role="button"
                                    >
                                        Requerer
                                    </a>
                                <?php } ?>
                       </td>
                    </tr>

                    <?php }?>
                    
                    
        
                    
                    
                </tbody>
            </table>
        </div>
        
</div>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        Swal.fire({
            title: "Equipamento Requisitado!",
            text: "Aguarde a resposta do administrador.",
            icon: "success"
        });

        // Limpa o "?sucesso=1" da barra de endereço para não repetir o alerta ao fazer F5
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
<?php endif; ?>



<?php include("../../template/footer.php");?>