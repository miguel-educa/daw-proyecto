[XAMPP imagen]: ../assets/xampp.png

[PHPGangsta/GoogleAuthenticator]: https://github.com/PHPGangsta/GoogleAuthenticator
[XAMPP]: https://www.apachefriends.org/es/index.html

[Regresar](../README.md)

**Contenidos**

- [1. Api](#1-api)
- [2. Estructura del proyecto](#2-estructura-del-proyecto)
- [3. Directorios y Archivos](#3-directorios-y-archivos)
- [4. Métodos de encriptación](#4-métodos-de-encriptación)
- [5. Rutas API](#5-rutas-api)
- [6. Autenticación de Doble Factor](#6-autenticación-de-doble-factor)


# 1. Api
Api de la aplicación. Se está utilizando **PHP** como lenguaje de programación y **MySQL** como base de datos relacional

Para el desarrollo en local se ha decidido utilizar [XAMPP] como entorno de desarrollo, aunque sólo se ha utilizado el servidor **Apache** con **PHP**.

![XAMPP imagen]

> [!NOTE]
> Para el servidor de **base de datos** se ha utilizado otra herramienta. [*más información](../db/README.md)


# 2. Estructura del proyecto

```text
📄 .htaccess
📄 example-env.php (env.php)
📁 controllers
    📄 2FA.php
    📄 AccountRecovery.php
    📄 Folders.php
    📄 Passwords.php
    📄 Session.php
    📄 SharedPasswords.php
    📄 User.php
    📄 Users.php
📁 models
    📄 Folders.php
    📄 Passwords.php
    📄 Sessions.php
    📄 Users.php
📁 schemas
    📄 AccountRecovery.php
    📄 Folder.php
    📄 Password.php
    📄 Session.php
    📄 User.php
📁 tools
    📄 DB.php
    📄 Encrypt.php
    📄 GoogleAuthenticator.php
    📄 HttpCode.php
    📄 Request.php
    📄 RequestMethod.php
    📄 Response.php
    📄 SessionDuration.php
📄 2fa.php
📄 account-recovery.php
📄 folders.php
📄 passwords.php
📄 session.php
📄 shared-passwords.php
📄 user.php
📄 users.php
```


# 3. [Directorios y Archivos](directories-files.md)
Explicación de los directorios y archivos


# 4. [Métodos de encriptación](encryption-methods.md)
Explicación de los métodos de encriptación


# 5. [Rutas API](api-routes.md)
Rutas disponibles de la API


# 6. Autenticación de Doble Factor
Para realizar la autenticación de doble factor mediante código temporal (*TOTP*), se ha utilizado la librería [PHPGangsta/GoogleAuthenticator]


---

[Regresar](../README.md)
