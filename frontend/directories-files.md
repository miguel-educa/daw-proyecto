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
        - [4.5.1. AsideComponent](#451-asidecomponent)
        - [4.5.2. HeaderComponent](#452-headercomponent)
        - [4.5.3. LoadingComponent](#453-loadingcomponent)
        - [4.5.4. ModalComponent](#454-modalcomponent)
        - [4.5.5. NotificationComponent](#455-notificationcomponent)
        - [4.5.6. Forms](#456-forms)
            - [4.5.6.1. AccountRecoveryComponent](#4561-accountrecoverycomponent)
            - [4.5.6.2. LoginFormComponent](#4562-loginformcomponent)
            - [4.5.6.3. RegisterFormComponent](#4563-registerformcomponent)
            - [4.5.6.4. RegisterRequirementsComponent](#4564-registerrequirementscomponent)
            - [4.5.6.5. TwoFactorComponent](#4565-twofactorcomponent)
        - [4.5.7. PassGenerator](#457-passgenerator)
            - [4.5.7.1. CharControlsComponent](#4571-charcontrolscomponent)
            - [4.5.7.2. PassGeneratorComponent](#4572-passgeneratorcomponent)
            - [4.5.7.3. PassHistoryComponent](#4573-passhistorycomponent)
            - [4.5.7.4. PassLengthComponent](#4574-passlengthcomponent)
        - [4.5.8. Vault](#458-vault)
            - [4.5.8.1. VaultComponent](#4581-vaultcomponent)
    - [4.6. Layouts](#46-layouts)
        - [4.6.1. AppLayout.vue](#461-applayoutvue)
    - [4.7. Router](#47-router)
        - [4.7.1. index](#471-index)
    - [4.8. Stores](#48-stores)
        - [4.8.1. passwordStore](#481-passwordstore)
        - [4.8.2. userStore](#482-userstore)
    - [4.9. Tools](#49-tools)
        - [4.9.1. clipboard](#491-clipboard)
        - [4.9.2. passGenerator](#492-passgenerator)
        - [4.9.3. password](#493-password)
        - [4.9.4. theme](#494-theme)
    - [4.10. user](#410-user)
    - [4.11. Views](#411-views)
        - [4.11.1. HomeView](#4111-homeview)
        - [4.11.2. LoginView](#4112-loginview)
        - [4.11.3. PassGeneratorView](#4113-passgeneratorview)
        - [4.11.4. RegisterView](#4114-registerview)
        - [4.11.5. VaultView](#4115-vaultview)


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
- [.htaccess](./public/.htaccess): Archivo de configuración del servidor web. Permite realizar redirecciones y configuraciones de seguridad


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

- [forms.css](./src/assets/css/forms.css): Estilos de los formularios
- [main.css](./src/assets/css/main.css): Archivo principal de estilos
- [pass-generator.css](./src/assets/css/pass-generator.css): Estilos del generador de contraseñas
- [bulma/bulma.css](./src/assets/css/bulma/bulma.css): Archivo principal de estilos del framework CSS [Bulma](https://github.com/jgthms/bulma/blob/main/css/bulma.css)


## 4.5. Components
Contiene los **componentes** utilizados en la aplicación


### 4.5.1. [AsideComponent](./src/components/AsideComponent.vue)
Contiene la barra lateral de la aplicación, disponible cuando hay un usuario autenticado


### 4.5.2. [HeaderComponent](./src/components/HeaderComponent.vue)
Contiene el Header de la aplicación, mostrando las opciones de navegación correspondientes dependiendo si hay un usuario autenticado o no


### 4.5.3. [LoadingComponent](./src/components/LoadingComponent.vue)
Componente para mostrar un indicador de carga mientras carga la aplicación por primera vez


### 4.5.4. [ModalComponent](./src/components/ModalComponent.vue)
Componente para mostrar modales


### 4.5.5. [NotificationComponent](./src/components/NotificationComponent.vue)
Componente para mostrar notificaciones temporales


### 4.5.6. Forms
Contiene componentes relacionados con formularios


#### 4.5.6.1. [AccountRecoveryComponent](./src/components/forms/AccountRecoveryComponent.vue)
Componente para la recuperación de una cuenta


#### 4.5.6.2. [LoginFormComponent](./src/components/forms/LoginFormComponent.vue)
Componente principal de la vista `LoginView`


#### 4.5.6.3. [RegisterFormComponent](./src/components/forms/RegisterFormComponent.vue)
Componente principal para la vista `RegisterView`


#### 4.5.6.4. [RegisterRequirementsComponent](./src/components/forms/RegisterRequirementsComponent.vue)
Componente auxiliar para mostrar los requisitos de registro


#### 4.5.6.5. [TwoFactorComponent](./src/components/forms/TwoFactorComponent.vue)
Componente para introducir el código de autenticación de dos factores si el usuario lo tiene habilitado


### 4.5.7. PassGenerator
Contiene los componentes de la vista `PassGeneratorView`


#### 4.5.7.1. [CharControlsComponent](./src/components/passGenerator/CharControlsComponent.vue)
Componente para modificar los caracteres mínimos de la contraseña a generar


#### 4.5.7.2. [PassGeneratorComponent](./src/components/passGenerator/PassGeneratorComponent.vue)
Componente principal del generador de la vista `PassGeneratorView`


#### 4.5.7.3. [PassHistoryComponent](./src/components/passGenerator/PassHistoryComponent.vue)
Componente para mostrar el historial de contraseñas generadas


#### 4.5.7.4. [PassLengthComponent](./src/components/passGenerator/PassLengthComponent.vue)
Componente para modificar la longitud de la contraseña


### 4.5.8. Vault
Contiene los componentes para la vista `VaultView`


#### 4.5.8.1. [VaultComponent](./src/components/vault/VaultComponent.vue)
Componente principal para la vista `VaultView`


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


### 4.8.1. [passwordStore](./src/stores/passwordStore.js)
Almacena los estados del generador de contraseñas (longitud, caracteres mínimos...)


### 4.8.2. [userStore](./src/stores/userStore.js)
Almacena los estados de la sesión de un usuario


## 4.9. Tools
Contiene herramientas que se utilizan en la aplicación


### 4.9.1. [clipboard](./src/tools/clipboard.js)
Permite copiar texto al portapapeles


### 4.9.2. [passGenerator](./src/tools/passGenerator.js)
Contiene clases y métodos para el generador de contraseñas (configuración, caracteres, historial...)


### 4.9.3. [password](./src/tools/password.js)
Contiene métodos para comprobar la validez de una contraseña


### 4.9.4. [theme](./src/tools/theme.js)
Contiene métodos para establecer y editar distintos parámetros del tema de la aplicación (modo oscuro/claro, posición de la barra lateral...)


## 4.10. [user](./src/tools/user.js)
Contiene métodos para realizar operaciones con la API realizadas con el usuario


## 4.11. Views
Contiene las **vistas** (páginas) de la aplicación. Cada vista suele tener asociada una ruta mediante **Vue Router**


### 4.11.1. [HomeView](./src/views/HomeView.vue)
Es la vista de la página de **inico** de la aplicación


### 4.11.2. [LoginView](./src/views/LoginView.vue)
Es la vista de la página de **inicio de sesión**


### 4.11.3. [PassGeneratorView](./src/views/PassGeneratorView.vue)
Es la vista de la página **Generador de contraseñas**


### 4.11.4. [RegisterView](./src/views/RegisterView.vue)
Es la vista de la página de **registro** de un usuario


### 4.11.5. [VaultView](./src/views/VaultView.vue)
Es la vista de la página **Baúl personal**


---

[Regresar](./README.md)
