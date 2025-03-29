[.htaccess]: ./.htaccess
[env-example]: ./example-env.php

[DB]: ./tools/DB.php
[Encrypt]: ./tools/Encrypt.php

[Regresar](./README.md)

**Contenidos**

- [1. .htaccess](#1-htaccess)
- [2. env-example](#2-env-example)
- [3. Tools](#3-tools)
    - [3.1. DB](#31-db)
    - [3.2. Encrypt](#32-encrypt)


# 1. [.htaccess]
Contiene **reglas** para añadir **seguridad** como no listar directorios sin index, no permitir acceso a rutas prohibidas...


# 2. [env-example]
Contiene variables de entorno del proyecto. Se debe realizar una **copia** de este archivo y **renombrarlo** a `env.php` para que se pueda utilizar en el entorno de desarrollo o producción.

Se **deben configurar** las siguientes variables:

- `DB_HOST`: IP o nombre del servidor de la base de datos
- `DB_USER`: Usuario de la base de datos
- `DB_PASSWORD`: Contraseña de la base de datos
- `DB_NAME`: Nombre de la base de datos
- `ENCRYPTION_PASSPHRASE`: **Passphrase** a utilizar. Debe ser **larga** (mayor a 32 caracteres), **aleatoria** y **NUNCA** debe ser **compartida**

> [!IMPORTANT]
> `env.php` debe ser importado mediante `require_once` en los archivos que necesiten utilizar las variables de entorno


# 3. Tools
Contiene clases con distintas **herramientas útiles** para realizar conexiones con base de datos, encriptación de datos...


## 3.1. [DB]
Clase que contiene la lógica para realizar una **conexión** e **interactuar** con una **base de datos** mediante `PDO`


## 3.2. [Encrypt]
Clase estática que contiene distintas **herramientas** para el cifrar, descifrar, generar tokens, UUIDs...


---

[Regresar](./README.md)
