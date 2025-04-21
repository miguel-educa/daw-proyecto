[Bulma]: https://bulma.io/
[Font Awesome]: https://fontawesome.com/icons
[Google Fonts]: https://fonts.google.com/
[Node JS]: https://nodejs.org
[Pinia]: https://pinia.vuejs.org/
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

Se ha decidido utilizar **Vue 3** para crear una **SPA** (Single Page Application). Este tipo de aplicaciones se **cargan una sola vez** en el navegador y, en lugar de recargar toda la página mientras se navega por las distintas rutas, se **actualiza dinámicamente** el contenido. Esto permite una navegación más fluida, ya que el navegador solo descarga y renderiza los recursos necesarios.

También se han utilizado las siguientes herramientas:

- [Vue Router] para la gestión de rutas SPA
- [Pinia] como almacén de estados entre componentes
- [Bulma] como framework de *CSS*
- Los iconos que se han utilizado están disponibles en [Font Awesome]
- Las fuentes utilizadas están disponibles en [Google Fonts]
    - [Winky Rough](https://fonts.google.com/specimen/Winky+Rough) como fuente del logo
    - [Inter](https://fonts.google.com/specimen/Inter) como fuente principal
    - [Fira Code](https://fonts.google.com/specimen/Fira+Code) como fuente auxiliar


# 2. Estructura del proyecto

```text
📄 .env-example (.env)
📄 index.html
📁 public
    📁 icons
    📁 img
📁 src
    📄 App.vue
    📄 config.js
    📄 main.js
    📁 assets
        📁 css
            📁 bulma
                📄 bulma.css
            📄 forms.css
            📄 main.css
            📄 pass-generator.css
    📁 components
        📄 AsideComponent.vue
        📄 HeaderComponent.vue
        📄 NotificationComponent.vue
        📁 forms
            📄 LoginFormComponent.vue
            📄 TwoFactorComponent.vue
            📄 AccountRecoveryComponent.vue
        📁 passGenerator
            📄 CharControlsComponent.vue
            📄 PassGeneratorComponent.vue
            📄 PassHistoryComponent.vue
            📄 PassLengthComponent.vue
    📁 layouts
        📄 AppLayout.vue
    📁 router
        📄 index.js
    📁 stores
        📄 passwordStore.js
        📄 userStore.js
    📁 tools
        📄 clipboard.js
        📄 passGenerator.js
        📄 password.js
        📄 theme.js
        📄 user.js
    📁 views
        📄 HomeView.vue
        📄 LoginView.vue
        📄 PassGeneratorView.vue
        📄 RegisterView.vue
        📄 VaultView.vue
```


# 3. [Directorios y Archivos](directories-files.md)
Explicación de los directorios y archivos


# 4. [Rutas](routes.md)
Rutas disponibles de la aplicación


---

[Regresar](../README.md)
