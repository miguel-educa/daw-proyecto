[.htaccess]: ./.htaccess
[env-example]: ./example-env.php

[UsersController]: ./controllers/Users.php

[UsersModel]: ./models/Users.php

[DB]: ./tools/DB.php
[Encrypt]: ./tools/Encrypt.php
[HttpCode]: ./tools/HttpCode.php
[Request]: ./tools/Request.php
[RequestMethod]: ./tools/RequestMethod.php
[Response]: ./tools/Response.php

[Regresar](./README.md)

**Contenidos**

- [1. .htaccess](#1-htaccess)
- [2. env-example](#2-env-example)
- [3. Controllers](#3-controllers)
    - [3.1. UsersController](#31-userscontroller)
- [4. Models](#4-models)
    - [4.1. UsersModel](#41-usersmodel)
- [5. Tools](#5-tools)
    - [5.1. DB](#51-db)
    - [5.2. Encrypt](#52-encrypt)
    - [5.3. HttpCode](#53-httpcode)
    - [5.4. Request](#54-request)
    - [5.5. RequestMethod](#55-requestmethod)
    - [5.6. Response](#56-response)


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


# 3. Controllers
Los controladores permiten organizar las distintas rutas y los distintos métodos HTTP. Sirven de puente entre el `Model` y la `View`. Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 3.1. [UsersController]
Contiene la lógica de la ruta `/users.php`


# 4. Models
Contiene los **modelos** de la aplicación. Permiten interactuar directamente con la base de datos. Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 4.1. [UsersModel]
Clase que permite interactuar con `User` en la base de datos


# 5. Tools
Contiene clases con distintas **herramientas útiles** para realizar conexiones con base de datos, encriptación de datos... Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 5.1. [DB]
Clase que contiene la lógica para realizar una **conexión** e **interactuar** con una **base de datos** mediante `PDO`


## 5.2. [Encrypt]
Clase estática que contiene distintas **herramientas** para el cifrar, descifrar, generar tokens, UUIDs...


## 5.3. [HttpCode]
Enum que contiene **códigos** de **respuesta HTTP** como `200`, `404`, `500`...


## 5.4. [Request]
Clase que permite **obtener** información de una **petición HTTP**, como el **método HTTP**, *body* con los **datos** enviados, Cookies...


## 5.5. [RequestMethod]
Enum que contiene los **métodos HTTP** como `GET`, `POST`, `DELETE`...


## 5.6. [Response]
Clase que permite **crear** y **mostrar** una **respuesta HTTP** manteniendo un formato consistente, **crear** o **eliminar** Cookies...


---

[Regresar](./README.md)
