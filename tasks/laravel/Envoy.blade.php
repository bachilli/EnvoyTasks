@servers(['localhost' => ['localhost']])

@setup
    $release = date('Ymdhis');
    $home = '/home/bachilli';
    $branch = 'master';
@endsetup

@task('git')
    echo "Efetuando deploy de aplicação Laravel para o domínio {{ $domain }}"
    cd {{ $home }}/{{ $domain }}/releases
    git clone {{ $gitUrl }} {{ $release }} --branch={{ $branch }}
@endtask

@task('composer')
    cd {{ $home }}/{{ $domain }}/releases/{{ $release }}
    composer install
@endtask

@task('symlinks')
    ln -s {{ $home }}/{{ $domain }}/releases/{{ $release }} {{ $home }}/{{ $domain }}/current
    rm -rf {{ $home }}/{{ $domain }}/releases/{{ $release }}/storage
    sudo ln -s {{ $home }}/{{ $domain }}/storage {{ $home }}/{{ $domain }}/releases/storage
    sudo ln -s {{ $home }}/{{ $domain }}/.env {{ $home }}/{{ $domain }}/releases/{{ $release }}/.env
@endtask

@task('permissions')
    echo "Alterando permissões"
    sudo chown www-data:www-data {{ $home }}/{{ $domain }}/releases/{{ $release }} -R
    sudo chmod -R ug+rwx {{ $home }}/{{ $domain }}/releases/{{ $release }}/bootstrap/cache
@endtask