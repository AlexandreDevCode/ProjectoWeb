<?php 
include("../../db.php");
session_start();

// 1. Proteção de Login (Apenas administrador)
if(!isset($_SESSION['utilizador']) || $_SESSION['tipo'] != "administrador"){
    header("Location: ../../index.php");
    exit();
}

// 2. Verifica se o ID da requisição foi passado na URL
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id_requisicao = $_GET['id'];

// 3. Processamento das Ações (Aceitar / Recusar) com Ajuste de Stock e Estado
if(isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    $novo_estado = "";

    // Procura os detalhes do equipamento e quantidade desta requisição
    $busca_dados = $conexion->prepare("
        SELECT id_equipamento, quantidade 
        FROM tbl_requisicao_equipamento 
        WHERE id_requisicao = :id
    ");
    $busca_dados->bindParam(":id", $id_requisicao);
    $busca_dados->execute();
    $dados_req = $busca_dados->fetch(PDO::FETCH_ASSOC);

    if($acao == "aceitar" && $dados_req) {
        $novo_estado = "confirmado";

        // A. DESCONTA A QUANTIDADE REQUISITADA DO STOCK DO EQUIPAMENTO
        $update_stock = $conexion->prepare("
            UPDATE tbl_equipamento 
            SET quantidade = quantidade - :qtd_requisitada 
            WHERE id_equipamento = :id_equip
        ");
        $update_stock->bindParam(":qtd_requisitada", $dados_req['quantidade']);
        $update_stock->bindParam(":id_equip", $dados_req['id_equipamento']);
        $update_stock->execute();

        // B. VERIFICA SE O STOCK CHEGOU A ZERO PARA TORNAR INDISPONÍVEL
        $verificar_stock = $conexion->prepare("
            SELECT quantidade FROM tbl_equipamento WHERE id_equipamento = :id_equip
        ");
        $verificar_stock->bindParam(":id_equip", $dados_req['id_equipamento']);
        $verificar_stock->execute();
        $equip_atualizado = $verificar_stock->fetch(PDO::FETCH_ASSOC);

        if($equip_atualizado && $equip_atualizado['quantidade'] <= 0) {
            $update_disponibilidade = $conexion->prepare("
                UPDATE tbl_equipamento 
                SET estado = 'Indisponível' 
                WHERE id_equipamento = :id_equip
            ");
            $update_disponibilidade->bindParam(":id_equip", $dados_req['id_equipamento']);
            $update_disponibilidade->execute();
        }

    } elseif($acao == "recusar") {
        $novo_estado = "recusado";
        // Negado não afeta o stock em nada, apenas segue para a mudança de estado
    }

    if(!empty($novo_estado)) {
        // Atualiza o estado da requisição
        $update = $conexion->prepare("
            UPDATE tbl_requisicao 
            SET estado_req = :estado 
            WHERE id_requisicao = :id
        ");
        $update->bindParam(":estado", $novo_estado);
        $update->bindParam(":id", $id_requisicao);
        $update->execute();
        
        // Redireciona para atualizar a página e refletir as mudanças
        header("Location: ver.php?id=" . $id_requisicao);
        exit();
    }
}

// 4. Procura os detalhes completos para exibição no ecrã
$sentencia = $conexion->prepare("
    SELECT r.*, u.utilizador, e.nome_equip, re.quantidade, e.foto_equip
    FROM tbl_requisicao r
    INNER JOIN tbl_utilizador u ON r.id_utilizador = u.id_utilizador
    INNER JOIN tbl_requisicao_equipamento re ON r.id_requisicao = re.id_requisicao
    INNER JOIN tbl_equipamento e ON re.id_equipamento = e.id_equipamento
    WHERE r.id_requisicao = :id
");
$sentencia->bindParam(":id", $id_requisicao);
$sentencia->execute();
$req = $sentencia->fetch(PDO::FETCH_ASSOC);

// Se a requisição não existir, volta para a listagem
if(!$req) {
    header("Location: index.php");
    exit();
}

include("../../template/header.php");
?>

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Detalhes da Requisição #<?php echo $req['id_requisicao']; ?></h4>
        <a href="index.php" class="btn btn-secondary btn-sm">Voltar</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <img width="200" src="../equipamentos/<?php echo $req['foto_equip']; ?>" class="img-fluid rounded shadow-sm" alt="Equipamento" />
            </div>
            
            <div class="col-md-8">
                <p><b>Utilizador que pediu:</b> <span class="badge bg-dark"><?php echo $req['utilizador']; ?></span></p>
                <p><b>Equipamento solicitado:</b> <?php echo $req['nome_equip']; ?></p>
                <p><b>Quantidade solicitada:</b> <span class="badge bg-secondary"><?php echo $req['quantidade']; ?></span></p>
                <p><b>Data do Pedido:</b> <?php echo date('d-m-Y', strtotime($req['data'])); ?></p>
                
                <p><b>Estado Atual:</b> 
                    <?php if($req['estado_req'] == "pendente"){ ?>
                        <span class="badge bg-warning text-dark">Pendente</span>
                    <?php } elseif($req['estado_req'] == "confirmado"){ ?>
                        <span class="badge bg-success">Confirmado (Stock Atualizado)</span>
                    <?php } else { ?>
                        <span class="badge bg-danger">Recusado</span>
                    <?php } ?>
                </p>

                <hr>

                <?php if($req['estado_req'] == "pendente"){ ?>
                    <div class="d-flex gap-2">
                        <a href="ver.php?id=<?php echo $id_requisicao; ?>&acao=aceitar" class="btn btn-success px-4">
                            Aceitar Requisição
                        </a>
                        <a href="ver.php?id=<?php echo $id_requisicao; ?>&acao=recusar" class="btn btn-danger px-4">
                            Recusar Requisição
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-light border text-muted">
                        Esta requisição já foi processada pelo sistema e encontra-se com o estado: <b><?php echo $req['estado_req']; ?></b>.
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php include("../../template/footer.php"); ?>