#!/bin/bash
# Este script executa a atualizção no servidor de produção, neste caso o servidor é deskfacil.com
# é preciso que a máquina local, tenha acesso ao servidor sem a necessidade de digitar senha
# também é importante que no servidor tenha o script phpgridPull.sh, com o seguinte conteúdo
# #!/bin/bash
# cd /var/www/phpgrid
# git pull
# uma vez que o diretório "/var/www/phpgrid" é o diretório aonde está o projeto.
#

ssh root@deskfacil.com /root/phpgridPull.sh

