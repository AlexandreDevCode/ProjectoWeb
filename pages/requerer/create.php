<?php 
include("../../db.php");
session_start();

// 1. Proteção da sessão (deve ficar no topo do arquivo)
if(!isset($_SESSION['utilizador'])){
    header("Location: ../../login.php");
    exit();
}

// Verifica se o ID do utilizador realmente existe na sessão para evitar erros de banco de dados
if (isset($_SESSION['id_utilizador'])) {
    $id_utilizador = $_SESSION['id_utilizador'];
} elseif (isset($_SESSION['id'])) {
    $id_utilizador = $_SESSION['id'];
} else {
    $id_utilizador = null;
}

// 2. Processamento do formulário de Requisição (POST)
if($_POST && isset($_GET['id_equipamento'])){
    
    if(!$id_utilizador) {
        die("<div class='alert alert-danger mt-3'>Erro de Sessão: Não foi possível identificar o utilizador logado.</div>");
    }

    $id_equipamento = $_GET['id_equipamento'];
    $quantidade = $_POST['quantidade'];
    $estado_req ="pendente";
    $data = date('Y-m-d');

    // Cria requisição
    $sentencia = $conexion->prepare("
        INSERT INTO tbl_requisicao (id_utilizador, data, estado_req)
        VALUES (:id_utilizador, :data, :estado_req)
    ");
    $sentencia->bindParam(":id_utilizador", $id_utilizador);
    $sentencia->bindParam(":data", $data);
    $sentencia->bindParam(":estado_req", $estado_req);
    $sentencia->execute();

    $id_requisicao = $conexion->lastInsertId();

    // Liga equipamento
    $sentencia = $conexion->prepare("
        INSERT INTO tbl_requisicao_equipamento 
        (id_requisicao, id_equipamento, quantidade)
        VALUES (:id_requisicao, :id_equipamento, :quantidade)
    ");
    $sentencia->bindParam(":id_requisicao", $id_requisicao);
    $sentencia->bindParam(":id_equipamento", $id_equipamento);
    $sentencia->bindParam(":quantidade", $quantidade);
    $sentencia->execute();

    header("Location: index.php?sucesso=1");
    exit();
}

include("../../template/header.php");

// 3. FLUXO: Se existir um ID na URL, mostra o formulário de quantidade
if (isset($_GET['id_equipamento'])) {
    $id_equipamento = $_GET['id_equipamento'];

    $sentencia = $conexion->prepare("
        SELECT e.*, c.nome_cat
        FROM tbl_equipamento e
        LEFT JOIN tbl_categoria c ON e.id_categoria = c.id_categoria
        WHERE e.id_equipamento = :id
    ");
    $sentencia->bindParam(":id", $id_equipamento);
    $sentencia->execute();
    $equip = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Se o equipamento existir na base de dados, mostra o formulário de quantidade
    if ($equip) {
?>
        <div class="card mt-3">
            <div class="card-header">
                <h4>Requisitar Equipamento</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img style="width:100%" src="../equipamentos/<?php echo $equip['foto_equip']; ?>">
                    </div>
                    <div class="col-md-8">
                        <h3><?php echo $equip['nome_equip']; ?></h3>
                        <p>Categoria: <?php echo $equip['nome_cat']; ?></p>
                        <p><b>Stock:</b> <?php echo $equip['quantidade']; ?></p>

                        <form method="POST">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <button type="button" class="btn btn-danger" onclick="diminuir()">-</button>
                                <input type="number" id="quantidade" name="quantidade" value="1" min="1" max="<?php echo $equip['quantidade']; ?>" class="form-control text-center" style="width:100px;">
                                <button type="button" class="btn btn-success" onclick="aumentar()">+</button>
                            </div>
                            <button class="btn btn-primary">Confirmar Requisição</button>
                            <a href="create.php" class="btn btn-secondary">Voltar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function aumentar(){
            let input = document.getElementById("quantidade");
            if(parseInt(input.value) < parseInt(input.max)){
                input.value++;
            }
        }
        
        function diminuir(){
            let input = document.getElementById("quantidade");
            if(input.value > 1){
                input.value--;
            }
        }
        </script>
<?php 
    } else {
        echo "<div class='alert alert-danger mt-3'>Equipamento não encontrado.</div>";
    }
} else { 
    // 4. FLUXO: Se NÃO houver ID na URL, mostra a lista para escolher o equipamento
    $sentencia = $conexion->prepare("SELECT e.*, c.nome_cat as categoria 
                                   FROM tbl_equipamento e 
                                   LEFT JOIN tbl_categoria c ON e.id_categoria = c.id_categoria");
    $sentencia->execute();
    $lista_equip = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="text-center">Requerer Equipamento</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table">
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
                        <?php foreach ($lista_equip as $registo) { ?>
                        <tr>
                            <td>
                                <img width="70" src="../equipamentos/<?php echo $registo['foto_equip']?>" class="img-fluid rounded" alt="" />
                            </td>
                            <td><?php echo $registo['nome_equip'];?></td>
                            <td>
                                <?php if($registo['estado'] == "Disponível"){ ?>
                                    <span class="badge bg-success"><?php echo $registo['estado']; ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-danger"><?php echo $registo['estado']; ?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo $registo['categoria'];?></td>
                            <td>
                                <?php if($registo['estado'] == "Disponível"){ ?>
                                    <a class="btn btn-success" href="create.php?id_equipamento=<?php echo $registo['id_equipamento'];?>" role="button">
                                        Requerer
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } ?>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        Swal.fire({
            title: "Good job!",
            text: "You clicked the button!",
            icon: "success"
        });
        // Limpa o ?sucesso=1 da URL de forma elegante
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
<?php endif; ?>




<?php include("../../template/footer.php"); ?>