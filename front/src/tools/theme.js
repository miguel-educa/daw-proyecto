class ThemeTools {
  /**
   * Carga el tema correspondiente según la preferencia del usuario
   */
  static loadTheme() {
    const theme = this.getCurrentTheme()

    if (theme === 'dark') {
      document.body.setAttribute('data-theme', 'dark')
    } else {
      document.body.setAttribute('data-theme', 'light')
    }
  }

  /**
   * Obtiene el tema actual del usuario:
   * - Almacenado en `localStorage`
   * - Por defecto el tema del navegador
   *
   * @returns {string} Retorna `dark` o `light`
   */
  static getCurrentTheme = () => {
    const currentTheme = localStorage.getItem('theme')
    if (currentTheme === 'dark' || currentTheme === 'light') return currentTheme

    const isDarkPrefer = window.matchMedia('(prefers-color-scheme: dark)').matches
    return isDarkPrefer ? 'dark' : 'light'
  }

  /**
   * Establece el tema del usuario y lo almacena en `localStorage`
   *
   * @param {string} newTheme `dark` o `light`
   */
  static setTheme = async (newTheme) => {
    if (newTheme !== 'dark' && newTheme !== 'light') return

    localStorage.setItem('theme', newTheme)

    this.loadTheme()
  }

  /**
   * Obtiene la posición de la barra lateral:
   * - Almacenado en `localStorage`
   * - Por defecto `left`
   *
   * @returns {string} Retorna `left` o `right`
   */
  static getAside = () => {
    const currentAside = localStorage.getItem('aside')

    return currentAside === 'left' || currentAside === 'right' ? currentAside : 'left'
  }

  /**
   * Retorna si la barra lateral está a la izquierda
   *
   * @returns {boolean} Retorna `true` si la barra lateral está a la izquierda, `false` si no
   */
  static isAsideLeft() {
    return this.getAside() === 'left'
  }

  /**
   * Establece la posición de la barra lateral y lo almacena en `localStorage`
   *
   * @param {string} newAside `left` o `right`
   */
  static setAside = (newAside) => {
    if (newAside !== 'left' && newAside !== 'right') localStorage.removeItem('aside')
    else localStorage.setItem('aside', newAside)

    location.reload()
  }
}

export default ThemeTools
