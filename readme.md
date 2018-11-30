# ONG

## Desarrolladores

### Instalación

Desde la terminal vamos a comenzar clonando el proyecto e instalando las dependencias:

```bash
git clone http://git.ecom.com.ar/felixbarros/ong.git
cd ong
composer install
```

Abrimos el archivo .env y editamos los datos de conexión a la base de datos:

```
DATABASE_URL=mysql://root:password@127.0.0.1:3306/name_database
```

Últimos comandos son para los assets, las tablas y los archivos estaticos:

```bash
php bin/console assets:install
php bin/console doctrine:schema:update --force
```

```bash
yarn install
yarn encore dev
```