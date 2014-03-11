phpGrid
==========

Php com OO, MVC, Auxiliares, jQuery, Bootstrap e listas.

1 - Considerações

o phpGrid tem a estrutura e fluxo de requisições do cakePHP, mas a camada de visão foi implementada com o BootStrap e jQuery.
Se quiser criar um arquivo JS ou CSS para sua action, crie os arquivos dentro do diretório webroot, como no exemplo abaixo:

APP/webroot/js/module_controller_action.js

APP/webroot/css/module_controller_action.css

dentro da view execute:

<?php $this->head('css','action'); ?>

* troque module pelo nome do módulo, controller pelo nome do controller e action pelo nome da action.

1 - Requerimentos:

Servidor Web Apache versão 2 ou superior, com módulo rewrite habilitado.

Php versão 5 ou superior

Bootstrap e jQjuery já inclusos no CORE.

2 - Instalação:

Copie o código para o diretório do seu servidor Apache.

Corrija o seguintes arquivos:

* <b>Config/database_default.php</b> PARA <b>Config/database.php</b><br />
Abra o arquivo e configure o seu banco de dados, caso tenha dificuldades, peça ajuda ao Administrador do Banco de Dados.


* <b>webroot/index_default.php</b> PARA <b>webroot/index.php</b><br />
Abra o arquivo e configura as opções do ambiente, caso tenha dificuldades, peça ajuda ao Administrador do Servidor.


* Lembre-se do e-mail e senha do usuário padrão:

email: admin@admin.com.br <br />
senha: admin

