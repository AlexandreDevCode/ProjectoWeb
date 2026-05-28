<?php 
include("../../db.php");
if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("SELECT * FROM  
    tbl_equipamento WHERE id_equipamento=:id_equipamento");
    $sentencia->bindParam(":id_equipamento",$txtID);
    $sentencia->execute();
    $registo=$sentencia->fetch(PDO::FETCH_LAZY);

    $foto_equip= $registo['foto_equip'];
    $nome_equip= $registo['nome_equip'];
    $quantidade= $registo['quantidade'];
    $estado= $registo['estado'];
    $id_categoria= $registo['id_categoria'];

    $sentencia=$conexion->prepare("SELECT *, (SELECT nome_equip FROM tbl_equipamento WHERE 
    tbl_equipamento.id_equipamento = tbl_equipamento.id_equipamento limit 1) as categoria FROM `tbl_equipamento`");
    $sentencia->execute();
    $lista_equip=$sentencia->fetchALL(PDO::FETCH_ASSOC);

}

if($_POST){
    $txtID = (isset($_POST['txtID'])?$_POST['txtID']:"");
    $foto_equip= (isset($_FILES["foto_equip"]['name'])?$_FILES["foto_equip"]['name']:"");
    $nome_equip= (isset($_POST["nome_equip"])?$_POST["nome_equip"]:"");
    $quantidade= (isset($_POST["quantidade"])?$_POST["quantidade"]:"");
    $estado= (isset($_POST["estado"])?$_POST["estado"]:"");
    $id_categoria= (isset($_POST["id_categoria"])?$_POST["id_categoria"]:"");

    $sentencia=$conexion->prepare("UPDATE `tbl_equipamento`
    SET nome_equip=:nome_equip,
        quantidade=:quantidade,
        estado=:estado,
        id_categoria=:id_categoria
    WHERE id_equipamento=:id_equipamento
    ");

    $sentencia->bindParam(":nome_equip", $nome_equip);
    $sentencia->bindParam(":quantidade", $quantidade);
    $sentencia->bindParam(":estado", $estado);
    $sentencia->bindParam(":id_categoria", $id_categoria);
    $sentencia->bindParam(":id_equipamento",$txtID);

    $sentencia->execute();

    $foto_equip= (isset($_FILES["foto_equip"]['name'])?$_FILES["foto_equip"]['name']:"");

    $data_ =new DateTime();

    $nomeficheiro_foto=($foto_equip != '') ? $data_->getTimestamp() . "_" . $_FILES["foto_equip"]['name'] : "";
    $tmp_foto=$_FILES["foto_equip"]['tmp_name'];
    if($tmp_foto!=''){
        move_uploaded_file($tmp_foto,"./".$nomeficheiro_foto);
        $sentencia=$conexion->prepare("SELECT foto_equip FROM `tbl_equipamento` WHERE id_equipamento=:id_equipamento");
        $sentencia->bindParam(":id_equipamento",$txtID);
        $sentencia->execute();

        $registo_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);

        if(isset($registo_recuperado["foto_equip"]) && $registo_recuperado["foto_equip"]!=""){
            if(file_exists("./".$registo_recuperado["foto_equip"])){
                unlink("./".$registo_recuperado["foto_equip"]);
            }
        }   
        $sentencia=$conexion->prepare("UPDATE `tbl_equipamento` SET foto_equip=:foto_equip WHERE id_equipamento=:id_equipamento");
        $sentencia->bindParam(":foto_equip",$nomeficheiro_foto);
        $sentencia->bindParam(":id_equipamento",$txtID);
        $sentencia->execute();
    }

    $sentencia->bindParam(":foto_equip", $nomeficheiro_foto);
        header("Location: index.php");
         exit();

}

$sentencia=$conexion->prepare("SELECT * FROM `tbl_categoria`");
$sentencia->execute();
$lista_cat=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>



<?php include("../../template/header.php");?>

<div class="card">
    <div class="card-header"> Atualizar Equipamento</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

        <div class="mb-3">
                <label for="" class="form-label">ID</label>
                <input
                    type="text"
                    value="<?php echo $txtID;?>"
                    class="form-control"
                    readonly
                    name="txtID"
                    id="txtID"
                    aria-describedby="helpId"
                    placeholder=""
                />
            </div>

         <div class="mb-3">
                <label for="" class="form-label"> Imagem do Equipamento</label>
                <img width="70" 
                            src="<?php echo $registo['foto_equip']?>"
                            class="img-fluid rounded-top"
                            alt="" />
                <input
                    type="file"
                    class="form-control"
                    name="foto_equip"
                    id="foto_equip"
                    aria-describedby="helpId"
                    placeholder="Foto"
                />
            </div>

        <div class="mb-3">
            <label for="nome_equip" class="form-label">Nome do Equipamento</label>
            <input
                type="text"
                value="<?php echo $nome_equip;?>"
                class="form-control"
                name="nome_equip"
                id="nome_equip"
                aria-describedby="helpId"
                placeholder="Nome do Equipamento"
            />
        </div>

       

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input
                type="number"
                value="<?php echo $quantidade;?>"
                class="form-control"
                name="quantidade"
                id="quantidade"
                aria-describedby="helpId"
                placeholder="Quantidade"
            />
        </div>

        <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select
                    class="form-select form-select-sm"
                    name="estado"
                    id="estado"
                >
                    <option selected>Disponível</option>
                    <option>Indisponível </option>
                </select>
            </div>

       
        

        <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoria</label>
                <select
                    class="form-select form-select-sm"
                    name="id_categoria"
                    id="id_categoria"
                >
                    <option selected>Select one</option>
                    <?php foreach($lista_cat as $registo){ ?>
                    <option value="<?php echo $registo['id_categoria']?>">
                    <?php echo $registo['nome_cat']?>
                    </option>
                    <?php }?>  
                </select>
            </div>




        <button
            type="submit"
            class="btn btn-success"
        >
            Atualizar Equipamento
        </button>
        <a
            name=""
            id=""
            class="btn btn-danger"
            href="index.php"
            role="button"
            >Cancelar</a
        >
     </form>    
        
        

    </div>
    
</div>


<?php include("../../template/footer.php");?>