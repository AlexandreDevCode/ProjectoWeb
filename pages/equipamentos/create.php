<?php
include("../../db.php"); 
if($_POST){
    print_r($_POST);
    print_r($_FILES);
    $foto_equip= (isset($_FILES["foto_equip"]['name'])?$_FILES["foto_equip"]['name']:"");
    $nome_equip= (isset($_POST["nome_equip"])?$_POST["nome_equip"]:"");
    $quantidade= (isset($_POST["quantidade"])?$_POST["quantidade"]:"");
    $estado= (isset($_POST["estado"])?$_POST["estado"]:"");
    $id_categoria= (isset($_POST["id_categoria"])?$_POST["id_categoria"]:"");

    $sentencia=$conexion->prepare(" INSERT INTO
      tbl_equipamento (id_equipamento, foto_equip, nome_equip,
      quantidade, estado, id_categoria) VALUES (NULL, :foto_equip,
      :nome_equip, :quantidade, :estado, :id_categoria)");
       $data_=new DateTime();

    $nomeficheiro_foto=($foto_equip!='')?$data_->getTimestamp()."_".$_FILES["foto_equip"]['name']:"";
    $tmp_foto=$_FILES["foto_equip"]['tmp_name'];
    if($tmp_foto!=''){
      move_uploaded_file($tmp_foto,"./".$nomeficheiro_foto);
     } 
    $sentencia->bindParam(":foto_equip", $nomeficheiro_foto);
    $sentencia->bindParam(":nome_equip",$nome_equip);
    $sentencia->bindParam(":quantidade",$quantidade);
    $sentencia->bindParam(":estado",$estado);
    $sentencia->bindParam(":id_categoria",$id_categoria);
    $sentencia->execute();
    header("Location:index.php");
}

$sentencia=$conexion->prepare("SELECT * FROM tbl_categoria");
$sentencia->execute();
$lista_cat=$sentencia->fetchAll(PDO::FETCH_ASSOC);


?>


<?php include("../../template/header.php");?>

<div class="card">
    <div class="card-header"> Registar Equipamento</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
         <div class="mb-3">
                <label for="" class="form-label"> Imagem do Equipamento</label>
                <input
                    type="file"
                    class="form-control"
                    name="foto_equip"
                    id="foto_equip"
                    aria-describedby="helpId"
                    placeholder="foto_equip"
                />
            </div>

        <div class="mb-3">
            <label for="nome_equip" class="form-label">Nome do Equipamento</label>
            <input
                type="text"
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
            Adicionar Equipamento
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