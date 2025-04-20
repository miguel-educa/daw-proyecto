[Regresar](./README.md)

**Contenidos**

- [1. .env-example](#1-env-example)
- [2. index.html](#2-indexhtml)
- [3. Public](#3-public)
- [4. Src](#4-src)
    - [4.1. App.vue](#41-appvue)
    - [4.2. main.js](#42-mainjs)
    - [4.3. Assets](#43-assets)
    - [4.4. Components](#44-components)
    - [4.5. Router](#45-router)
        - [4.5.1. index.js](#451-indexjs)
    - [4.6. Views](#46-views)


# 1. [.env-example](./.env-example)
Contiene variables de entorno del proyecto. Se debe realizar una **copia** de este archivo y **renombrarlo** a `.env` para que se pueda utilizar en el entorno de desarrollo o producción.

Se **deben configurar** las siguientes variables:

- `VITE_API_URL`: IP o nombre del servidor de la API. Si la api se encuentra en el mismo servidor que el front, se puede introducir la ruta relativa de la api (por ejemplo: `/api`)


# 2. [index.html](./index.html)
Es el **archivo principal** que se carga cuando se inicia la aplicación. El contenido de la aplicación se inyectará mediante **JavaScript** (`<script type="module" src="/src/main.js"></script>`) en el elemento **HTML** `<div id="app"></div>`


# 3. Public
Contiene los **archivos estáticos** públicos que se utilizan en la aplicación (imágenes, iconos...), siendo accesibles desde la raíz (`/`) de la aplicación


# 4. Src
Es la raíz del proyecto, contiene el **código fuente** de la aplicación


## 4.1. [App.vue](./src/App.vue)
Es el **componente raíz** de la aplicación. Toda la estructura y la lógica básica debe estar anidada dentro de este archivo


## 4.2. [main.js](./src/main.js)
Es el **archivo principal** de la aplicación donde se configura y se monta la aplicación ***Vue***


## 4.3. Assets
Almacena **archivos** que se utilizarán en la aplicación pero que necesitan ser **procesados** y **optimizados** (imágenes grandes, CSS...) para mejorar la carga de recursos


## 4.4. Components
Contiene los **componentes reutilizables**


## 4.5. Router
Contiene la **definición** y **configuración** de rutas mediante **Vue Router**


### 4.5.1. [index.js](./src/router/index.js)
Es el **archivo de configuración** del enrutador. En él se definen las rutas y vistas disponibles de la aplicación


## 4.6. Views
Contiene las **vistas** (páginas) de la aplicación. Cada vista suele tener asociada una ruta mediante **Vue Router**


---

[Regresar](./README.md)
