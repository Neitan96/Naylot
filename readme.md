# Naylot

Naylot é um ORM feito em PHP. Criado para ser simples e fácil, com ele você pode rapidamente criar relações de objetos com banco de dados.

## Instalação

```sh
composer require neitan96/naylot
```

## Estrutura

* Naylot/Builder
    Nesse diretório fica as classes para a interface da API e serve como base para os Compilers.
    
* Naylot/Compilers
    Nesse diretório fica as classes que transforma o builder em SQL.
    
* Naylot/Components
    Nesse diretório fica as classes base para o projeto.
    
* Naylot/Helpers
    Aqui fica todos os helpers do projeto.