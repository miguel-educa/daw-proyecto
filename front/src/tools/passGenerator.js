import { passGenerator } from '@/config.js'

/**
 * Configuración
*/
export class PassConfig {
  /**
   * Obtiene la configuración actual
   *
   * @returns {Object} Retorna la configuración actual
   */
  static get() {
    const passConfig = localStorage.getItem('pass-generator-config')

    return { ...passGenerator.config, ...JSON.parse(passConfig) }
  }

  /**
   * Guarda la configuración en `localStorage`
   *
   * @param {Object} config
   */
  static save(config) {
    localStorage.setItem('pass-generator-config', JSON.stringify(config))
  }
}

/**
 * Caracteres
 */
export class PassCharacters {
  static minPasswordLength = passGenerator.limits.minPasswordLength
  static maxPasswordLength = passGenerator.limits.maxPasswordLength
  static maxCharsMinCount = passGenerator.limits.maxNumberMinimumChars

  /**
   * Obtiene los caracteres disponibles por tipo:
   * - `lower`
   * - `upper`
   * - `number`
   * - `special`
   *
   * @param {string} type Tipo a obtener
   * @returns {Array} Array con los caracteres disponibles
   */
  static getByType(type = null) {
    return passGenerator.availableChars[type] ?? []
  }
}

/**
 * Historial
 */
export class PassHistory {
  /**
   * Retorna el historial de contraseñas almacenadas en `localStorage`
   *
   * @returns {Array} Array con las contraseñas más recientes
   */
  static get() {
    const passwords = JSON.parse(localStorage.getItem('pass-generator-history')) ?? []

    return passwords
  }

  /**
   * Añade una contraseña al historial. Si se supera el límite, se elimina la más antigua
   *
   * @param {string} password
   */
  static add(password) {
    const passwords = JSON.parse(localStorage.getItem('pass-generator-history')) ?? []

    passwords.unshift({
      pass: password,
      timestamp: Date.now(),
    })

    if (passwords.length > passGenerator.history.maxHistory) passwords.pop()

    localStorage.setItem('pass-generator-history', JSON.stringify(passwords))
  }

  /**
   * Vacia el historial
   */
  static clear() {
    localStorage.removeItem('pass-generator-history')
  }
}
