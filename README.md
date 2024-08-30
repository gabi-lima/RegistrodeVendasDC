## Como testar:

- Verifique as dependências node
- npm init
- npm install
- Com o apache e MySQL online:
    - execute os migrations
    - acesse o /register e crie um usuário
    - realize o login em /login/
    - cadastre cliente e produto
    - gere uma venda
    - verifique as vendas geradas no /orders/{id} (via navi bar o id de usuário vai automaticamente)
    - cada venda, cliente e produto deve aparecer somente para o usuário em especifico.
