## PHP Challenge 20201117
- Projeto desenvolvido para o PHP Challenge 20201117

### Preparando Back-end
- Inserir no terminal

    `composer install && npm i && npm run prod`
 
    > Gerar chave aplicação `php artisan key:generate`

- Configurar banco de dados no .env e rodar as migrations:

    `php artisan migrate`

---
### Utilizar o Projeto
- Para inciar o projeto, iniciamos o servidor com:
 
    `php artisan serve`


- A rota `/` se refere ao endpoint com a mensagem de "PHP Challenge 20201117". A raiz de nosso projeto esta em `/home`
- Para algumas rotas funcinarem é necessário ter a chave da API, foi deixado um input para ser possivel relizar testes pelo usuário.
- Ao gerar a chave é e inserir no input torna-se possível enviar o arquivo de project.json que contém os produtos, além de editar e deletar o mesmo.
- Para a documentação da API a rota encontra-se em:`/api/documentation`
