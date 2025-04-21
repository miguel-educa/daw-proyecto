[.htaccess]: ./.htaccess

[PHPGangsta/GoogleAuthenticator]: https://github.com/PHPGangsta/GoogleAuthenticator


[Regresar](./README.md)

**Contenidos**

- [1. .htaccess](#1-htaccess)
- [2. example-env](#2-example-env)
- [3. Controllers](#3-controllers)
    - [3.1. 2FA](#31-2fa)
    - [3.2. AccountRecovery](#32-accountrecovery)
    - [3.3. Folders](#33-folders)
    - [3.4. Session](#34-session)
    - [3.5. User](#35-user)
    - [3.6. Users](#36-users)
- [4. Models](#4-models)
    - [4.1. Folders](#41-folders)
    - [4.2. Sessions](#42-sessions)
    - [4.3. Users](#43-users)
- [5. Schemas](#5-schemas)
    - [5.1. AccountRecovery](#51-accountrecovery)
    - [5.2. Folder](#52-folder)
    - [5.3. Session](#53-session)
    - [5.4. User](#54-user)
- [6. Tools](#6-tools)
    - [6.1. DB](#61-db)
    - [6.2. Encrypt](#62-encrypt)
    - [6.3. GoogleAuthenticator](#63-googleauthenticator)
    - [6.4. HttpCode](#64-httpcode)
    - [6.5. Request](#65-request)
    - [6.6. RequestMethod](#66-requestmethod)
    - [6.7. Response](#67-response)
    - [6.8. SessionDuration](#68-sessionduration)


# 1. [.htaccess]
Contiene **reglas** para añadir **seguridad** como no listar directorios sin index, no permitir acceso a rutas prohibidas...


# 2. [example-env](./example-env.php)
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


## 3.1. [2FA](./controllers/2FA.php)
Contiene la lógica de la ruta `/2fa.php`


## 3.2. [AccountRecovery](./controllers/AccountRecovery.php)
Contiene la lógica de la ruta `/account-recovery.php`


## 3.3. [Folders](./controllers/Folders.php)
Contiene la lógica de la ruta `/folders.php`


## 3.4. [Session](./controllers/Session.php)
Contiene la lógica de la ruta `/session.php`


## 3.5. [User](./controllers/User.php)
Contiene la lógica de la ruta `/user.php`


## 3.6. [Users](./controllers/Users.php)
Contiene la lógica de la ruta `/users.php`


# 4. Models
Contiene los **modelos** de la aplicación. Permiten interactuar directamente con la base de datos. Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 4.1. [Folders](./models/Folders.php)
Clase que permite interactuar con `Folder` en la base de datos


## 4.2. [Sessions](./models/Sessions.php)
Clase que permite interactuar con `Session` en la base de datos


## 4.3. [Users](./models/Users.php)
Clase que permite interactuar con `User` en la base de datos


# 5. Schemas
Contiene las **estructuras** y las **validaciones** de los objetos. Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 5.1. [AccountRecovery](./schemas/AccountRecovery.php)
Clase con la estructura y validaciones para realizar una recuperación de cuenta


## 5.2. [Folder](./schemas/Folder.php)
Clase con la estructura y validaciones de una `Folder`


## 5.3. [Session](./schemas/Session.php)
Clase con la estructura y validaciones de una `Session`


## 5.4. [User](./schemas/User.php)
Clase con la estructura y validaciones de un `User`


# 6. Tools
Contiene clases con distintas **herramientas útiles** para realizar conexiones con base de datos, encriptación de datos... Este directorio **NO** debe ser **accesible** mediante una petición HTTP ([.htaccess])


## 6.1. [DB](./tools/DB.php)
Clase que contiene la lógica para realizar una **conexión** e **interactuar** con una **base de datos** mediante `PDO`


## 6.2. [Encrypt](./tools/Encrypt.php)
Clase estática que contiene distintas **herramientas** para el cifrar, descifrar, generar tokens, UUIDs...


## 6.3. [GoogleAuthenticator](./tools/GoogleAuthenticator.php)
Clase que contiene la **lógica** para crear y realizar la **autenticación** de **doble factor** mediante **código temporal** (*TOTP*). Es una librería externa: [PHPGangsta/GoogleAuthenticator]


## 6.4. [HttpCode](./tools/HttpCode.php)
Enum que contiene **códigos** de **respuesta HTTP** como `200`, `404`, `500`...


## 6.5. [Request](./tools/Request.php)
Clase que permite **obtener** información de una **petición HTTP**, como el **método HTTP**, *body* con los **datos** enviados, Cookies...


## 6.6. [RequestMethod](./tools/RequestMethod.php)
Enum que contiene los **métodos HTTP** como `GET`, `POST`, `DELETE`...


## 6.7. [Response](./tools/Response.php)
Clase que permite **crear** y **mostrar** una **respuesta HTTP** manteniendo un formato consistente, **crear** o **eliminar** Cookies...


## 6.8. [SessionDuration](./tools/SessionDuration.php)
Enum que contiene las posibles **duraciones** (en segundos) de una `Session`


---

[Regresar](./README.md)
