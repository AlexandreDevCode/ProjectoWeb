<?php 
session_start();
include("db.php");

if($_POST){
    $sentencia=$conexion->prepare("SELECT *, count(*) as n_user
    FROM `tbl_utilizador` 
    WHERE utilizador=:utilizador
    AND password_user=:password_user");
    $utilizador=$_POST["utilizador"];
    $password_user=$_POST["password_user"];
    $sentencia->bindParam(":utilizador", $utilizador);
    $sentencia->bindParam(":password_user", $password_user);
    $sentencia->execute();
    $registo = $sentencia->fetch(PDO::FETCH_LAZY);

    if($registo["n_user"]>0){
        $_SESSION["utilizador"]=$registo["utilizador"];
        $_SESSION["tipo"]= $registo["tipo"];
        
        // CORREÇÃO: Grava o ID do utilizador na sessão para ser usado nas requisições
        $_SESSION["id_utilizador"] = $registo["id_utilizador"]; 
        
        $_SESSION["logado"]=true;
        header("Location:index.php");
        exit();
    }else{
        $mensagem= "Error: Utilizador ou password estão incorretos";
    }
}
?>

<!doctype html>
<html lang="pt" data-bs-theme="light">
    <head>
        <title>Login - LabStock</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    </head>
    <body>
        <main class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <br><br>
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                           <?php if(isset($mensagem)){?>
                            <div class="alert alert-danger" role="alert">
                                <strong><?php echo $mensagem?></strong> 
                            </div>
                            <?php } ?>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="utilizador" class="form-label">Utilizador</label>
                                    <input type="text" class="form-control" name="utilizador" id="utilizador" placeholder="Digite o nome do utilizador" required />
                                </div>
                                <div class="mb-3">
                                    <label for="password_user" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password_user" id="password_user" required />
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Entrar no sistema</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>