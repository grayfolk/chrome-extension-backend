## Requirements
This is application based on [Yii 2.0 Basic Application Template](https://github.com/yiisoft/yii2-app-basic)    
Please read it if any issues
Require PHP7+
## Setup

- Clone repository
- Copy config/web.php.dist and config/db.php.dist to config/web.php and config/db.php respectively
- Update config/db.php with your database credentials
- Run `composer install` in project root
- Run `./yii migrate` for migrations
- Setup your server for use pretty url as[ described in guide](https://www.yiiframework.com/doc/guide/2.0/en/start-installation#configuring-web-servers) (see also [notes below](#server-configuration))
- Default user credentials are hardcoded in models/User.php
- API endpoints: `/api` for get news, `/api/interests` for get interests; for get news with interests by ids: `/api?interests[]=1&interests[]=2` etc.

Setup every minute cron task for parse news:  
`php /path/to/yii/root/folder/yii parser`
Or you can run this command manually from project root folder:
`./yii parser`
## Server configuration
Folder `/web` should be setup as document root.
Example of nginx config:
```
server {
    charset utf-8;
    client_max_body_size 128M;

    listen 80; ## listen for ipv4
    #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

    server_name mysite.test;
    root        /path/to/web;
    index       index.php;

    access_log  /path/to/log/access.log;
    error_log   /path/to/log/error.log;

    location / {
        # Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php$is_args$args;
    }

    # uncomment to avoid processing of calls to non-existing static files by Yii
    #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
    #    try_files $uri =404;
    #}
    #error_page 404 /404.html;

    # deny accessing php files for the /assets directory
    location ~ ^/assets/.*\.php$ {
        deny all;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        #fastcgi_pass unix:/var/run/php5-fpm.sock;
        try_files $uri =404;
    }

    location ~* /\. {
        deny all;
    }
}
```
For Apache you can use .htaccess file in `/web` folder:
```
RewriteEngine on
# if $showScriptName is false in UrlManager, do not allow accessing URLs with script name
RewriteRule ^index.php/ - [L,R=404]
    
# If a directory or a file exists, use the request directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
    
# Otherwise forward the request to index.php
RewriteRule . index.php
```
