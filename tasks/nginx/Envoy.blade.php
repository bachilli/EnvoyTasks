@servers(['localhost' => ['localhost']])

@setup
    $home = '/home/bachilli/www';
@endsetup

@task('create-ssl-vhost')
    echo "Criando diretório"
    mkdir {{ $home }}/{{ $domain }}
    mkdir {{ $home }}/{{ $domain }}/storage
    mkdir {{ $home }}/{{ $domain }}/releases

    echo "Alterando permissões"
    chgrp -R www-data {{ $home }}/{{ $domain }}
    chmod -R ug+rwx {{ $home }}/{{ $domain }}/storage

    echo "Criando arquivo de configuração para o domínio {{ $domain }}"
    sudo cp ssl-vhost /etc/nginx/sites-available/{{ $domain }}
    sudo sed -i -e 's/DOMAIN.COM/{{ $domain }}/g' /etc/nginx/sites-available/{{ $domain }}
    sudo ln -s /etc/nginx/sites-available/{{ $domain }} /etc/nginx/sites-enabled/{{ $domain }}

    echo "Criando certificados SSL para o domínio {{ $domain }}"
    sudo certbot --nginx certonly -d www.{{ $domain }}

    echo "Ativando certificados SSL para o domínio {{ $domain }}"
    sudo sed -i -e 's/#UNCOMMENT//g' /etc/nginx/sites-available/{{ $domain }}

    echo "Reiniciando nginx"
    sudo systemctl restart nginx
@endtask
