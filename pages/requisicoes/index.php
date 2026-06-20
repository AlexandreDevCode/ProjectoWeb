<?php 
include("../../db.php");
session_start();

// 1. Proteção de Login e Nível de Acesso (Apenas administrador)
if(!isset($_SESSION['utilizador']) || $_SESSION['tipo'] != "administrador"){
    header("Location: ../../index.php"); // Redireciona para a página inicial se não for admin
    exit();
}

include("../../template/header.php");
?>

<div class="card mt-3">
    <div class="card-header">
        <h4>Requisições</h4>
    </div>

    <div class="card-body">

    <?php
    // Atualizado: Adicionado re.quantidade na consulta SQL
    $sentencia = $conexion->prepare("
        SELECT r.*, u.utilizador, e.nome_equip, re.quantidade
        FROM tbl_requisicao r
        INNER JOIN tbl_utilizador u ON r.id_utilizador = u.id_utilizador
        INNER JOIN tbl_requisicao_equipamento re ON r.id_requisicao = re.id_requisicao
        INNER JOIN tbl_equipamento e ON re.id_equipamento = e.id_equipamento
        ORDER BY r.id_requisicao DESC
    ");
    $sentencia->execute();
    $lista = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilizador</th>
                    <th>Equipamento</th>
                    <th>Quantidade</th> <th>Data</th>
                    <th>Estado</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($lista as $r){ ?>
                <tr>
                    <td><?php echo $r['id_requisicao']; ?></td>
                    <td><?php echo $r['utilizador']; ?></td>
                    <td><?php echo $r['nome_equip']; ?></td>
                    
                    <td><?php echo $r['quantidade']; ?></td>
                    
                    <td><?php echo date('d-m-Y', strtotime($r['data'])); ?></td>
                    
                    <td>
                        <?php if($r['estado_req'] == "pendente"){ ?>
                            <span class="badge bg-warning text-dark">Pendente</span>
                        <?php } elseif($r['estado_req'] == "confirmado"){ ?>
                            <span class="badge bg-success">Confirmado</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Recusado</span>
                        <?php } ?>
                    </td>

                    <td>
                        <?php if($r['estado_req'] == "pendente"){ ?>
                            <a class="btn btn-info btn-sm" href="ver.php?id=<?php echo $r['id_requisicao']; ?>">
                                Ver
                            </a>
                        <?php } else { ?>
                            <span class="text-muted" style="padding-left: 10px;">-</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    </div>
</div>

<?php include("../../template/footer.php"); ?>