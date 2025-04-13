[Node JS]: https://nodejs.org
[PNPM]: https://pnpm.io/
[Vue 3]: https://vuejs.org/
[Vue Router]: https://router.vuejs.org/


[Regresar](../README.md)

**Contenidos**

- [1. Front](#1-front)
- [2. Estructura del proyecto](#2-estructura-del-proyecto)
- [3. Directorios y Archivos](#3-directorios-y-archivos)
- [4. Rutas](#4-rutas)


# 1. Front
***Frontend*** de la aplicación. Permite al usuario **interactuar** con la aplicación. Se ha decido utilizar [Vue 3] como framework de desarrollo. Para ello, es necesario utilizar [Node JS] como entorno de ejecución y [PNPM] como gestor de paquetes

Se ha decidido utilizar **Vue 3** para crear una **SPA** (Single Page Application). Este tipo de aplicaciones se **cargan una sola vez** en el navegador y, en lugar de recargar toda la página mientras se navega por las distintas rutas, se **actualiza dinámicamente** el contenido. Esto permite una navegación más fluida, ya que el navegador solo descarga y renderiza los recursos necesarios. Para la gestión de las rutas se ha utilizado [Vue Router]


# 2. Estructura del proyecto

```text
📄 index.html
📁 public
📁 src
    📄 App.vue
    📄 main.js
    📁 assets
    📁 components
    📁 router
    📁 views
```


# 3. [Directorios y Archivos](directories-files.md)
Explicación de los directorios y archivos


# 4. [Rutas](routes.md)
Rutas disponibles de la aplicación


---

[Regresar](../README.md)
