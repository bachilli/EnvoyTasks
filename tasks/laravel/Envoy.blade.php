@servers(['localhost' => ['localhost']])

@setup
    $release = date('Ymdhis');
    $home = '/home/bachilli/www';
    $branch = 'master';
@endsetup

@story('deploy')
    git
    composer
    symlinks
    permissions
@endstory

@task('git')
    echo "Efetuando deploy de aplicação Laravel para o domínio {{ $domain }}"
    git clone {{ $gitUrl }} {{ $home }}/{{ $domain }}/releases/{{ $release }} --branch={{ $branch }}
@endtask

@task('composer')
    cd {{ $home }}/{{ $domain }}/releases/{{ $release }}
    composer install
@endtask

@task('symlinks')
    rm {{ $home }}/{{ $domain }}/current
    ln -s {{ $home }}/{{ $domain }}/releases/{{ $release }} {{ $home }}/{{ $domain }}/current
    rm -rf {{ $home }}/{{ $domain }}/releases/{{ $release }}/storage
    ln -s {{ $home }}/{{ $domain }}/storage {{ $home }}/{{ $domain }}/releases/{{ $release }}/storage
    ln -s {{ $home }}/{{ $domain }}/.env {{ $home }}/{{ $domain }}/releases/{{ $release }}/.env
@endtask

@task('permissions')
    echo "Alterando permissões"
    chgrp -R www-data {{ $home }}/{{ $domain }}/releases/{{ $release }}
    chmod -R ug+rwx {{ $home }}/{{ $domain }}/releases/{{ $release }}/bootstrap/cache
@endtask