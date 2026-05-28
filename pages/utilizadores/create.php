<?php include("../../template/header.php");?>

<div class="card">
    <div class="card-header">Registar Utilizador</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="utilizador" class="form-label">Nome de Utilizador</label>
            <input
                type="text"
                class="form-control"
                name="utilizador"
                id="utilizador"
                aria-describedby="helpId"
                placeholder="Nome de Utilizador"
            />
        </div>

        <div class="mb-3">
            <label for="password_user" class="form-label">Password</label>
            <input
                type="text"
                class="form-control"
                name="password_user"
                id="password_user"
                aria-describedby="helpId"
                placeholder="Password"
            />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                type="text"
                class="form-control"
                name="email"
                id="email"
                aria-describedby="helpId"
                placeholder="Email"
            />
        </div>
        <button
            type="submit"
            class="btn btn-success"
        >
            Adicionar Utilizador
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