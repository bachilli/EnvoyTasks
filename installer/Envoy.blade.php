@servers(['localhost' => ['localhost']])

@setup
    $home = '/home/bachilli/www';
@endsetup

@story('install')
    certbot
    configure-nginx
@endstory

@task('certbot')
    sudo apt-get update
    sudo apt-get install software-properties-common
    sudo add-apt-repository ppa:certbot/certbot
    sudo apt-get update
    sudo apt-get install python-certbot-nginx
@endtask

@task('configure-nginx')
    sudo rm /etc/nginx/fastcgi_params
    sudo cp config/fastcgi_params /etc/nginx/fastcgi_params
    chown root:root /etc/nginx/fastcgi_params
    sudo cp config/catch-all /etc/nginx/sites-available/catch-all
    sudo chown root:root /etc/nginx/sites-available/catch-all
    sudo ln -s /etc/nginx/sites-available/catch-all /etc/nginx/sites-enabled/catch-all
    sudo systemctl restart nginx
@endtask