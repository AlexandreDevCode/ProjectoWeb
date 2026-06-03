<?php include("../../db.php");

if($_POST){

    $utilizador = (isset($_POST["utilizador"])?$_POST["utilizador"]:"");
    $password_user = (isset($_POST["password_user"])?$_POST["password_user"]:"");
    $email = (isset($_POST["email"])?$_POST["email"]:"");
    $tipo = (isset($_POST["tipo"])?$_POST["tipo"]:"utilizador");

    $sentencia = $conexion->prepare("
    INSERT INTO tbl_utilizador
    (id_utilizador,utilizador,password_user,email,tipo)
    VALUES
    (null,:utilizador,:password_user,:email,:tipo)");

    $sentencia->bindParam(":utilizador",$utilizador);
    $sentencia->bindParam(":password_user",$password_user);
    $sentencia->bindParam(":email",$email);
    $sentencia->bindParam(":tipo",$tipo);

    $sentencia->execute();

    $mensagem = "Registo Criado com Sucesso!";
    header("Location:index.php?mensagem=".$mensagem);

}
?>

<?php include("../../template/header.php"); ?>

<br/>

<div class="card">
    <div class="card-header">
        Adicionar Utilizador
    </div>

    <div class="card-body">

        <form action="" method="post">

            <div class="mb-3">
                <label for="utilizador" class="form-label">Nome de Utilizador</label>
                <input
                    type="text"
                    class="form-control"
                    name="utilizador"
                    id="utilizador"
                    placeholder="Nome de Utilizador"
                    required
                />
            </div>

            <div class="mb-3">
                <label for="password_user" class="form-label">Password</label>
                <input
                    type="password"
                    class="form-control"
                    name="password_user"
                    id="password_user"
                    placeholder="Password"
                    required
                />
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    name="email"
                    id="email"
                    placeholder="Email"
                    required
                />
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Utilizador</label>
                <select
                    class="form-control"
                    name="tipo"
                    id="tipo"
                >
                    <option value="utilizador">Utilizador</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <button
                type="submit"
                class="btn btn-success">
                Adicionar Utilizador
            </button>

            <a
                class="btn btn-danger"
                href="index.php"
                role="button">
                Cancelar
            </a>

        </form>

    </div>

    <div class="card-footer text-muted">
        Footer
    </div>
</div>

<?php include("../../template/footer.php"); ?>