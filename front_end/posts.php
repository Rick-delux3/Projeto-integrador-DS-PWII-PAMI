<?php include '../front_end/assets/header.php'; ?>

<main >
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8 col-md-8 col-lg-6" id="lista">
                <div class="text-center mt-5" id="loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const UrlListar = 'http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/listar_posts.php';
    const lista = document.getElementById("lista");
    const loading = document.getElementById("loading");

    function getPosts() {
        // Mostra loading
        loading.style.display = 'block';

        fetch(UrlListar, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(r => r.json())
            .then(response => {
                // Limpa o container e remove o loading
                lista.innerHTML = "";
                
                // Verifica se existem posts
                if (!response.posts || response.posts.length === 0) {
                    lista.innerHTML = "<p class='text-center text-muted'>Nenhum post encontrado.</p>";
                    return;
                }

                response.posts.forEach(post => {
                    // 1. Criação do Card (Container principal do post)
                    let card = document.createElement('div');
                    card.classList.add('card', 'shadow-sm', 'mb-4'); // mb-4 dá o espaço entre um post e outro

                    // 2. Header do Card (Avatar e Nome)
                    let cardHeader = document.createElement('div');
                    cardHeader.classList.add('card-header', 'bg-white', 'd-flex', 'align-items-center', 'border-0', 'pt-3');

                    let avatar = document.createElement("img");
                    avatar.src = "../front_end/assets/imagens/account.png"; // Avatar padrão
                    avatar.classList.add("rounded-circle", "me-2", "border");
                    avatar.width = 40;
                    avatar.height = 40;
                    avatar.style.objectFit = "cover";

                    let username = document.createElement("span");
                    username.classList.add("fw-bold", "text-dark");
                    username.innerText = post.username || "Usuário";

                    cardHeader.appendChild(avatar);
                    cardHeader.appendChild(username);

                    // 3. Imagem do Post (Inserir antes do corpo se existir)
                    let imgContainer = null;
                    if (post.thumb) {
                        imgContainer = document.createElement('img');
                        imgContainer.src = post.thumb;
                        imgContainer.classList.add('card-img-top', 'rounded-0');
                        imgContainer.style.maxHeight = "500px";
                        imgContainer.style.objectFit = "cover";
                    }

                    // 4. Corpo do Card (Título e Conteúdo)
                    let cardBody = document.createElement('div');
                    cardBody.classList.add('card-body');

                    let cardTitle = document.createElement('h5');
                    cardTitle.classList.add('card-title', 'fw-bold');
                    cardTitle.innerText = post.titulo;

                    let cardText = document.createElement('p');
                    cardText.classList.add('card-text');
                    cardText.innerText = post.conteudo;

                    // Data
                    let cardData = document.createElement('p');
                    cardData.classList.add('card-text', 'mt-3');
                    let smallData = document.createElement('small');
                    smallData.classList.add('text-muted');
                    smallData.innerText = `Postado em ${post.data}`;
                    
                    cardData.appendChild(smallData);

                    // Montagem do Corpo
                    cardBody.appendChild(cardTitle);
                    cardBody.appendChild(cardText);
                    cardBody.appendChild(cardData);

                    // 5. Montagem Final do Card
                    card.appendChild(cardHeader);
                    
                    // Se tiver imagem, adiciona entre o header e o body (estilo Instagram)
                    // Ou use card.insertBefore(imgContainer, cardBody) se preferir
                    if (imgContainer) {
                        card.appendChild(imgContainer);
                    }

                    card.appendChild(cardBody);

                    // Adiciona o card à lista principal
                    lista.appendChild(card);
                });
            })
            .catch(erro => {
                console.error("Erro ao buscar posts:", erro);
                lista.innerHTML = "<div class='alert alert-danger'>Erro ao carregar o feed.</div>";
            })
            .finally(() => {
                // Garante que o loading suma
                if(loading) loading.style.display = 'none';
            });
    }

    // Inicializa
    getPosts();
</script>

<?php include '../front_end/assets/footer.php'; ?>