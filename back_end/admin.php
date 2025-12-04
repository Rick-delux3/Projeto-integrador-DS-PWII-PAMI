<?php
    session_start();

    include 'Api/cors.php';
    include 'Api/conexao.php';
    include '../front_end/assets/header.php';
?>

<?php
    
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
            $thumb = "../front_end/assets/imagens/default.png"; // imagem padrão
        }

        // INSERE NO BANCO
        $sql = "INSERT INTO posts (titulo, conteudo, thumb, iduser)  VALUES ('$titulo', '$conteudo', '$thumb', $iduser)";

        if ($connection->query($sql) === TRUE) {
            echo "<script>alert('Post criado!'); window.location='admin.php';</script>";
            exit;
        } else {
            die("Erro no INSERT: " . $connection->error);
        }
    }
?>

<main>
    <div class="container">
        
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-8 col-lg-6 d-grid">
                <button id="abrir-modal" class="btn btn-primary btn-lg shadow-sm">
                    <span style="font-size:20px;">+</span> Novo Post
                </button>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6" id="lista">
                <div class="text-center mt-5" id="loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div id="modal" class="hidden">
                <div class="modal-content p-4 bg-white shadow rounded">

                    <h4 id="titulo-modal" class="mb-3">Novo Post</h4>

                    <form id="form-modal" enctype="multipart/form-data" method="POST">

                        <input type="hidden" id="id_post" name="id">
    
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" name="titulo" id="titulo" class="form-control mb-2" required>
        
                        <label for="conteudo" class="form-label">Conteúdo:</label>
                        <textarea name="conteudo" id="conteudo" class="form-control mb-2" rows="4" required></textarea>
        
                        <label for="thumb" class="form-label">Thumb:</label>
                        <input type="file" name="thumb" id="thumb" class="form-control mb-3">
        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="cancelar-modal" class="btn btn-secondary">Cancelar</button>
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


