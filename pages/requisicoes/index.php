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
$sentencia = $conexion->prepare("
    SELECT r.*, u.utilizador
    FROM tbl_requisicao r
    INNER JOIN tbl_utilizador u 
    ON r.id_utilizador = u.id_utilizador
    ORDER BY r.id_requisicao DESC
");
$sentencia->execute();
$lista = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Utilizador</th>
            <th>Data</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($lista as $r){ ?>
        <tr>
            <td><?php echo $r['id_requisicao']; ?></td>
            <td><?php echo $r['utilizador']; ?></td>
            <td><?php echo $r['data']; ?></td>
            <td>
                <a class="btn btn-info" href="ver.php?id=<?php echo $r['id_requisicao']; ?>">
                    Ver
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</div>
</div>

<?php include("../../template/footer.php"); ?>