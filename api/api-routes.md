[Regresar](./README.md)

**Contenidos**

- [1. Rutas API](#1-rutas-api)
    - [1.1. /2fa.php](#11-2faphp)
        - [1.1.1. DELETE](#111-delete)
        - [1.1.2. POST](#112-post)
            - [1.1.2.1. Petición 1](#1121-petición-1)
            - [1.1.2.2. Petición 2](#1122-petición-2)
    - [1.2. /account-recovery.php](#12-account-recoveryphp)
        - [1.2.1. POST](#121-post)
    - [1.3. /folders.php](#13-foldersphp)
        - [1.3.1. DELETE](#131-delete)
        - [1.3.2. GET](#132-get)
        - [1.3.3. PATCH](#133-patch)
        - [1.3.4. POST](#134-post)
    - [1.4. /passwords.php](#14-passwordsphp)
        - [1.4.1. DELETE](#141-delete)
        - [1.4.2. GET](#142-get)
        - [1.4.3. PATCH](#143-patch)
        - [1.4.4. POST](#144-post)
    - [1.5. /session.php](#15-sessionphp)
        - [1.5.1. DELETE](#151-delete)
        - [1.5.2. PATCH](#152-patch)
        - [1.5.3. POST](#153-post)
    - [1.6. /user.php](#16-userphp)
        - [1.6.1. DELETE](#161-delete)
        - [1.6.2. GET](#162-get)
    - [1.7. /users.php](#17-usersphp)
        - [1.7.1. GET](#171-get)
        - [1.7.2. PATCH](#172-patch)
        - [1.7.3. POST](#173-post)


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


## 1.1. /2fa.php
Proporciona información sobre las `Password`


### 1.1.1. DELETE
Permite **eliminar** la autenticación de doble factor de un `User`

> [!CAUTION]
> Se necesita estar autenticado. Si no se mostrará un error `401`
> Si no existe la autenticación de doble factor, se mostrará un error `400`

Si se elimina con éxito, se retorna la siguiente **data**:

```jsonc
{
    "two_fa_deleted": true
}
```


### 1.1.2. POST
Permite **crear** la autenticación de doble factor de un `User`. Para la creación y activación de *2FA*, se debe realizar 2 peticiones.

> [!CAUTION]
> Se necesita estar autenticado. Si no se mostrará un error `401`


#### 1.1.2.1. Petición 1

Permite generar el ***secret*** de la autenticación de doble factor. El **body** de la petición debe estar **vacío** (pero **debe existir**):

```jsonc
{}
```

- Si se genera correctamente, se retorna la siguiente **data**:

```jsonc
{
    "qr_code_url": "string",
    "secret": "string"
}
```


#### 1.1.2.2. Petición 2

Permite **verificar** el código temporal generado al escanear el **código QR** por la aplicación correspondiente (*Google Authenticator*, *Microsoft Authenticator*, *Authy*...) para habilitar *2FA* en el proceso de *login*. El cuerpo de la petición debe contener la siguiente estructura:

```jsonc
{
    "code": "string",
    "secret": "string"
}
```

| Propiedad | Descripción                                                                     | Requerido |
| --------- | ------------------------------------------------------------------------------- | --------- |
| `code`    | El código temporal generado por la aplicación correspondiente                   | ✔️         |
| `secret`  | El *secret* de la autenticación de doble factor generado en la primera petición | ✔️         |

- Si el código es correcto, se retorna la siguiente **data**:

```jsonc
{
    "two_fa_created": true
}
```

> [!IMPORTANT]
> A partir de este momento, el usuario deberá usar el código temporal generado por la aplicación para poder iniciar sesión


## 1.2. /account-recovery.php
Permite recuperar la cuenta de un usuario


### 1.2.1. POST
Permite **recuperar** la cuenta de un usuario proporcionando el **código de recuperación**

- El **body** de la petición debe contener la siguiente estructura

```jsonc
{
    "username": "string",
    "recuperation_code": "string",
    "master_password": "string"
}
```

| Propiedad           | Descripción                                     | Requerido |
| ------------------- | ----------------------------------------------- | --------- |
| `username`          | Nombre de usuario de la cuenta a recuperar      | ✔️         |
| `recuperation_code` | Código de recuperación de la cuenta a recuperar | ✔️         |
| `master_password`   | Contraseña maestra                              | ✔️         |

- Si el `username` existe y el `recuperation_code` es correcto, se genera un nuevo código de recuperación y se actualizan los datos del usuario. También se creará una `Session` y se retornará el nuevo código de recuperación:

```jsonc
{
    "recuperation_code": "string"
}
```


## 1.3. /folders.php
Proporciona información sobre los `Folder`


### 1.3.1. DELETE
Permite **eliminar** un `Folder`. El cuerpo de la petición debe contener el `id` del `Folder` a eliminar

> [!CAUTION]
> Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`

Si se elimina con éxito, se retorna la siguiente **data**:

```jsonc
{
    "folder_deleted": true
}
```


### 1.3.2. GET
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


### 1.3.3. PATCH
Permite **actualizar** un `Folder`. El `body` de la petición debe contener la siguiente estructura. Todas las propiedades son opcionales (excepto `id`), si no se especifican, se mantendrán los mismos valores

```jsonc
{
    "id": "string",
    "name": "string"
}
```

| Propiedad | Descripción                                                                                                     |
| --------- | --------------------------------------------------------------------------------------------------------------- |
| `id`      | ID del `Folder` a actualizar                                                                                    |
| `name`    | Debe ser **único** por cada `User`. Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres |

> [!CAUTION]
>
> - Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`


Si los **datos** son **válidos**, se actualizará el `Folder` y se retornará la siguiente **data**:

```jsonc
{
    "id": "string",
    "name": "string"
}
```


### 1.3.4. POST
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
> - Se necesita estar autenticado. Si no se mostrará un error `401`
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`


Si los **datos** son **válidos**, se creará un `Folder` y se retornará la siguiente **data** del `Folder` creado:

```jsonc
{
    "id": "string",
    "name": "string"
}
```


## 1.4. /passwords.php
Proporciona información sobre las `Password`


### 1.4.1. DELETE
Permite **eliminar** una `Password`. El cuerpo de la petición debe contener el `id` de la `Password` a eliminar

> [!CAUTION]
> Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`

Si se elimina con éxito, se retorna la siguiente **data**:

```jsonc
{
    "password_deleted": true
}
```


### 1.4.2. GET
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


### 1.4.3. PATCH
Permite **actualizar** una `Password`. El `body` de la petición debe contener la siguiente estructura. Todas las propiedades son opcionales (excepto `id`), si no se especifican, se mantendrán los mismos valores

```jsonc
{
  "id": "string",
  "name": "string",
  "folder_id": "string",
  "password": "string",
  "username": "string",
  "urls": "array",
  "notes": "string"
}
```

| Propiedad   | Descripción                                                                                   |
| ----------- | --------------------------------------------------------------------------------------------- |
| `id`        | ID de la `Password` a actualizar                                                              |
| `name`      | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres                   |
| `folder_id` | ID del `Folder` que almacenará la `Password`. Puede ser `null`                                |
| `password`  | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres. Puede ser `null` |
| `username`  | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres. Puede ser `null` |
| `urls`      | *Array* de *Strings* (de 1 hasta 5 items) con las URLs de la `Password`. Puede ser `null`     |
| `notes`     | Puede contener **cualquier carácter**. Puede ser `null`                                       |

> [!CAUTION]
>
> - Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`


### 1.4.4. POST
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


## 1.5. /session.php
Permite **gestionar** las sesiones de los `Users`


### 1.5.1. DELETE
Elimina una `Session` para que no pueda ser utilizada


> [!CAUTION]
>
> - Si no se encuentra una Cookie con un `session_token` válido, se mostrará un error `401`

Si se elimina correctamente la `Session`, se retorna la siguiente **data**:

```jsonc
{
    "session_deleted": true
}
```


### 1.5.2. PATCH
Permite **actualizar** una `Session`. El `body` de la petición debe contener la siguiente estructura.

```jsonc
{
  "session_duration": 0
}
```

| Propiedad          | Descripción                                     |
| ------------------ | ----------------------------------------------- |
| `session_duration` | Tiempo de duración de la `Session` en segundos. |

> [!CAUTION]
>
> - Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`

Si se actualiza correctamente la `Session`, se actualiza la Cookie de sesión y se retorna la siguiente **data**:

```jsonc
{
    "username": "string",
    "master_password": "string",
    "session_duration": 0
}
```


### 1.5.3. POST
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
    "user_agent": "string"
}
```

> [!IMPORTANT]
> Se crea la Cookie `session_token` con la **sesión** del `User` recién creado


## 1.6. /user.php
Proporciona información sobre el `User` autenticado


### 1.6.1. DELETE
Permite **eliminar** un `User`. El cuerpo de la petición debe contener el `id` del `User` a eliminar


> [!CAUTION]
> Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`

Si se elimina con éxito, se retorna la siguiente **data**:

```jsonc
{
    "deleted": true
}
```


### 1.6.2. GET
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


## 1.7. /users.php
Proporciona información sobre los `Users`


### 1.7.1. GET
Permite **recuperar** uno o varios `User`

- **Filtros** disponibles:

    | *Query param*                | Descripción                                                                                  |
    | ---------------------------- | -------------------------------------------------------------------------------------------- |
    | `?username=<string>`         | Retorna un **único** `User` cuyo `username` **coincida completamente**. \**Case insensitive* |
    | `?name=<string>`             | Retorna **varios** `User` cuyo `name` **contenga** el valor. \**Case insensitive*            |
    | `?partial_username=<string>` | Retorna **varios** `User` cuyo `username` **contenga** el valor. \**Case insensitive*        |

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

- Data del filtro `name` y `partial_username`

    ```jsonc
    [
        {
            "username": "string",
            "name": "string",
        },
        ...
    ]
    ```


### 1.7.2. PATCH
Permite **actualizar** un `User`. El `body` de la petición debe contener la siguiente estructura. Todas las propiedades son opcionales (excepto `id`), si no se especifican, se mantendrán los mismos valores

```jsonc
{
  "id": "string",
  "name": "string",
  "master_password": "string",
  "recuperation_code": true
}
```

| Propiedad | Descripción |
| - | - |
| `id` | ID del `User` a actualizar |
| `name` | Nombre del `User` |
| `master_password` | Contraseña maestra |
| `recuperation_code` | Genera un nuevo código de recuperación |

> [!CAUTION]
>
> - Se necesita estar autenticado y ser el dueño. Si no se mostrará un error `401`
> - Si el contenido del cuerpo no cumple los requisitos, se mostrará un error `400`

Si los **datos** son **válidos**, se actualizará el `User` y se retornará la siguiente **data**:

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


### 1.7.3. POST
Permite **crear** un `User`. El `body` de la petición debe contener la siguiente estructura

```jsonc
{
  "username": "string",
  "name": "string",
  "master_password": "string"
}
```

| Propiedad         | Descripción                                                                                                                                                                                                                         | Requerido |
| ----------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------- |
| `username`        | Debe ser **único**. ***Regex*** que debe cumplir: `/^[a-zA-Z][a-zA-Z0-9_]{1,29}$/`                                                                                                                                                  | ✔️         |
| `name`            | Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres                                                                                                                                                         | ✔️         |
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