<script>

    const UrlListar = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/listar_posts.php';
    const UrlRemover = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/remover_post_por_id.php';
    const UrlEditar = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/editar_post_por_id.php';
    
    const lista = document.getElementById("lista");
    const loading = document.getElementById("loading");

    function getPosts(){
        // Mostra loading
        if(loading) loading.style.display = 'block';

        fetch(UrlListar, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(r => r.json())
            .then(response => {

                lista.innerHTML = "";

                // Verifica se existem posts
                if (!response.posts || response.posts.length === 0) {
                    lista.innerHTML = "<p class='text-center text-muted'>Nenhum post encontrado.</p>";
                    return;
                }

                response.posts.forEach(post => { 
                    // 1. Criação do Card (Container principal) - Igual ao posts.php
                    let card = document.createElement('div');
                    card.classList.add('card', 'shadow-sm', 'mb-4');

                    // 2. Header do Card (Avatar e Nome)
                    let cardHeader = document.createElement('div');
                    cardHeader.classList.add('card-header', 'bg-white', 'd-flex', 'align-items-center', 'border-0', 'pt-3');

                    let avatar = document.createElement("img");
                    avatar.src = "../front_end/assets/imagens/account.png";
                    avatar.classList.add("rounded-circle", "me-2", "border");
                    avatar.width = 40;
                    avatar.height = 40;
                    avatar.style.objectFit = "cover";

                    let username = document.createElement("span");
                    username.classList.add("fw-bold", "text-dark");
                    username.innerText = post.username || "Usuário";

                    cardHeader.appendChild(avatar);
                    cardHeader.appendChild(username);

                    // 3. Imagem do Post (Se houver)
                    let imgContainer = null;
                    if (post.thumb) {
                        imgContainer = document.createElement('img');
                        imgContainer.src = post.thumb;
                        imgContainer.classList.add('card-img-top', 'rounded-0');
                        imgContainer.style.maxHeight = "500px";
                        imgContainer.style.objectFit = "cover";
                    }
                    
                    // 4. Corpo do Card
                    let cardBody = document.createElement('div');
                    cardBody.classList.add('card-body');
                    
                    let cardTitle =  document.createElement('h5');
                    cardTitle.classList.add('card-title', 'fw-bold');
                    cardTitle.innerText = post.titulo;

                    let cardText = document.createElement('p');
                    cardText.classList.add('card-text');
                    cardText.innerText = post.conteudo;

                    let cardData = document.createElement('p');
                    cardData.classList.add('card-text', 'mt-3');
                    let smallData = document.createElement('small');
                    smallData.classList.add('text-muted');
                    smallData.innerText = `Postado em ${post.data}`;
                    
                    cardData.appendChild(smallData);

                    // --- BOTÕES DE ADMINISTRAÇÃO (Diferença do posts.php) ---
                    let divBtn = document.createElement("div");
                    divBtn.classList.add("mt-3", "d-flex", "justify-content-end", "gap-2", "border-top", "pt-3");

                    let btnEdit = document.createElement('button');
                    btnEdit.classList.add('btn', 'btn-outline-warning', 'btn-sm');
                    btnEdit.innerHTML = '<i class="bi bi-pencil"></i> Editar'; // Se tiver ícones, senão texto puro
                    btnEdit.innerText = "Editar";
                    btnEdit.onclick = () => editarPost(post);

                    let btnDelete = document.createElement('button');
                    btnDelete.classList.add('btn', 'btn-outline-danger', 'btn-sm');
                    btnDelete.innerText = "Excluir";
                    btnDelete.onclick = () => excluirPost(post.id);

                    divBtn.appendChild(btnEdit);
                    divBtn.appendChild(btnDelete);

                    // Montagem do Corpo
                    cardBody.appendChild(cardTitle);
                    cardBody.appendChild(cardText);
                    cardBody.appendChild(cardData);
                    cardBody.appendChild(divBtn); // Adiciona botões ao corpo

                    // 5. Montagem Final
                    card.appendChild(cardHeader);
                    if(imgContainer) {
                        card.appendChild(imgContainer);
                    }
                    card.appendChild(cardBody);

                    // Adiciona o card diretamente na lista (não cria coluna col-md-4)
                    lista.appendChild(card);
                });
            })
            .catch(erro => {
                console.log(erro);
                lista.innerHTML = "<div class='alert alert-danger'>Erro ao carregar posts.</div>";
            })
            .finally(()=>{
                if(loading) loading.style.display = 'none';
            })
    }
    
    // Inicializa
    getPosts();

    // --- FUNÇÕES DE ADMINISTRAÇÃO (Mantidas) ---

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

    // Modal Controller
    const abrir_modal = document.querySelector("#abrir-modal");
    const cancelar_modal = document.querySelector("#cancelar-modal");
    const modal = document.querySelector("#modal");

    abrir_modal.addEventListener("click", () => {
        document.querySelector("#titulo-modal").innerText = "Novo Post";
        document.querySelector('#form-modal').reset();
        document.querySelector("#id_post").value = ""; // Limpa ID para garantir criação
        modal.classList.remove("hidden");
    });

    cancelar_modal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Form Submit
   document.querySelector("#form-modal").addEventListener("submit", function(e){
    e.preventDefault();

    const id = document.querySelector("#id_post").value;

    // SE FOR EDITAR
    if(id){

        let formData = new FormData();
        formData.append("id", id);
        formData.append("titulo", document.querySelector("#titulo").value);
        formData.append("conteudo", document.querySelector("#conteudo").value);
        formData.append("_method", "PUT"); // simula PUT

        const thumbFile = document.querySelector("#thumb").files[0];
        if(thumbFile){
            formData.append("thumb", thumbFile);
        }

        fetch(UrlEditar, {
            method: "POST",
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            alert("Post atualizado!");
            modal.classList.add("hidden");
            getPosts();
        });

    } 
    else {
        this.submit(); // criação normal (insert)
    }
    });

</script>

<?php include '../front_end/assets/footer.php'; ?>