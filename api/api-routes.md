[Regresar](./README.md)

**Contenidos**

- [1. Rutas API](#1-rutas-api)
    - [1.1. /folders.php](#11-foldersphp)
        - [1.1.1. GET](#111-get)
        - [1.1.2. POST](#112-post)
    - [1.2. /passwords.php](#12-passwordsphp)
        - [1.2.1. GET](#121-get)
        - [1.2.2. POST](#122-post)
    - [1.3. /sessions.php](#13-sessionsphp)
        - [1.3.1. DELETE](#131-delete)
        - [1.3.2. POST](#132-post)
    - [1.4. /user.php](#14-userphp)
        - [1.4.1. GET](#141-get)
    - [1.5. /users.php](#15-usersphp)
        - [1.5.1. GET](#151-get)
        - [1.5.2. POST](#152-post)


# 1. Rutas API
La **estructura** del JSON de todas las **respuestas** de la API es la siguiente:

```jsonc
{
  "service_name": "Pass Warriors",
  "success": true, // `true` o `false`
  "data": {}, // `{}`, `[ {} ]` o `null`
  "errors": [ "string" ] // `array` o `null`
}
```

| Propiedad      | Descripción                                                                                              |
| -------------- | -------------------------------------------------------------------------------------------------------- |
| `service_name` | Nombre del servicio                                                                                      |
| `success`      | Muestra si la respuesta fue existosa (`true`) o hubo un error en la petición (`false`)                   |
| `data`         | `Objeto` con el resultado obtenido o `Array` con los objetos obtenidos. `null` si se produce algún error |
| `errors`       | `Array` con los mensajes de errores producidos. `null` si no se produjeron errores                       |


## 1.1. /folders.php
Proporciona información sobre los `Folder`


### 1.1.1. GET
Permite **recuperar** los `Folder` de un `User`

> [!CAUTION]
> Se necesita estar autenticado. Si no se mostrará un error `401`

- Data:

```jsonc
[
    {
        "id": "string",
        "name": "string"
    },
    ...
]
```


### 1.1.2. POST
Permite **crear** un `Folder`. El `body` de la petición debe contener la siguiente estructura

```jsonc
{
  "name": "string"
}
```

| Propiedad | Descripción                                                                                                     | Requerido |
| --------- | --------------------------------------------------------------------------------------------------------------- | --------- |
| `name`    | Debe ser **único** por cada `User`. Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres | ✔️         |

> [!CAUTION]
>
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`


Si los **datos** son **válidos**, se creará un `Folder` y se retornará la siguiente **data** del `Folder` creado:

```jsonc
{
    "id": "string",
    "name": "string"
}
```


## 1.2. /passwords.php
Proporciona información sobre las `Password`


### 1.2.1. GET
Permite **recuperar** las `Password` de un `User`

> [!CAUTION]
> Se necesita estar autenticado. Si no se mostrará un error `401`

- Data:

```jsonc
[
    {
        "id": "string",
        "folder_id": "string", // `string` o `null`
        "name": "string",
        "password": "string", // `string` o `null`
        "username": "string", // `string` o `null`
        "urls": [ "string" ], // `array` o `null`
        "notes": "string", // `string` o `null`
    },
    ...
]
```


### 1.2.2. POST
Permite **crear** una `Password`. El `body` de la petición debe contener la siguiente estructura

```jsonc
{
  "name": "string",
  "folder_id": "string",
  "password": "string",
  "username": "string",
  "urls": "array",
  "notes": "string"
}
```

| Propiedad   | Descripción                                                                                                          | Requerido |
| ----------- | -------------------------------------------------------------------------------------------------------------------- | --------- |
| `name`      | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres                                          | ✔️         |
| `folder_id` | ID del `Folder` que almacenará la `Password`. Si no se expecifica, su valor es `null`                                | ❌         |
| `password`  | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres. Si no se expecifica, su valor es `null` | ❌         |
| `username`  | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres. Si no se expecifica, su valor es `null` | ❌         |
| `urls`      | *Array* de *Strings* (de 1 hasta 5 items) con las URLs de la `Password`. Si no se expecifica, su valor es `null`     | ❌         |
| `notes`     | Puede contener **cualquier carácter**. Si no se expecifica, su valor es `null`                                       | ❌         |


> [!CAUTION]
>
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`


Si los **datos** son **válidos**, se creará una `Password` y se retornará la siguiente **data** de la `Password` creada:

```jsonc
{
    "id": "string",
    "folder_id": "string", // `string` o `null`
    "name": "string",
    "password": "string", // `string` o `null`
    "username": "string", // `string` o `null`
    "urls": [ "string" ], // `array` o `null`
    "notes": "string", // `string` o `null`
}
```


## 1.3. /sessions.php
Permite **gestionar** las sesiones de los `Users`


### 1.3.1. DELETE
Elimina una `Session` (se actualiza como revocada) para que no pueda ser utilizada


> [!CAUTION]
>
> - Si no se encuentra una Cookie con un `session_token` válido, se mostrará un error `401`

Si se revoca correctamente la `Session`, se retorna la siguiente **data**:

```jsonc
{
    "session_revoked": true
}
```


### 1.3.2. POST
Permite **crear** una `Session` para un `User` existente

- **Body** de la petición debe contener la siguiente estructura

```jsonc
{
  "username": "string",
  "master_password": "string",
  "session_duration": 0
}
```

| Propiedad          | Descripción                                                                                                                                                                                                                                                                                                                                                                          | Requerido |
| ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | --------- |
| `username`         | Nombre de usuario                                                                                                                                                                                                                                                                                                                                                                    | ✔️         |
| `master_password`  | Contraseña maestra                                                                                                                                                                                                                                                                                                                                                                   | ✔️         |
| `session_duration` | Tiempo de duración de la `Session` en segundos. Se aceptan los siguientes valores:<br>- `3600`: Duración de *1 hora*<br>- `86400`: Duración de *1 día*<br>- `604800`: Duración de *7 días*<br>- `2592000`: Duración de *30 días*<br>- `7776000`: Duración de *90 días*<br><br>Si no se especifica o contiene un valor inválido, se establece la duración de la `Session` en *1 hora* | ❌         |

> [!CAUTION]
>
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`
> - Si el `username` no existe o la `master_password` no es correcta, se mostrará un error `401`

Si los datos son **válidos**, se crea una `Session` y se retorna la siguiente **data**:

```jsonc
{
    "id": "string",
    "user_id": "string",
    "name": "string",
    "token_created_at": 0, // Unix Timestamp en segundos
    "token_expires_at": 0, // Unix Timestamp en segundos
    "revoked": false,
    "user_agent": "string"
}
```

> [!IMPORTANT]
> Se crea la Cookie `session_token` con la **sesión** del `User` recién creado


## 1.4. /user.php
Proporciona información sobre el `User` autenticado


### 1.4.1. GET
Permite **recuperar** información sobre el `User` autenticado

- **Filtros** disponibles:

    | *Query param*                 | Descripción                                                                                                                                            |
    | ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
    | `?confidential_data=<string>` | Si se establece en `true`, se devolverá información **sensible**. Si no se encuentra el filtro o su valor es distinto, no se obtendrá esta información |

> [!CAUTION]
>
> - Si no existe la Cookie con el `session_token` o éste no es válido, ha expirado o el *user agent* del dispositivo no coincide, se mostrará un error `401`
> - Si el token existe, pero no está asociado a un `User` (cuenta eliminada), se mostrará un error `404`

- Data sin filtro:

```jsonc
{
    "id": "string",
    "username": "string",
    "name": "string",
}
```

- Data con filtro `confidential_data=true`:

```jsonc
{
    "id": "string",
    "username": "string",
    "name": "string",
    "master_password_edited_at": 0, // Unix Timestamp en segundos
    "recuperation_code": "string",
    "recuperation_code_edited_at": 0 // Unix Timestamp en segundos
}
```


## 1.5. /users.php
Proporciona información sobre los `Users`


### 1.5.1. GET
Permite **recuperar** uno o varios `User`

- **Filtros** disponibles:

    | *Query param*        | Descripción                                                                                  |
    | -------------------- | -------------------------------------------------------------------------------------------- |
    | `?username=<string>` | Retorna un **único** `User` cuyo `username` **coincida completamente**. \**Case insensitive* |
    | `?name=<string>`     | Retorna **varios** `User` cuyo `name` **contenga** el valor. \**Case insensitive*            |

> [!CAUTION]
>
> - Se **debe utilizar** uno de los filtros. Si no se utiliza ninguno, se mostrará un error `400`
> - Sólo se puede utilizar **un filtro** en cada petición. Si se encuentran varios filtros, se utilizará el filtro según el orden espicificado en la tabla
> - Si **no** se **encontraron** resultados, se mostrará un error `404`


- Data del filtro `username`

    ```jsonc
    {
        "username": "string",
        "name": "string",
    }
    ```

- Data del filtro `name`

    ```jsonc
    [
        {
            "username": "string",
            "name": "string",
        },
        ...
    ]
    ```


### 1.5.2. POST
Permite **crear** un `User`. El `body` de la petición debe contener la siguiente estructura

```jsonc
{
  "username": "string",
  "name": "string",
  "master_password": "string"
}
```

| Propiedad         | Descripción                                                                                                                                                                                                                          | Requerido |
| ----------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | --------- |
| `username`        | Debe ser **único**. ***Regex*** que debe cumplir: `/^[a-zA-Z][a-zA-Z0-9_]{1,29}$/`                                                                                                                                                   | ✔️         |
| `name`            | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres                                                                                                                                                          | ✔️         |
| `master_password` | Longitud entre `8` y `50` caracteres. Debe **contener** al menos **una** letra **minúscula** y **una** letra **mayúscula** (alfabeto inglés), **un número** y **alguno** de los siguientes **símbolos especiales** `_-,;!.@*&#%+$/` | ✔️         |

> [!CAUTION]
>
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`


Si los **datos** son **válidos**, se creará un `User` y se retornará la siguiente **data** del `User` creado:

```jsonc
{
    "id": "string",
    "username": "string",
    "name": "string",
    "recuperation_code": "string",
    "master_password_edited_at": 0, // Unix Timestamp en segundos
    "recuperation_code_edited_at": 0 // Unix Timestamp en segundos
}
```

> [!IMPORTANT]
> Se crea la Cookie `session_token` con la **sesión** del `User` recién creado


---

[Regresar](./README.md)
