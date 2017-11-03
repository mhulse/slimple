# Slimple

Simple Slim PHP starter repo.

## PHP built-in server

```bash
$ composer start
```

… and visit <http://0.0.0.0:8080/>.

**This will not load the `.htaccess` file!**

## Apache dev option

To test `.htaccess`, setup an Apache vhost:

```apache
<VirtualHost *:80>
	DocumentRoot "/root/path/to/site.local/public"
	ServerName site.local.local
	ServerAlias www.site.local.local
	ErrorLog "logs/site.local.local-error.log"
	CustomLog "logs/site.local.local-access.log" combined
	#DirectoryIndex index.html
	<Directory "/root/path/to/site.local/public">
		AddDefaultCharset utf-8
		IndexOptions +FancyIndexing NameWidth=*
		Options -Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Order allow,deny
		Allow from all
		Require all granted
	</Directory>
</VirtualHost>
```

Add `127.0.0.1 site.local.local` to your hosts file and restart your local dev Apache.

Use this approach if you want to test the rules found in the `.htaccess`. Once that's solid, feel free to use the php built-in server.

## Install Composer dependencies:

Install [Composer](https://getcomposer.org/):

```bash
$ curl -s http://getcomposer.org/installer | php
```

Get the composer-installable code:

```bash
$ php composer.phar install
```

If/when needed, update Composer dependencies using:

```bash
$ php composer.phar update
```

**WARNING:** You should **never** run `composer update` on the production machine!

> [after] deploy[ing] your updated `composer.lock`, [you should] then re-run `composer install`. You should never run `composer update` in production. If however you deploy a new `composer.lock` with new dependencies and/or versions (after having run `composer update` in dev) [you can] then run `composer install` [and] Composer will update and install your new dependencies [onto the production machineâ€™s deployment].  
> – [“composer update” vs “composer install”](http://adamcod.es/2013/03/07/composer-install-vs-composer-update.html)
