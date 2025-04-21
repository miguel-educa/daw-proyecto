import { api } from '@/config.js'

class UserTools {
  /**
   * Recupera la información del usuario
   *
   * @returns {Object|null} `{id: String, name: String, username: String, timestamp: Number}` o `null`
   */
  static async getUserInfo() {
    // Solicitud API
    const options = {
      method: 'GET',
      credentials: 'include',
    }

    try {
      const res = await fetch(api.userEndpoint, options)

      if (res.status === 401) return null

      if (res.status !== 200) {
        throw new Error('No se ha podido recuperar la información del usuario')
      }

      const result = await res.json()
      const user = result.data

      return {
        id: user.id,
        name: user.name,
        username: user.username,
      }
    } catch (e) {
      console.error(e)
      return null
    }
  }

  /**
   * Inicia sesión de un usuario
   *
   * @param {Object} data Datos del usuario
   *
   * @returns {Object|null} Datos del resultado obtenido o `null` si se produce un error
   */
  static async login(data) {
    const options = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.sessionEndpoint, options)

      if (res.status === 401) {
        return {}
      }

      if (res.status !== 200) {
        throw new Error('No se ha podido iniciar sesión')
      }

      sessionStorage.removeItem('anonymous-user')
      const result = await res.json()

      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Cierra la sesión de un usuario
   *
   * @returns {boolean|null} `true` si se ha cerrado la sesión correctamente, `null` si se produce un error
   */
  static async logout() {
    const options = {
      method: 'DELETE',
      credentials: 'include',
    }

    try {
      const res = await fetch(api.sessionEndpoint, options)

      if (res.status !== 200) {
        throw new Error('No se ha podido cerrar la sesión')
      }

      return true
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera una cuenta de usuario mediante un código de recuperación
   *
   * @param {Object} data Datos para recuperar la cuenta
   *
   * @returns {Object|null} Nuevo código de recuperación o `null` si se produce un error
   */
  static async recuperationCode(data) {
    const options = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.accountRecoveryEndpoint, options)

      if (res.status === 401) {
        return {}
      }

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido recuperar la cuenta')
      }

      const result = await res.json()
      console.log(result)
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }
}

export default UserTools
