<script setup>
import ThemeTools from '@/tools/theme.js'

const changeThemeDark = () => {
  ThemeTools.setTheme('dark')
}

const changeThemeLight = () => {
  ThemeTools.setTheme('light')
}
</script>

<template>
  <h1 class="title home-title title-font has-text-centered">Pass Warriors</h1>

  <section class="section home-section">
    <h2 class="title is-1 has-text-centered home-text">Descripción</h2>

    <div class="columns is-vcentered is-desktop">
      <div class="column is-one-quarter is-full-touch has-text-centered">
        <img src="/img/pass-warriors-logo.png" alt="Logo de Pass Warriors" class="home-logo" />
      </div>

      <div class="column">
        <div class="content">
          <p class="home-text is-size-5">
            Pass Warriors es un proyecto realizado por
            <span class="is-underlined">Miguel Guerrero</span> como Proyecto de Fin de
            <span class="is-underlined">Grado Superior</span> en
            <span class="is-underlined">Desarrollo de Aplicaciones Web</span> (DAW). Puede encontrar
            más información del proyecto y ver el código fuente en
            <a
              href="https://github.com/miguel-educa/daw-proyecto/"
              title="Abrir repostorio de GitHub en una nueva pestaña"
              target="_blank"
              >GitHub</a
            >.
          </p>
          <p class="home-text is-size-5">
            El objetivo del desarrollo de este proyecto es aplicar los conocimientos adquiridos
            durante el curso mediante la creación de una aplicación web que cubra una
            <span class="is-underlined">necesidad real</span>
            de un usuario: la gestión de contraseñas de manera segura, entre otras funciones.
          </p>
          <p class="home-text is-size-5">Las funciones principales de la aplicación son:</p>
          <ul>
            <li class="home-text">
              Gestión de contraseñas accediendo mediante una única contraseña maestra
            </li>
            <ul>
              <li class="home-text">Crear, editar y eliminar elementos</li>
              <li class="home-text">
                Los elementos pueden almacenar usuario, contraseña, hasta 5 links y notas
                personalizadas
              </li>
              <li class="home-text">Organización en carpetas</li>
              <li class="home-text">Compartir contraseñas con otros usuarios</li>
            </ul>
            <li class="home-text">Generación de contraseñas seguras</li>
            <ul>
              <li class="home-text">
                Personalización de carecteres (tipo y cantidad mínima) y longitud de la contraseña
              </li>
              <li class="home-text">Historial de generación</li>
            </ul>
            <li class="home-text">Auditoría de contraseñas</li>
            <ul>
              <li class="home-text">
                Comprobar la seguridad de las contraseñas, pudiendo ver un resumen completo de cada
                contraseña
              </li>
              <li class="home-text">Visualizar contraseñas repetidas</li>
              <li class="home-text">
                Comprobar contraseñas filtradas en filtraciones masivas de datos
              </li>
            </ul>
            <li class="home-text">Personalización</li>
            <ul>
              <li class="home-text">Personalización de la interfaz</li>
              <li class="home-text">Modificación de datos de la cuenta</li>
            </ul>
            <li class="home-text">Seguridad</li>
            <ul>
              <li class="home-text">Duración de sesión variable e identificación de dispositivo</li>
              <li class="home-text">Autenticación de doble factor mediante código temporal</li>
              <li class="home-text">Recuperación de cuenta mediante código de recuperación</li>
            </ul>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="section home-section">
    <h2 class="title is-1 has-text-centered home-text">Baúl</h2>

    <p class="home-text is-size-5">
      El baúl permite al usuario almacenar de manera segura sus contraseñas y acceder recordando
      únicamente la contraseña maestra. Cada elemento almacenado puede contener un nombre de
      identificación, un nombre de usuario, una contraseña, 0 o hasta 5 URLs y notas personalizadas.
      En caso de elementos compartidos, se podrán seleccionar los usuarios con los que se compartirá
      el elemento (pudiendo ver los datos, pero sin edición). También se puede acceder al generador
      de contraseñas desde el formulario de creación.
    </p>

    <hr class="separator home-separator" />

    <div class="columns is-desktop is-5 mt-4">
      <div class="column">
        <h3 class="title is-3 has-text-centered">Baúl personal</h3>

        <p class="home-text is-size-5">
          Almacena las contraseñas privadas del usuario. Se puede filtrar por carpetas, pudiéndose
          seleccionar 1 o más, y también se dispone de un buscador para realizar búsquedas por
          nombre de elemento.
        </p>

        <div class="has-text-centered">
          <img src="/img/vault.png" alt="Baúl privado" class="img mt-4" />
          <img src="/img/new-element.png" alt="Creación de un nuevo elemento" class="img mt-4" />
        </div>
      </div>

      <div class="column">
        <hr class="separator home-separator is-hidden-desktop" />

        <h3 class="title is-3 has-text-centered">Baúl compartido</h3>

        <p class="home-text is-size-5">
          Almacena las contraseñas compartidas con otros usuarios o que otros usuarios hayan
          compartido con nosotros, pudiéndose filtrar entre ambos tipos. Al igual que en el baúl
          personal, también se dispone de un buscador.
        </p>
        <div class="has-text-centered">
          <img src="/img/shared-vault.png" alt="Baúl compartido" class="img mt-4" />
          <img
            src="/img/new-shared-element.png"
            alt="Creación de un nuevo elemento compartido"
            class="img mt-4"
          />
        </div>
      </div>
    </div>
  </section>

  <section class="section home-section">
    <h2 class="title is-1 has-text-centered home-text">Generador de contraseñas</h2>

    <p class="home-text is-size-5">
      El generador de contraseñas permite generar contraseñas seguras rápidamente. Se pueden generar
      contraseñas desde 5 hasta 50 caracteres. Además, se pueden personalizar los tipos de
      caracteres que deben contener y su cantidad mínima (desde 0 hasta 10). El generador es
      accesible tanto por usuarios autenticados como anónimos.
    </p>

    <div class="has-text-centered">
      <img src="/img/pass-generator.png" alt="Generador de contraseñas" class="img mt-4" />
    </div>

    <p class="home-text is-size-5 mt-4">
      Se almacena un historial de las últimas 75 contraseñas generadas. Cada dispositivo dispone de
      su propio historial y no se comparten entre ellos, siendo accesible incluso después de cerrar
      el navegador. Se puede vaciar el historial cuando se desee.
    </p>

    <div class="has-text-centered">
      <img src="/img/pass-generator-history.png" alt="Generador de contraseñas" class="img mt-4" />
    </div>
  </section>

  <section class="section home-section">
    <h2 class="title is-1 has-text-centered home-text">Auditoría de contraseñas</h2>

    <p class="home-text is-size-5">
      En este apartado se puede consultar distintos apartados relacionados con la seguridad de las
      contraseñas.
    </p>

    <hr class="separator home-separator" />

    <h3 class="title is-3 has-text-centered mt-4">Resumen de seguridad</h3>

    <div class="content">
      <p class="home-text is-size-5">
        Muestra un resumen de los requisitos de seguridad de todas las contraseñas. Los requisitos
        que se tienen en cuenta son:
      </p>
      <ul>
        <li class="home-text">Longitud de la contraseña</li>
        <ul>
          <li class="home-text">Bajo: menos de 8 caracteres</li>
          <li class="home-text">Medio: de 8 a 14 caracteres</li>
          <li class="home-text">Alto: más de 14 caracteres</li>
        </ul>
        <li class="home-text">Caracteres mínimos</li>
        <ul>
          <li class="home-text">Contiene al menos 2 letras minúsculas</li>
          <li class="home-text">Contiene al menos 2 letras mayúsculas</li>
          <li class="home-text">Contiene al menos 2 números</li>
          <li class="home-text">Contiene al menos 2 caracteres especiales</li>
        </ul>
        <li class="home-text">No contiene 3 o más caracteres repetidos</li>
        <li class="home-text">
          No contiene secuencias de caracteres (números o letras consecutivos). Por ejemplo: abc,
          567, ZYXW, 3210...
        </li>
      </ul>
    </div>

    <div class="content">
      <p class="home-text is-size-5">
        El resumen se divide en 3 secciones según el nivel de seguridad de la contraseña:
      </p>
      <ul>
        <li class="home-text">Seguridad baja (necesita revisión urgente)</li>
        <li class="home-text">Seguridad media (revisión recomendable)</li>
        <li class="home-text">Seguridad alta</li>
      </ul>
    </div>

    <div class="has-text-centered">
      <img src="/img/audit-low-security.png" alt="Generador de contraseñas" class="img mt-4" />

      <img src="/img/audit-medium-security.png" alt="Generador de contraseñas" class="img mt-4" />

      <img src="/img/audit-high-security.png" alt="Generador de contraseñas" class="img mt-4" />
    </div>

    <hr class="separator home-separator" />

    <h3 class="title is-3 has-text-centered mt-4">Control de contraseñas duplicadas</h3>

    <p class="home-text is-size-5">
      En este apartado se muestran las contraseñas que se utilizan en distintos elementos,
      pudiéndose acceder directamente a los elementos para su edición.
    </p>

    <div class="has-text-centered">
      <img src="/img/audit-duplicate.png" alt="Generador de contraseñas" class="img mt-4" />
    </div>

    <hr class="separator home-separator" />

    <h3 class="title is-3 has-text-centered mt-4">Filtración de contraseñas</h3>

    <p class="home-text is-size-5">
      En este apartado se muestran las contraseñas que han sido filtradas en alguna filtración de
      datos masiva. Para ello se utiliza la API de
      <a href="https://haveibeenpwned.com/Passwords" title="API de HaveIBeenPwned" target="_blank"
        >HaveIBeenPwned</a
      >.
    </p>

    <div class="has-text-centered">
      <img src="/img/audit-filter.png" alt="Generador de contraseñas" class="img mt-4" />
    </div>
  </section>

  <section class="section home-section">
    <h2 class="title is-1 has-text-centered home-text">Ajustes y preferencias</h2>

    <p class="home-text is-size-5">
      En este apartado el usuario puede alternar entre el modo oscuro y el modo claro de la interfaz
      (por defecto se utiliza el tema predeterminado del navegador). También, en dispositivos
      grandes que contienen una barra de navegación lateral, el usuario pude elegir en que lado se
      debe mostrar, izquierdo o derecho. La aplicación tiene un diseño adaptativo para cualquier
      tipo de dispositivo, pantalla o resolución.
    </p>

    <div class="buttons mt-4 is-centered">
      <button class="button is-dark" @click="changeThemeDark">Cambiar a modo oscuro</button>
      <button class="button is-light" @click="changeThemeLight">Cambiar a modo claro</button>
    </div>

    <div class="columns is-desktop is-vcentered">
      <div class="column has-text-centered">
        <img src="/img/theme-dark.png" alt="Ajustes" class="img mt-4" />
        <img src="/img/aside-left.png" alt="Ajustes" class="img mt-4 is-hidden-touch" />
        <img src="/img/theme-light.png" alt="Ajustes" class="img mt-4 is-hidden-desktop" />
      </div>
      <div class="column has-text-centered">
        <img src="/img/theme-light.png" alt="Ajustes" class="img mt-4 is-hidden-touch" />
        <img src="/img/aside-left.png" alt="Ajustes" class="img mt-4 is-hidden-desktop" />
        <img src="/img/aside-right.png" alt="Ajustes" class="img mt-4" />
      </div>
    </div>

    <hr class="separator home-separator" />

    <h3 class="title is-3 has-text-centered mt-4">Seguridad de la cuenta</h3>

    <div class="content">
      <p class="home-text is-size-5">
        Para acceder a las partes privadas de la aplicación, es necesario registrarse o iniciar
        sesión. Para ello, se debe introducir el nombre de usuario y la contraseña maestra. En caso
        de no recordar la contraseña, se puede recuperar la cuenta mediante el código de
        recuperación. Dicho código, se muestra al registrarse por primera vez o se puede ver en los
        ajustes de la cuenta. Es importante almacenarlo en un lugar seguro, ya que es la única forma
        de poder recuperar la cuenta si se olvida la contraseña maestra. También se puede activar la
        autenticación de doble factor, aumentando la seguridad de la cuenta al solicitarse un código
        temporal generado en aplicaciones especializadas (<span class="is-underlined"
          >Google Authenticator</span
        >, <span class="is-underlined">Microsoft Authenticator</span>,
        <span class="is-underlined">Authy</span>...) en cada inicio de sesión.
      </p>

      <p class="home-text is-size-5">
        Se puede personalizar la duración de la sesión cada vez que se inicie sesión (al registrarse
        la duración es de 1 hora). Las posibles duraciones son:
      </p>

      <ul>
        <li class="home-text">
          15 minutos (ideal en dispositivos no personales o de uso compartido)
        </li>
        <li class="home-text">
          30 minutos (ideal en dispositivos no personales o de uso compartido)
        </li>
        <li class="home-text">1 hora (ideal en dispositivos no personales o de uso compartido)</li>
        <li class="home-text">4 horas (ideal en dispositivos no personales)</li>
        <li class="home-text">8 horas (ideal en dispositivos no personales)</li>
        <li class="home-text">1 día (ideal en dispositivos personales o de uso privado)</li>
        <li class="home-text">
          1 semana (ideal en dispositivos personales o de uso privado aunque menor seguridad a
          accesos no autorizados)
        </li>
        <li class="home-text">
          1 mes (ideal en dispositivos personales o de uso privado aunque menor seguridad a accesos
          no autorizados)
        </li>
        <li class="home-text">
          3 meses (ideal en dispositivos personales o de uso privado aunque menor seguridad a
          accesos no autorizados)
        </li>
      </ul>

      <div class="columns is-desktop is-vcentered">
        <div class="column has-text-centered">
          <img src="/img/register.png" alt="Imagen de registro de sesión" class="img mt-4" />
          <img src="/img/login.png" alt="Imagen de inicio de sesión" class="img mt-4" />
        </div>
        <div class="column is-one-third is-full-touch has-text-centered">
          <img src="/img/recovery-code.png" alt="Imagen de inicio de sesión" class="img mt-4" />
          <img src="/img/account-recovery.png" alt="Imagen de inicio de sesión" class="img mt-4" />
          <img src="/img/2fa.png" alt="Imagen de inicio de sesión" class="img mt-4" />
        </div>
      </div>

      <p class="home-text is-size-5">
        En los ajustes de cuenta se pueden realizar las siguientes acciones:
      </p>

      <ul>
        <li class="home-text">
          Editar el nombre (el nombre de usuario con el que se inicia sesión no se puede modificar)
        </li>
        <li class="home-text">
          Generar un nuevo código de recuperación, invalidando el código anterior
        </li>
        <li class="home-text">Editar la contraseña maestra</li>
        <li class="home-text">
          Cambiar la duración de la sesión actual (mismas opciones que al iniciar sesión)
        </li>
        <li class="home-text">
          Activar o desactivar la autenticación de doble factor mediante código temporal
        </li>
        <li class="home-text">Vaciar baúl personal (eliminando carpetas y contraseñas)</li>
        <li class="home-text">
          Vaciar baúl compartido (eliminando las contraseñas compartidas, pero no las que otros
          usuarios comparten)
        </li>
        <li class="home-text">
          Eliminar la cuenta (elimina todos los datos, incluyendo carpetas y contraseñas privadas y
          compartidas)
        </li>
      </ul>
    </div>
  </section>

  <section class="section home-section">
    <h2 class="title is-1 has-text-centered home-text">¡Pruébelo!</h2>

    <p class="home-text is-size-5">
      ¡Disfruta de la aplicación! Recuerda que es un proyecto educactivo con finalidad didáctica y
      no tiene como objetivo ser un servicio real.
    </p>

    <div class="buttons is-centered">
      <a href="/register" title="Registrarse" class="button mt-4">Registrarse</a>
    </div>
  </section>
</template>
