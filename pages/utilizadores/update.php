<?php 
include("../../db.php");

if(isset($_GET['txtID'])){

    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");

    $sentencia = $conexion->prepare("
    SELECT * FROM tbl_utilizador
    WHERE id_utilizador=:id_utilizador");

    $sentencia->bindParam(":id_utilizador",$txtID);
    $sentencia->execute();

    $registo = $sentencia->fetch(PDO::FETCH_LAZY);

    $utilizador = $registo['utilizador'];
    $email = $registo['email'];
    $password_user = $registo['password_user'];
    $tipo = $registo['tipo'];
}

if($_POST){

    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $utilizador = (isset($_POST["utilizador"])?$_POST["utilizador"]:"");
    $email = (isset($_POST["email"])?$_POST["email"]:"");
    $password_user = (isset($_POST["password_user"])?$_POST["password_user"]:"");
    $tipo = (isset($_POST["tipo"])?$_POST["tipo"]:"");

    $sentencia = $conexion->prepare("
    UPDATE tbl_utilizador
    SET
        utilizador=:utilizador,
        email=:email,
        password_user=:password_user,
        tipo=:tipo
    WHERE id_utilizador=:id_utilizador");

    $sentencia->bindParam(":utilizador",$utilizador);
    $sentencia->bindParam(":email",$email);
    $sentencia->bindParam(":password_user",$password_user);
    $sentencia->bindParam(":tipo",$tipo);
    $sentencia->bindParam(":id_utilizador",$txtID);

    $sentencia->execute();

    $mensagem="Atualizado com Sucesso";
    header("Location:index.php?mensagem=".$mensagem);
}

?>

<?php include("../../template/header.php");?>

<div class="card">

    <div class="card-header">
        Atualizar Utilizador
    </div>

    <div class="card-body">

        <form action="" method="post">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input
                    type="text"
                    value="<?php echo $txtID;?>"
                    class="form-control"
                    readonly
                    name="txtID"
                    id="txtID"
                />
            </div>

            <div class="mb-3">
                <label class="form-label">Nome de Utilizador</label>
                <input
                    type="text"
                    value="<?php echo $utilizador;?>"
                    class="form-control"
                    name="utilizador"
                    id="utilizador"
                    placeholder="Nome de Utilizador"
                />
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    value="<?php echo $email;?>"
                    class="form-control"
                    name="email"
                    id="email"
                    placeholder="Email"
                />
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="text"
                    value="<?php echo $password_user;?>"
                    class="form-control"
                    name="password_user"
                    id="password_user"
                    placeholder="Password"
                />
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Utilizador</label>

                <select class="form-control" name="tipo" id="tipo">

                    <option value="utilizador"
                    <?php if($tipo=="utilizador") echo "selected"; ?>>
                        Utilizador
                    </option>

                    <option value="administrador"
                    <?php if($tipo=="administrador") echo "selected"; ?>>
                        Administrador
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn btn-success">
                Atualizar Utilizador
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

<?php include("../../template/footer.php");?>