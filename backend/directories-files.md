[.htaccess]: ./.htaccess
[env-example]: ./example-env.php

[Regresar](../README.md)

**Contenidos**

- [1. .htaccess](#1-htaccess)
- [2. env-example](#2-env-example)


# 1. [.htaccess]
Contiene **reglas** para añadir **seguridad** como no listar directorios sin index, no permitir acceso a rutas prohibidas...


# 2. [env-example]
Contiene variables de entorno del proyecto. Se debe realizar una **copia** de este archivo y **renombrarlo** a `env.php` para que se pueda utilizar en el entorno de desarrollo o producción.

> [!IMPORTANT]
> `env.php` debe ser importado mediante `require_once` en los archivos que necesiten utilizar las variables de entorno


---

[Regresar](../README.md)
