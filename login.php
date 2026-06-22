<?php 
session_start();
include("db.php");

if($_POST){

    $utilizador = $_POST["utilizador"];
    $password_user = $_POST["password_user"];

    // 1. Buscar apenas o utilizador
    $sentencia = $conexion->prepare("
        SELECT *
        FROM tbl_utilizador 
        WHERE utilizador = :utilizador
    ");

    $sentencia->bindParam(":utilizador", $utilizador);
    $sentencia->execute();

    $registo = $sentencia->fetch(PDO::FETCH_ASSOC);

    // 2. Verificar password com hash
    if($registo && password_verify($password_user, $registo['password_user'])){

        $_SESSION["utilizador"] = $registo["utilizador"];
        $_SESSION["tipo"] = $registo["tipo"];
        $_SESSION["id_utilizador"] = $registo["id_utilizador"];
        $_SESSION["logado"] = true;

        header("Location: index.php");
        exit();

    } else {
        $mensagem = "Error: Utilizador ou password estão incorretos";
    }
}
?>

<!doctype html>
<html lang="pt" data-bs-theme="light">
<head>
    <title>Login - LabStock</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <br><br>

            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">

                    <?php if(isset($mensagem)){ ?>
                        <div class="alert alert-danger">
                            <strong><?php echo $mensagem; ?></strong>
                        </div>
                    <?php } ?>

                    <form method="post">

                        <div class="mb-3">
                            <label>Utilizador</label>
                            <input type="text" class="form-control" name="utilizador" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password_user" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Entrar no sistema
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>