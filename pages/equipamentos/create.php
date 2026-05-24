<?php include("../../template/header.php");?>

<div class="card">
    <div class="card-header">Equipamento</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Equipamento</label>
            <input
                type="text"
                class="form-control"
                name="nome"
                id="nome"
                aria-describedby="helpId"
                placeholder="Nome do Equipamento"
            />
        </div>

         <div class="mb-3">
            <label for="nome" class="form-label">Estado</label>
            <input
                type="text"
                class="form-control"
                name="nome"
                id="nome"
                aria-describedby="helpId"
                placeholder="Nome do Equipamento"
            />
        </div>

       

        <div class="mb-3">
            <label for="email" class="form-label">Quantidade</label>
            <input
                type="number"
                class="form-control"
                name="quantidade"
                id="quantidade"
                aria-describedby="helpId"
                placeholder="Quantidade"
            />
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