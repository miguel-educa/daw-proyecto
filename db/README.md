[DBeaver image]: ../assets/dbeaver.png
[DBngine image]: ../assets/dbngine.png
[Modelo Entidad/Relación image]: ../assets/modelo-entidad-relacion.png
[Modelo Relacional image]: ../assets/modelo-relacional.png

[modelo-entidad-relacion.drawio]: ../assets/modelo-entidad-relacion.drawio
[modelo-relacional.drawio]: ../assets/modelo-relacional.drawio
[db_pass_warriors_example_data.sql]: ./db_pass_warriors_example_data.sql
[db_pass_warriors_structure.sql]: ./db_pass_warriors_structure.sql

[DBeaver]: https://dbeaver.io/
[DBngine]: https://dbngin.com/
[draw.io]: https://app.diagrams.net/

[Regresar](../README.md)

**Contenidos**

- [1. Base de Datos](#1-base-de-datos)
    - [1.1. Entidades](#11-entidades)
        - [1.1.1. User](#111-user)
        - [1.1.2. Session](#112-session)
        - [1.1.3. Folder](#113-folder)
        - [1.1.4. Password](#114-password)
    - [1.2. Relaciones entre Entidades](#12-relaciones-entre-entidades)
        - [1.2.1. User-Session](#121-user-session)
        - [1.2.2. User-Folder](#122-user-folder)
        - [1.2.3. User-Password](#123-user-password)
        - [1.2.4. Folder-Password](#124-folder-password)
- [2. Diagramas](#2-diagramas)
    - [2.1. Modelo Entidad/Relación](#21-modelo-entidadrelación)
    - [2.2. Modelo Relacional](#22-modelo-relacional)
- [3. Código](#3-código)


# 1. Base de Datos
Para la realización de este proyecto se ha decidido utilizar una base de datos relacional, en concreto, se ha utilizado **MySQL** (versión 8.4.2) como *Sistema Gestor de Bases de Datos Relacionales* (RDBMS) porque es una de las más populares y se ha utilizado durante el Grado Superior

Para el **desarrollo en local** de la base de datos se ha utilizado la herramienta [DBngine]. Esta herramienta permite crear distintas instancias de bases de datos (*MySQL*, *MariaDB*, *PostgreSQL* y *Redis*) y, además, permite tener distintas versiones de cada tipo en un mismo entorno

![DBngine image]

Como **cliente** de la base de datos se ha utilizado [DBeaver]. Una herramienta que permite realizar conexiones a diferentes tipos de bases de datos y realizar consultas, modificaciones y visualizaciones de los datos

![DBeaver image]


## 1.1. Entidades

La base de datos tiene las siguientes **entidades**:


### 1.1.1. User
Representa a un **usuario registrado** en la aplicación y tiene las siguientes **propiedades**:

- `id` (`UUID`): Identificador único (**Primary Key**)
- `username` (`VARCHAR(30)`): Nombre único de usuario (**Unique**)
- `name` (`VARCHAR(50)`): Nombre a mostrar del usuario
- `master_password` (`CHAR(60)`): Hash de la contraseña maestra
- `master_password_updated_at` (`TIMESTAMP`): Fecha de la última edición de la contraseña maestra
- `recuperation_code` (`CHAR(24)`): Código de recuperación para poder restablecer la contraseña maestra en caso de olvido (**Unique**)
- `recuperation_code_updated_at` (`TIMESTAMP`): Fecha de la última edición del código de recuperación
- `created_at` (`TIMESTAMP`): Campo de control
- `updated_at` (`TIMESTAMP`): Campo de control


### 1.1.2. Session
Representa una **sesión** de un `User` y tiene las siguientes **propiedades**:

- `id` (`UUID`): Identificador único (**Primary Key**)
- `token` (`CHAR(64)`): Token hexadecimal único como identificador de la sesión (**Unique**)
- `token_created_at` (`TIMESTAMP`): Fecha de creación del token
- `token_expires_at` (`TIMESTAMP`): Fecha de expiración del token
- `revoked` (`BOOLEAN`): Indica si la sesión ha sido revocada
- `user_agent` (`VARCHAR(255)`): *User Agent* del dispositivo que creó la sesión
- `created_at` (`TIMESTAMP`): Campo de control
- `updated_at` (`TIMESTAMP`): Campo de control


### 1.1.3. Folder
Representa una **carpeta** y tiene las siguientes **propiedades**:

- `id` (`UUID`): Identificador único (**Primary Key**)
- `name` (`VARCHAR(50)`): Nombre de la carpeta
- `created_at` (`TIMESTAMP`): Campo de control
- `updated_at` (`TIMESTAMP`): Campo de control


### 1.1.4. Password
Representa una **contraseña** y tiene las siguientes **propiedades**:

- `id` (`UUID`): Identificador único (**Primary Key**)
- `name` (`VARCHAR(50)`): Nombre de la contraseña
- `password` (`VARCHAR(100)`): Contraseña encriptada
- `username` (`VARCHAR(50)`): Nombre de usuario
- `urls` (`JSON`): Lista de URLs
- `notes` (`TEXT`): Notas extra
- `created_at` (`TIMESTAMP`): Campo de control
- `updated_at` (`TIMESTAMP`): Campo de control


## 1.2. Relaciones entre Entidades
Las entidades mantienen las siguientes **relaciones**:


### 1.2.1. User-Session
Un `User` puede **tener** 0, 1 o N `Sessions` (distinto dispositivo, navegador...) y una `Session` **únicamente** puede **pertenecer** a un `User`

- La relación es `1:N`, por tanto, la clave de `User` se propaga a `Session` como clave foránea (`user_id`)


### 1.2.2. User-Folder
Un `User` puede **crear** 0, 1 o N `Folders` y una `Folder` **únicamente** puede **pertenecer** a un `User`

- La relación es `1:N`, por tanto, la clave de `User` se propaga a `Folder` como clave foránea (`owner_user_id`)

> [!WARNING]
> Un `User` no puede tener `Folders` con nombres repetidos


### 1.2.3. User-Password
Un `User` puede **crear** 0, 1 o N `Passwords` y una `Password` **únicamente** puede **pertenecer** a un `User`

- La relación es `1:N`, por tanto, la clave de `User` se propaga a `Password` como clave foránea (`owner_user_id`)

Además, un `User` puede compartir 0, 1 o N `Passwords` con otros `Users` y a un `User` le pueden compartir 0, 1 o N `Passwords`. Además, el `User` que comparte puede establecer permiso de *solo-lectura* o *lectura-escritura* a cada `User` compartido

- La relación es `N:M`, por tanto, se debe crear una nueva entidad `Shared Password`. La entidad tiene las siguientes propiedades:
    - `id` (`UUID`): Identificador único (**Primary Key**)
    - `shared_user_id` (`UUID`): Clave foránea del `User` al que **le comparten** la `Password`
    - `password_id` (`UUID`): Clave foránea de la `Password` que se comparte
    - `permission` (`ENUM`): Tipo de permiso del usuario compartido
        - `read-only`: Solo lectura
        - `read-write`: Lectura y escritura
    - `created_at` (`TIMESTAMP`): Campo de control
    - `updated_at` (`TIMESTAMP`): Campo de control

> [!WARNING]
> No se puede compartir una `Password` a un mismo `User` varias veces


### 1.2.4. Folder-Password
Una `Folder` puede **tener** 0, 1 o N `Password` y una `Password` puede **pertenecer** a una `Folder` o no

- La relación es `1:N`, por tanto, la clave de `Folder` se propaga a `Password` como clave foránea (`folder_id`)


# 2. Diagramas

> [!TIP]
> Los siguientes diagramas han sido diseñados mediante [draw.io]. Los archivos con extensión `.drawio` pueden ser abiertos en la aplicación para visualizar o editar el diagrama


## 2.1. Modelo Entidad/Relación
![Modelo Entidad/Relación image]

> [!NOTE]
> Archivo `draw.io`: [modelo-entidad-relacion.drawio]


## 2.2. Modelo Relacional
![Modelo Relacional image]

> [!NOTE]
> Archivo `draw.io`: [modelo-relacional.drawio]


# 3. Código

- El código `SQL` con la **estructura** de la base de datos se encuentra en [db_pass_warriors_structure.sql]
- El código `SQL` con la **datos de ejemplo** se encuentra en [db_pass_warriors_example_data.sql]

---

[Regresar](../README.md)
