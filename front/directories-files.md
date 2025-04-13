[Regresar](./README.md)

**Contenidos**

- [1. index.html](#1-indexhtml)
- [2. Public](#2-public)
- [3. Src](#3-src)
    - [3.1. App.vue](#31-appvue)
    - [3.2. main.js](#32-mainjs)
    - [3.3. Assets](#33-assets)
    - [3.4. Components](#34-components)
    - [3.5. Router](#35-router)
        - [3.5.1. index.js](#351-indexjs)
    - [3.6. Views](#36-views)


# 1. [index.html](./index.html)
Es el **archivo principal** que se carga cuando se inicia la aplicación. El contenido de la aplicación se inyectará mediante **JavaScript** (`<script type="module" src="/src/main.js"></script>`) en el elemento **HTML** `<div id="app"></div>`


# 2. Public
Contiene los **archivos estáticos** que se utilizan en la aplicación (imágenes, fuentes, iconos..), siendo accesibles desde la raíz (`/`) de la aplicación


# 3. Src
Es la raíz del proyecto, contiene el **código fuente** de la aplicación


## 3.1. [App.vue](./src/App.vue)
Es el **componente raíz** de la aplicación. Toda la estructura y la lógica básica debe estar anidada dentro de este archivo


## 3.2. [main.js](./src/main.js)
Es el **archivo principal** de la aplicación donde se configura y se monta la aplicación ***Vue***


## 3.3. Assets
Almacena **archivos** que se utilizarán en la aplicación pero que necesitan ser **procesados** y **optimizados** (imágenes grandes, CSS...) para mejorar la carga de recursos


## 3.4. Components
Contiene los **componentes reutilizables**


## 3.5. Router
Contiene la **definición** y **configuración** de rutas mediante **Vue Router**


### 3.5.1. [index.js](./src/router/index.js)
Es el **archivo de configuración** del enrutador. En él se definen las rutas y vistas disponibles de la aplicación


## 3.6. Views
Contiene las **vistas** (páginas) de la aplicación. Cada vista suele tener asociada una ruta mediante **Vue Router**


---

[Regresar](./README.md)
