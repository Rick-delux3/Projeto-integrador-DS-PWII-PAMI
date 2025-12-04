<?php
    include 'Api/cors.php';
    include 'Api/conexao.php';
    include '../front_end/assets/header.php';
?>

<?php
    session_start();
    
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }



    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $titulo   = $connection->real_escape_string($_POST['titulo']);
        $conteudo = $connection->real_escape_string($_POST['conteudo']);
        $iduser   = $_SESSION['id']; // pega ID do usuário logado

        // Thumb (caso tenha upload)
        $thumb = "";

        if (!empty($_FILES['thumb']['name'])) {
            $nome_arquivo = time() . "_" . basename($_FILES["thumb"]["name"]);
            $destino = "../front_end/assets/imagens/" . $nome_arquivo;

            move_uploaded_file($_FILES["thumb"]["tmp_name"], $destino);

            $thumb = $destino;
        } else {
            $thumb = "..front_end/assets/imagens/default.png"; // imagem padrão
        }

        // INSERE NO BANCO
        $sql = "INSERT INTO posts (titulo, conteudo, thumb, iduser)  VALUES ('$titulo', '$conteudo', '$thumb', $iduser)";

        if ($connection->query($sql) === TRUE) {
            echo "<script>alert('Post criado!'); window.location='admin.php';</script>";
            exit;
        } else {
            echo "<script>alert('Erro ao criar post: " . $connection->error . "');</script>";
        }
    }
?>

<main class="container">

    <h2 class="mb-4">Gerenciar Posts</h2>

    <button id="abrir-modal">Adicionar post</button>

    <div id="lista" class="row d-flex flex-column"></div>

    <div>
        <div id="modal"  class="hidden">
            <div class="modal-content p-4 bg-white shadow rounded">

                <h4 id="titulo-modal">Novo Post</h4>

                <form id="form-modal" method="POST" enctype="multipart/form-data">

                    <input type="hidden" id="id_post" name="id">
    
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="titulo" required>
        
                    <label for="conteudo">Conteúdo:</label>
                    <input type="text" name="conteudo" id="conteudo" required>
        
                    <label for="thumb">Thumb:</label>
                    <input type="file" name="thumb" id="thumb">
        
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="button" id="cancelar-modal" class="btn btn-secondary me-2">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>  
                </form>
            </div>
            
        </div>
    </div>
</main>

<script>

    const UrlListar = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/listar_post.php';
    const UrlRemover = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/remover_post_por_id.php';
    const UrlEditar = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/editar_post_por_id.php';

         function getPosts(){
                isLoading = true;

                


            fetch(UrlListar,
                {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                }
            )
            .then(r => r.json())
            .then(response => {

                const lista = document.getElementById("lista");
                lista.innerHTML = "";

                response.posts.forEach(post =>{ 
                    let col = document.createElement('div');
                    col.classList.add('col-md-4');

                    let card = document.createElement('div');
                    card.classList.add('card', 'shadow');
                    
                    let cardheader = document.createElement('div');
                    cardheader.classList.add('card-header', 'd-flex', "align-items-center");
                    cardheader.innerText = "Usuario";

                    let avatar = document.createElement("img");
                    avatar.src = "../front_end/assets/imagens/account.png";
                    avatar.classList.add("rounded-circle", "me-2");
                    avatar.width = 40;

                    let username = document.createElement("span");
                    username.innerText = post.username;

                    cardheader.appendChild(avatar);
                    cardheader.appendChild(username);
                    
                    //corpo
                    let cardbody = document.createElement('div');
                    cardbody.classList.add('card-body');
                    
                    let cardtitle =  document.createElement('h5');
                    cardtitle.classList.add('card-title');
                    cardtitle.innerText = post.titulo;

                    let cardtext = document.createElement('p');
                    cardtext.classList.add('card-text');
                    cardtext.innerText = post.conteudo;

                    let carddata = document.createElement('p');
                    carddata.classList.add('card-text');
                    let smalldata = document.createElement('small');
                    smalldata.classList.add('text-body-secondary');
                    smalldata.innerText = `Postado em ${post.data}`;

                    if(post.thumb){
                        let img = document.createElement('img');
                        img.src = post.thumb;
                        img.classList.add('card-img-top');
                        card.appendChild(img);
                    }

                    //botões

                    let DivBtn = document.createElement("div");
                    DivBtn.classList.add("mt-2");

                    let btnEdit = document.createElement('button');
                    btnEdit.classList.add('btn', 'btn-warning', 'me-2');
                    btnEdit.innerText = "Editar";
                    btnEdit.onclick = () => editarPost(post);

                    let btnDelete = document.createElement('button');
                    btnDelete.classList.add('btn', 'btn-danger');
                    btnDelete.innerText = "Excluir";
                    btnDelete.onclick = () => excluirPost(post.id);

                    DivBtn.appendChild(btnEdit);
                    DivBtn.appendChild(btnDelete);

                    lista.appendChild(col);
                    col.appendChild(card);

                    card.appendChild(cardheader);
                    card.appendChild(cardbody);

                    cardbody.appendChild(cardtitle);
                    cardbody.appendChild(cardtext);
                    cardbody.appendChild(carddata);
                    carddata.appendChild(smalldata);
                    cardbody.appendChild(DivBtn);
                





                });
            })
            .catch(erro => {
                console.log(erro);
            })
            .finally(()=>{
                isLoading = false;
            })

         }
        getPosts();


        //Excluir Post

        function excluirPost(id) {
                if (!confirm("Deseja realmente excluir este post?")) return;

                fetch(UrlRemover + "?id=" + id)
                .then(() => getPosts());
        }

        function editarPost(post) {
            document.querySelector("#titulo-modal").innerText = "Editar Post";

            document.querySelector("#id_post").value = post.id;
            document.querySelector("#titulo").value = post.titulo;
            document.querySelector("#conteudo").value = post.conteudo;

            modal.classList.remove("hidden");
}

</script>

<script>

    //ModalController
    const abrir_modal = document.querySelector("#abrir-modal");
    const cancelar_modal = document.querySelector("#cancelar-modal");
    const modal = document.querySelector("#modal");

    abrir_modal.addEventListener("click", () => {
        document.querySelector("#titulo-modal").innerText = "Novo Post";
        document.querySelector('#form-modal').reset()
        modal.classList.remove("hidden");
    });

    cancelar_modal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });



</script>



<?php include '../front_end/assets/footer.php'; ?>
