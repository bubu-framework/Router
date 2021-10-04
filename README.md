# Router library

## PLEASE

In your root directory put:

- For Apache server:

```htaccess
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
```

- For Nginx server:

```nginx
try_files $uri /index.php;
```
