[Regresar](./README.md)

**Contenidos**

- [1. Rutas API](#1-rutas-api)
    - [1.1. /user.php](#11-userphp)
        - [1.1.1. GET](#111-get)
    - [1.2. /users.php](#12-usersphp)
        - [1.2.1. GET](#121-get)
        - [1.2.2. POST](#122-post)
    - [1.3. /sessions.php](#13-sessionsphp)
        - [1.3.1. DELETE](#131-delete)
        - [1.3.2. POST](#132-post)


# 1. Rutas API
La **estructura** del JSON de todas las **respuestas** de la API es la siguiente:

```json
{
  "service_name": "Pass Warriors",
  "success": true, // `true` o `false`
  "data": {}, // `{}`, `[ {} ]` o `null`
  "errors": [ "string" ] // `array` o `null`
}
```

- `service_name`: Nombre del servicio
- `success`: Muestra si la respuesta fue existosa (`true`) o hubo un error en la petición (`false`)
- `data`: `Objeto` con el resultado obtenido o `Array` con los objetos obtenidos. `null` si se produce algún error
- `errors`: `Array` con los mensajes de errores producidos. `null` si no se produjeron errores


## 1.1. /user.php
Proporciona información sobre el `User` autenticado


### 1.1.1. GET
Permite **recuperar** información sobre el `User` autenticado

- **Filtros** disponibles (*Query params*):
    - `?confidential_data=<boolean>`: Si se establece en `true`, se devolverá información **sensible**. Si no se encuentra el filtro o su valor es `false`, no se mostrará esta información

> [!CAUTION]
>
> - Si no exisete la Cookie con el `session_token` o éste no es válido, ha expirado o el *user agent* del dispositivo no coincide, se mostrará un error `401`
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

- `id`: GUID
- `username`: Nombre de usuario
- `name`: Nombre a mostrar
- `master_password_edited_at`: *Timestamp* de la última modificación de la contraseña maestra
- `recuperation_code`: Código de recuperación de la cuenta
- `recuperation_code_edited_at`: *Timestamp* de la última modificación del código de recuperación


## 1.2. /users.php
Proporciona información sobre los `Users`


### 1.2.1. GET
Permite **recuperar** uno o varios `User`

- **Filtros** disponibles (*Query params*):
    - `?username=<string>`: Retorna un **único** `User` cuyo `username` **coincida completamente**. \**Case insensitive*
    - `?name=<string>`: Retorna **varios** `User` cuyo `name` **contenga** el valor. \**Case insensitive*

> [!CAUTION]
>
> - Se **debe utilizar** uno de los filtros. Si no se utiliza ninguno, se mostrará un error `400`
> - Sólo se puede utilizar **un filtro** en cada petición. Si se encuentran varios filtros, se utilizará el filtro según el orden espicificado en *filtros disponibles*
> - Si **no** se **encontraron** resultados, se mostrará un error `404`


- Data del filtro `username`

    ```json
    {
        "username": "string",
        "name": "string",
    }
    ```

- Data del filtro `name`

    ```json
    [
        {
            "username": "string",
            "name": "string",
        },
        ...
    ]
    ```


### 1.2.2. POST
Permite **crear** un `User`. El `body` de la petición debe contener la siguiente estructura

```json
{
  "username": "string",
  "name": "string",
  "master_password": "string"
}
```

- `username`: Debe ser **único**. ***Regex*** que debe cumplir: `/^[a-zA-Z][a-zA-Z0-9_]{1,29}$/` (\***Requerido**)
- `name`: Puede contener **cualquier carácter**. Longitud entre `1` y `50` caracteres (\***Requerido**)
- `master_password`: Longitud entre `8` y `50` caracteres. Debe **contener** al menos **una** letra **minúscula** y **una** letra **mayúscula** (alfabeto inglés), **un número** y **alguno** de los siguientes **símbolos especiales** `_-,;!.@*&#%+$/`. (\***Requerido**)

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

- `id`: GUID
- `username`: Nombre de usuario
- `name`: Nombre a mostrar
- `recuperation_code`: Código de recuperación de la cuenta
- `master_password_edited_at`: *Timestamp* de la última modificación de la contraseña maestra
- `recuperation_code_edited_at`: *Timestamp* de la última modificación del código de recuperación

> [!IMPORTANT]
> Se crea la Cookie `session_token` con la **sesión** del `User` recién creado


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

```json
{
  "username": "string",
  "master_password": "string",
  "session_duration": 0 // Tiempo en segundos
}
```

- `username`: Nombre de usuario (\***Requerido**)
- `master_password`: Contraseña maestra (\***Requerido**)
- `session_duration`: Tiempo de duración de la `Session` en segundos. Se aceptan los siguientes valores:
    - `3600`: Duración de `1 hora`
    - `86400`: Duración de `1 día`
    - `604800`: Duración de `7 días`
    - `2592000`: Duración de `30 días`
    - `7776000`: Duración de `90 días`

> [!INFO]
> Si no se especifica o contiene un valor inválido, se establece la duración de la `Session` en `1 hora`

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

- `id`: GUID
- `user_id`: Id del `User` que ha creado la `Session`
- `name`: Nombre del `User` que ha creado la `Session`
- `token_created_at`: *Timestamp* de la creación de la `Session`
- `token_expires_at`: *Timestamp* de la expiración de la `Session`
- `revoked`: `true` si la `Session` ha sido revocada, `false` si no
- `user_agent`: *User Agent* del dispositivo que ha creado la `Session`

> [!IMPORTANT]
> Se crea la Cookie `session_token` con la **sesión** del `User` recién creado


---

[Regresar](./README.md)
