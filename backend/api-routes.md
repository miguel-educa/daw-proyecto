[Regresar](./README.md)

**Contenidos**

- [1. Rutas API](#1-rutas-api)
    - [1.1. /users.php](#11-usersphp)
        - [1.1.1. GET](#111-get)
        - [1.1.1. POST](#111-post)


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


## 1.1. /users.php
Proporciona información sobre los `Users`


### 1.1.1. GET
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


### 1.1.1. POST
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


---

[Regresar](./README.md)
