# Sistema Zer@Dengue

  > Sistema de cadastro de denúncias de focos de dengue em todo o Brasil relacionada ao exercício de portifólio do 5º semestre do curso de ADS da UNOPAR

![Logo](https://user-images.githubusercontent.com/47118693/82142964-b7e28680-9816-11ea-89c7-ed0b6e1d0d6e.jpg)

- [Sistema Zer@Dengue](#sistema-zerdengue)
  - [Visão Geral](#vis%c3%a3o-geral)
  - [Funcionalidades](#funcionalidades)
  - [Tecnologias Utilizadas](#tecnologias-utilizadas)


## Visão Geral
Aplicação web na qual qualquer pessoa poderá fazer uma denúncia de possíveis focos de dengue em todo território nacional.  Bastará acessar o sistema Zer@Dengue, criar uma conta e fazer um descritivo do problema, indicando o endereço da ocorrência, inclusive podendo enviar fotos. 
  
  > Como se trata de um trabalho para portifólio, este sistema é apenas demonstrativo e não possui qualquer relação com o ministério da saúde e outras entidades governamentais.

## Funcionalidades
  O sistema possui um cadastro de usuário, onde qualquer pessoa pode se cadastrar. Nele o usuário informa suas informações pessoais, de endereço e de contato.
  > Atenção: **Não** forneça informações suas informações pessoais no sistema! Ele é meramente demonstrativo.

Ao se cadastrar, o usuário pode usufruir das funcionalidades principais do sistema que são o cadastro e a consulta de denúncias. No cadastro o usuário pode inserir até 10 fotos do local onde ele identificou os focos de dengue, além de poder inserir o endereço do local, tendo um mapa e um campo de texto com sugestões de endereço como auxílio. Na consulta, ele pode preencher um campo de pesquisa e definir filtros para uma busca mais específica.

O usuário também pode comentar em suas denúncias, mantendo o "contato" com o "Ministério da Saúde" e se informando sobre o andamento do caso. 

Por fim, o usuário pode alterar alguns de seus dados de cadastro como nome e informações de endereço e contato.

## Tecnologias Utilizadas

A linguagem utilizada no sistema foi o PHP, uma linguagem com ampla utilização na web há muito tempo. Apesar de ser uma linguagem bem simples mas que fornece mecanismos para a criação de aplicações de diferentes níveis de complexidade.

Nenhum framework foi utilizado, mas muitas bibliotecas auxiliaram o desenvolvimento do sistema. São elas:

- [Simple Router](https://github.com/skipperbent/simple-php-router)
- [JWT](https://github.com/lcobucci/jwt)
- [Guzzle](https://github.com/guzzle/guzzle)
- [Twig](https://github.com/twigphp/Twig)

O banco de dados utilizado na persistência de dados foi o MariaDB, banco Open Source e que tem ótima integração com o PHP. A comunicação entre ambas foi feita utilizando o PDO, uma interface que especifica as principais funcionalidades que o SGBD precisa disponibilizar e dessa maneira permite a utilização de diversos SGBDs que a implementam, com um desses sendo o MariaDB.

O mecanismo utilizado na autenticação dos usuário foi o OAuth2, com a emissão de um token JWT que identifica o usuário que está realizando a solicitação a API.
