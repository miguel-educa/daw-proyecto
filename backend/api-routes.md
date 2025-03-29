[Regresar](./README.md)

**Contenidos**

- [1. Rutas API](#1-rutas-api)
    - [1.1. /users.php](#11-usersphp)
        - [1.1.1. GET](#111-get)


# 1. Rutas API
La **estructura** del JSON de todas las **respuestas** de la API es la siguiente:

```json
{
  "service_name": "Pass Warriors",
  "success": true|false,
  "data": {}|[ {} ]|null,
  "errors": [ "string" ]|null
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

---

[Regresar](./README.md)
