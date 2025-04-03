[.htaccess]: ./.htaccess
[example-env]: ./example-env.php

[SessionController]: ./controllers/Session.php
[UsersController]: ./controllers/Users.php

[SessionsModel]: ./models/Sessions.php
[UsersModel]: ./models/Users.php

[SessionSchema]: ./schemas/Session.php
[UserSchema]: ./schemas/User.php

[DB]: ./tools/DB.php
[Encrypt]: ./tools/Encrypt.php
[HttpCode]: ./tools/HttpCode.php
[Request]: ./tools/Request.php
[RequestMethod]: ./tools/RequestMethod.php
[Response]: ./tools/Response.php
[SessionDuration]: ./tools/SessionDuration.php

[Regresar](./README.md)

**Contenidos**

- [1. .htaccess](#1-htaccess)
- [2. example-env](#2-example-env)
- [3. Controllers](#3-controllers)
    - [3.1. SessionController](#31-sessioncontroller)
    - [3.2. UsersController](#32-userscontroller)
- [4. Models](#4-models)
    - [4.1. SessionsModel](#41-sessionsmodel)
    - [4.2. UsersModel](#42-usersmodel)
- [5. Schemas](#5-schemas)
    - [5.1. SessionSchema](#51-sessionschema)
    - [5.2. UserSchema](#52-userschema)
- [6. Tools](#6-tools)
    - [6.1. DB](#61-db)
    - [6.2. Encrypt](#62-encrypt)
    - [6.3. HttpCode](#63-httpcode)
    - [6.4. Request](#64-request)
    - [6.5. RequestMethod](#65-requestmethod)
    - [6.6. Response](#66-response)
    - [6.7. SessionDuration](#67-sessionduration)


# 1. [.htaccess]
Contiene **reglas** para añadir **seguridad** como no listar directorios sin index, no permitir acceso a rutas prohibidas...


# 2. [example-env]
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


## 3.1. [SessionController]
Contiene la lógica de la ruta `/session.php`


## 3.2. [UsersController]
Contiene la lógica de la ruta `/users.php`


# 4. Models
Contiene los **modelos** de la aplicación. Permiten interactuar directamente con la base de datos. Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 4.1. [SessionsModel]
Clase que permite interactuar con `Session` en la base de datos


## 4.2. [UsersModel]
Clase que permite interactuar con `User` en la base de datos


# 5. Schemas
Contiene las **estructuras** y las **validaciones** de los objetos. Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 5.1. [SessionSchema]
Clase con la estructura y validaciones de una `Session`


## 5.2. [UserSchema]
Clase con la estructura y validaciones de un `User`


# 6. Tools
Contiene clases con distintas **herramientas útiles** para realizar conexiones con base de datos, encriptación de datos... Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 6.1. [DB]
Clase que contiene la lógica para realizar una **conexión** e **interactuar** con una **base de datos** mediante `PDO`


## 6.2. [Encrypt]
Clase estática que contiene distintas **herramientas** para el cifrar, descifrar, generar tokens, UUIDs...


## 6.3. [HttpCode]
Enum que contiene **códigos** de **respuesta HTTP** como `200`, `404`, `500`...


## 6.4. [Request]
Clase que permite **obtener** información de una **petición HTTP**, como el **método HTTP**, *body* con los **datos** enviados, Cookies...


## 6.5. [RequestMethod]
Enum que contiene los **métodos HTTP** como `GET`, `POST`, `DELETE`...


## 6.6. [Response]
Clase que permite **crear** y **mostrar** una **respuesta HTTP** manteniendo un formato consistente, **crear** o **eliminar** Cookies...


## 6.7. [SessionDuration]
Enum que contiene las posibles **duraciones** (en segundos) de una `Session`


---

[Regresar](./README.md)
