[Regresar](./README.md)

**Contenidos**

- [1. .env-example](#1-env-example)
- [2. index.html](#2-indexhtml)
- [3. Public](#3-public)
- [4. Src](#4-src)
    - [4.1. App.vue](#41-appvue)
    - [4.2. config](#42-config)
    - [4.3. main.js](#43-mainjs)
    - [4.4. Assets](#44-assets)
        - [4.4.1. CSS](#441-css)
    - [4.5. Components](#45-components)
        - [4.5.1. HeaderComponent](#451-headercomponent)
        - [4.5.2. AsideComponent](#452-asidecomponent)
    - [4.6. Layouts](#46-layouts)
        - [4.6.1. AppLayout.vue](#461-applayoutvue)
    - [4.7. Router](#47-router)
        - [4.7.1. index](#471-index)
    - [4.8. Stores](#48-stores)
        - [4.8.1. userStore](#481-userstore)
    - [4.9. Tools](#49-tools)
        - [4.9.1. theme](#491-theme)
    - [4.10. user](#410-user)
    - [4.11. Views](#411-views)
        - [4.11.1. HomeView](#4111-homeview)


# 1. [.env-example](./.env-example)
Contiene variables de entorno del proyecto. Se debe realizar una **copia** de este archivo y **renombrarlo** a `.env` para que se pueda utilizar en el entorno de desarrollo o producción.

Se **deben configurar** las siguientes variables:

- `VITE_API_URL`: IP o nombre del servidor de la API. Si la api se encuentra en el mismo servidor que el front, se puede introducir la ruta relativa de la api (por ejemplo: `/api`)


# 2. [index.html](./index.html)
Es el **archivo principal** que se carga cuando se inicia la aplicación. El contenido de la aplicación se inyectará mediante **JavaScript** (`<script type="module" src="/src/main.js"></script>`) en el elemento **HTML** `<div id="app"></div>`


# 3. Public
Contiene los **archivos estáticos** públicos que se utilizan en la aplicación (imágenes, iconos...), siendo accesibles desde la raíz (`/`) de la aplicación

- [icons/](./public/icons/): Contiene iconos
- [img/](./public/img/): Contiene imágenes


# 4. Src
Es la raíz del proyecto, contiene el **código fuente** de la aplicación


## 4.1. [App.vue](./src/App.vue)
Es el **componente raíz** de la aplicación. Toda la estructura y la lógica básica debe estar anidada dentro de este archivo


## 4.2. [config](./src/config.js)
Contiene la configuración de la aplicación


## 4.3. [main.js](./src/main.js)
Es el **archivo principal** de la aplicación donde se configura y se monta la aplicación ***Vue***


## 4.4. Assets
Almacena **archivos** que se utilizarán en la aplicación pero que necesitan ser **procesados** y **optimizados** (imágenes grandes, CSS, fuentes...) para mejorar la carga de recursos


### 4.4.1. CSS
Archivos CSS para proporcionar estilos a la aplicación

- [main.css](./src/assets/css/main.css): Archivo principal
- [bulma/bulma.css](./src/assets/css/bulma/bulma.css): Archivo principal del framework CSS [Bulma](https://github.com/jgthms/bulma/blob/main/css/bulma.css)


## 4.5. Components
Contiene los **componentes** utilizados en la aplicación


### 4.5.1. [HeaderComponent](./src/components/HeaderComponent.vue)
Contiene el Header de la aplicación, mostrando las opciones de navegación correspondientes dependiendo si hay un usuario autenticado o no


### 4.5.2. [AsideComponent](./src/components/AsideComponent.vue)
Contiene la barra lateral de la aplicación, disponible cuando hay un usuario autenticado


## 4.6. Layouts
Contiene el *layout* de la aplicación


### 4.6.1. [AppLayout.vue](./src/components/layouts/AppLayout.vue)
Es el *layout* principal de la aplicación. Proporciona la misma estructura para las diferentes vistas, teniendo en cuenta si hay un usuario autenticado o el tipo de dispositivo


## 4.7. Router
Contiene la **definición** y **configuración** de rutas mediante **Vue Router**


### 4.7.1. [index](./src/router/index.js)
Es el **archivo de configuración** del enrutador. En él se definen las rutas y vistas disponibles de la aplicación


## 4.8. Stores
Contiene los almacenes de estado de la aplicación. Permite sincronizar estados desde distintos componentes


### 4.8.1. [userStore](./src/stores/userStore.js)
Almacena los estados de la sesión de un usuario


## 4.9. Tools
Contiene herramientas que se utilizan en la aplicación


### 4.9.1. [theme](./src/tools/theme.js)
Contiene métodos para establecer y editar distintos parámetros del tema de la aplicación (modo oscuro/claro, posición de la barra lateral...)


## 4.10. [user](./src/tools/user.js)
Contiene métodos para realizar operaciones con la API realizadas con el usuario


## 4.11. Views
Contiene las **vistas** (páginas) de la aplicación. Cada vista suele tener asociada una ruta mediante **Vue Router**


### 4.11.1. [HomeView](./src/views/HomeView.vue)
Es la vista de la página de inico de la aplicación


---

[Regresar](./README.md)
