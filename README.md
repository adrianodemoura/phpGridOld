phpGrid
==========

Php com OO, MVC, Auxiliares, PDO, jQuery, Bootstrap e tabelas.



<b>1 - Requerimentos:</b><br />
Servidor Web Apache versão 2 ou superior, com módulo rewrite habilitado.<br />
Php versão 5 ou superior, mysql ou mariadb com versão 5 ou superior e ainda Bootstrap e jQjuery já inclusos no CORE.

<b>2 - Instalação:</b><br />
Copie o código para o diretório do seu servidor Apache.

Corrija o seguintes arquivos:<br />
* <b>Config/database_default.php</b> PARA <b>Config/database.php</b><br />
Abra o arquivo e altere o seu banco de dados, caso tenha dificuldades, peça ajuda ao Administrador do Banco de Dados.

* Lembre-se do e-mail e senha do usuário padrão:<br />
email: admin@phpgrid.com <br />
senha: 1234567


<b>3 - Considerações:</b><br />
O phpGrid tem a estrutura e fluxo de requisições do cakePHP, mas a camada de visão foi implementada com o <b>BootStrap</b> e <b>jQuery</b>.
Se quiser criar um arquivo JS ou CSS para sua action, crie os arquivos dentro do diretório webroot, como no exemplo abaixo:

APP/webroot/js/module_controller_action.js<br />
APP/webroot/css/module_controller_action.css

* troque <b>module</b> pelo nome do módulo, <b>controller</b> pelo nome do controller e <b>action</b> pelo nome da action.

dentro da view execute:
<?php $this->head('css','action'); ?>

<b>4 - Demonstrativo:</b><br />
O demonstrativo pode ser acessado em http://www.deskfacil.com/phpgrid


Se encontrar alguma dificuldade LEIA O CÓDIGO, se ainda assim continuar com dificuldades, DESISTA você é burro, brincadeirinha pode me contactar pelo e-mail adrianodemoura@gmail.com
