<?php include '../front_end/assets/header.php'; ?>


<main>
    <div id="lista" class="container">
        
    </div>
    <script>
        let lista = document.getElementById("lista");

        let posts = {};

         function getPosts(){
                isLoading = true;

            fetch('http://localhost/Projeto-integrador-DS-PWII-PAMI/back_end/Api/listar_post.php',
                {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                }
            )
            .then(response => response.json())
            .then(response => {
                response.posts.foreach(post =>{
                    let row = document.createElement('div');
                    row.classList.add('row');
                    row.classList.add('d-flex');
                    row.classList.add('flex-column');
                    row.setAttribute('id', post.CodFun);

                    let card = document.createElement('div');
                    card.classList.add('card');
                    
                    let cardheader = document.createElement('div');
                    cardheader.classList.add('card-header');
                    cardheader.innerText = "Usuario";

                    let cardbody = document.createElement('div');
                    cardbody.classList.add('card-body');
                    
                    let cardtitle =  document.createElement('h5');
                    cardtitle.classList.add('card-title');
                    cardtitle.innerText = post.titulo;

                    let cardtext = document.createElement('p');
                    cardtext.classList.add('card-text');
                    cardtext.innerText = post.conteudo;

                    let carddata = document.createElement('p');
                    carddata.classList('card-text');
                    let smalldata = document.createElement('small');
                    smalldata.classList.add('text-body-secondary');
                    smalldata.innerText = `Postado em ${post.data}`;

                    let thumb = document.createElement('img');
                    thumb.classList.add('card-img-bottom');
                    thumb.alt = "Thumb";
                    thumb.src = post.thumb;


                    row.appendChild(card);
                    card.appendChild(cardheader);
                    card.appendChild(cardbody);
                    cardbody.appendChild(cardtitle);
                    cardbody.appendChild(cardtext);
                    cardbody.appendChild(carddata);
                    carddata.appendChild(smalldata);





                });
            })
            .catch(erro => {
                console.log(erro);
            })
            .finally(()=>{
                isLoading = false;
            })

         }
    </script>
</main>


<?php include '../front_end/assets/footer.php'; ?>