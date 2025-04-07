[XAMPP imagen]: ../assets/xampp.png

[XAMPP]: https://www.apachefriends.org/es/index.html

[Regresar](../README.md)

**Contenidos**

- [1. Backend](#1-backend)
- [2. Estructura del proyecto](#2-estructura-del-proyecto)
- [3. Directorios y Archivos](#3-directorios-y-archivos)
- [4. Métodos de encriptación](#4-métodos-de-encriptación)
- [5. Rutas API](#5-rutas-api)


# 1. Backend
Backend de la aplicación. Se está utilizando **PHP** como lenguaje de programación y **MySQL** como base de datos relacional

Para el desarrollo en local se ha decidido utilizar [XAMPP] como entorno de desarrollo, aunque sólo se ha utilizado el servidor **Apache** con **PHP**.

![XAMPP imagen]

> [!NOTE]
> Para el servidor de **base de datos** se ha utilizado otra herramienta. [*más información](../db/README.md)


# 2. Estructura del proyecto

```text
📄 .htaccess
📄 example-env.php (env.php)
📁 controllers
    📄 Folders.php
    📄 Session.php
    📄 User.php
    📄 Users.php
📁 models
    📄 Folders.php
    📄 Sessions.php
    📄 Users.php
📁 schemas
    📄 Folder.php
    📄 Session.php
    📄 User.php
📁 tools
    📄 DB.php
    📄 Encrypt.php
    📄 HttpCode.php
    📄 Request.php
    📄 RequestMethod.php
    📄 Response.php
    📄 SessionDuration.php
📄 folders.php
📄 session.php
📄 user.php
📄 users.php
```


# 3. [Directorios y Archivos](directories-files.md)
Explicación de los directorios y archivos


# 4. [Métodos de encriptación](encryption-methods.md)
Explicación de los métodos de encriptación


# 5. [Rutas API](api-routes.md)
Rutas disponibles de la API


---

[Regresar](../README.md)
