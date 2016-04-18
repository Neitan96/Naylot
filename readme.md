# Naylot

Naylot é um ORM feito em PHP. Criado para ser simples e fácil, com ele você pode rapidamente criar relações de objectos com banco de dados rapidamente.

## Instalação

```sh
composer require nathanalmeida/naylot
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