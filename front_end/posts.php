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
                response.foreach(post =>{
                    let row = document.createElement('div');
                    row.classList.add('row');
                    row.setAttribute('id', post.CodFun);
                    row.setAttribute('onclick', 'alert("Postagem ' + post.Nome + ' selecionado de id ' + fun.CodFun + '")');
                })

                

            })

         }
    </script>
</main>


<?php include '../front_end/assets/footer.php'; ?>