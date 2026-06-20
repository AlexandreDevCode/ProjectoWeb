<?php 
include("../../db.php");
session_start();

// 1. Proteção de Login (Garante que o utilizador está logado)
if(!isset($_SESSION['utilizador'])){
    header("Location: ../../login.php");
    exit();
}

// Recupera o ID do utilizador que está guardado na sessão
if (isset($_SESSION['id_utilizador'])) {
    $id_utilizador = $_SESSION['id_utilizador'];
} elseif (isset($_SESSION['id'])) {
    $id_utilizador = $_SESSION['id'];
} else {
    $id_utilizador = null;
}

include("../../template/header.php");
?>

<div class="card mt-3">
    <div class="card-header">
        <h4 class="text-center">O Meu Inventário / Requisições</h4>
    </div>

    <div class="card-body">

    <?php
    if ($id_utilizador) {
        // Consulta SQL filtrada pelo ID do utilizador logado
        $sentencia = $conexion->prepare("
            SELECT r.*, e.nome_equip, re.quantidade, e.foto_equip
            FROM tbl_requisicao r
            INNER JOIN tbl_requisicao_equipamento re ON r.id_requisicao = re.id_requisicao
            INNER JOIN tbl_equipamento e ON re.id_equipamento = e.id_equipamento
            WHERE r.id_utilizador = :id_utilizador
            ORDER BY r.id_requisicao DESC
        ");
        $sentencia->bindParam(":id_utilizador", $id_utilizador);
        $sentencia->execute();
        $meu_inventario = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Equipamento</th>
                    <th>Quantidade</th>
                    <th>Data do Pedido</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (count($meu_inventario) > 0) {
                    foreach($meu_inventario as $item){ 
                ?>
                <tr>
                    <td>
                        <img width="60" src="../equipamentos/<?php echo $item['foto_equip']; ?>" class="img-fluid rounded" alt="Equipamento" />
                    </td>
                    <td><b><?php echo $item['nome_equip']; ?></b></td>
                    <td><?php echo $item['quantidade']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($item['data'])); ?></td>
                    
                    <td>
                        <?php if($item['estado_req'] == "pendente"){ ?>
                            <span class="badge bg-warning text-dark">A aguardar aprovação</span>
                        <?php } elseif($item['estado_req'] == "confirmado"){ ?>
                            <span class="badge bg-success">Confirmado</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Recusado</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php 
                    } 
                } else { 
                ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Ainda não efetuaste nenhuma requisição de equipamento.
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php 
    } else {
        echo "<div class='alert alert-danger'>Erro de sessão: não foi possível identificar o teu utilizador.</div>";
    } 
    ?>

    </div>
</div>

<?php include("../../template/footer.php"); ?>