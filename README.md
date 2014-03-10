Angú
==========

Php com OO, MVC, Auxiliares, jQuery, Bootstrap e polenta.

1 - Considerações

o Angu tem a estrutura e fluxo de requisições do cakePHP, mas a camada de visão foi implementada com o BootStrap e jQuery.
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

Copia o código para o diretório do seu servidor apache
.
email: admin@admin.com.br <br />
senha: admin



