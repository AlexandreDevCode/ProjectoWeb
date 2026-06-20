<?php
include("../../db.php");
session_start();

if(!isset($_SESSION['utilizador'])){
    header("Location: ../../login.php");
    exit();
}

if($_SESSION['tipo'] != "administrador"){
    header("Location: ../../login.php");
    exit();
}


$sentencia = $conexion->prepare("
SELECT SUM(IFNULL(quantidade,0)) as total
FROM tbl_equipamento
");
$sentencia->execute();
$totalEquip = $sentencia->fetch(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("
SELECT COUNT(*) as total
FROM tbl_requisicao
");
$sentencia->execute();
$totalRequisicoes = $sentencia->fetch(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("
SELECT COUNT(*) as total
FROM tbl_requisicao
WHERE estado_req = 'pendente'
");
$sentencia->execute();
$pendentes = $sentencia->fetch(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("
SELECT COUNT(*) as total
FROM tbl_requisicao
WHERE estado_req = 'confirmado'
");
$sentencia->execute();
$confirmadas = $sentencia->fetch(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("
SELECT COUNT(*) as total
FROM tbl_requisicao
WHERE estado_req = 'recusado'
");

$sentencia->execute();
$recusadas = $sentencia->fetch(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("
SELECT COUNT(*) as total
FROM tbl_categoria
");
$sentencia->execute();
$totalCat = $sentencia->fetch(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("
SELECT COUNT(*) as total
FROM tbl_utilizador
");
$sentencia->execute();
$totalUser = $sentencia->fetch(PDO::FETCH_ASSOC);

// adicionar total requisiçõ 

$sentencia = $conexion->prepare("
SELECT
    c.nome_cat,
    SUM(IFNULL(e.quantidade,0)) as total
FROM tbl_categoria c
LEFT JOIN tbl_equipamento e
ON c.id_categoria = e.id_categoria
GROUP BY c.id_categoria, c.nome_cat
ORDER BY c.nome_cat
");
$sentencia->execute();
$categorias = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../template/header.php");
?>

<div class="card">

    <div class="card-header">
        <h4>Dashboard do Sistema</h4>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Equipamentos</h5>
                        <h2><?php echo $totalEquip['total']; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Total Categorias</h5>
                        <h2><?php echo $totalCat['total']; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5>Total Utilizadores</h5>
                        <h2><?php echo $totalUser['total']; ?></h2>
                    </div>
                </div>
            </div>

            

        <hr>

        <div class="row">

        <h4>Requisições</h4>


    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5>Total Requisições</h5>
                <h2><?php echo $totalRequisicoes['total']; ?></h2>
            </div>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5>Pendentes</h5>
                <h2><?php echo $pendentes['total']; ?></h2>
            </div>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5>Confirmadas</h5>
                <h2><?php echo $confirmadas['total']; ?></h2>
            </div>
        </div>
    </div>

   
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5>Recusadas</h5>
                <h2><?php echo $recusadas['total']; ?></h2>
            </div>
        </div>
    </div>

    

    
</div>

        
        <h5>Equipamentos por Categoria</h5>

        


        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Total de Equipamentos</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach($categorias as $categoria){ ?>

                <tr>
                    <td><?php echo $categoria['nome_cat']; ?></td>
                    <td><?php echo $categoria['total']; ?></td>
                </tr>

                <?php } ?>

                

            </tbody>

        </table>

    </div>

</div>

<?php include("../../template/footer.php"); ?>