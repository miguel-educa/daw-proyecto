import { api } from '@/config.js'

class UserTools {
  /**
   * Recupera la información del usuario
   *
   * @returns {Object|null} `{id: String, name: String, username: String, timestamp: Number}` o `null`
   */
  static async getUserInfo(confidentialData = false) {
    // Solicitud API
    const options = {
      method: 'GET',
      credentials: 'include',
    }

    try {
      const res = await fetch(
        api.userEndpoint + (confidentialData ? '?confidential_data=true' : ''),
        options,
      )

      if (res.status === 401) {
        sessionStorage.setItem('anonymous-user', 'true')
        return null
      }

      if (res.status !== 200) {
        throw new Error('No se ha podido recuperar la información del usuario')
      }

      const result = await res.json()
      const user = result.data

      const data = {
        id: user.id,
        name: user.name,
        username: user.username,
      }

      if (confidentialData) {
        data.recuperationCode = user.recuperation_code
        data.masterPasswordEditedAt = user.master_password_edited_at * 1000
        data.recuperationCode = user.recuperation_code
        data.recuperationCodeEditedAt = user.recuperation_code_edited_at * 1000
        data.totp2faActivated = user.totp_2fa_activated
        data.totp2faActivatedAt = user.totp_2fa_activated_at * 1000
      }

      return data
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

      sessionStorage.removeItem('anonymous-user')

      const result = await res.json()
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Comprueba si existe el nombre de usuario
   *
   * @param {String} username Nombre de usuario a comprobar
   *
   * @returns {boolean|null} `true` si existe, `false` si no existe o `null` si se produce un error
   */
  static async existsUsername(username) {
    const options = {
      method: 'GET',
    }

    try {
      const res = await fetch(`${api.usersEndpoint}?username=${username}`, options)

      if (res.status !== 200 && res.status !== 404) {
        throw new Error('No se ha podido comprobar la existencia del nombre de usuario')
      }

      return res.status === 200
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Comprueba si existe el nombre de usuario
   *
   * @param {String} username Nombre de usuario a comprobar
   *
   * @returns {boolean|null} `true` si existe, `false` si no existe o `null` si se produce un error
   */
  static async getUsersByUsername(username) {
    const options = {
      method: 'GET',
    }

    try {
      const res = await fetch(`${api.usersEndpoint}?partial_username=${username}`, options)

      if (res.status !== 200 && res.status !== 404) {
        throw new Error('No se ha podido comprobar la existencia del nombre de usuario')
      }

      const result = await res.json()
      return result.data ?? []
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Registra un nuevo usuario
   *
   * @returns {Object|null} Datos del resultado obtenido o `null` si se produce un error
   */
  static async register(data) {
    const options = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.usersEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido registrar el usuario')
      }

      sessionStorage.removeItem('anonymous-user')

      const result = await res.json()
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async generate2FA() {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({}),
    }

    try {
      const res = await fetch(api.twoFAEndpoint, options)

      if (res.status !== 200) {
        throw new Error('No se ha podido generar el código QR')
      }

      const result = await res.json()
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async verify2FA(data) {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.twoFAEndpoint, options)

      if (res.status !== 201 && res.status !== 400) {
        throw new Error('No se ha podido verificar el código')
      }

      const result = await res.json()
      return result
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async delete2FA() {
    const options = {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
    }

    try {
      const res = await fetch(api.twoFAEndpoint, options)

      if (res.status !== 200) {
        throw new Error('Se ha producido un error al eliminar la autenticación de doble factor')
      }

      return true
    } catch (error) {
      console.error(error)
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
  static async update(data) {
    const options = {
      method: 'PATCH',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.userEndpoint, options)

      if (res.status === 401) {
        return {}
      }

      if (res.status !== 200) {
        throw new Error('No se ha podido actualizar al usuario')
      }

      sessionStorage.removeItem('anonymous-user')
      const result = await res.json()

      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async updateSessionDuration(data) {
    const options = {
      method: 'PATCH',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.sessionEndpoint, options)

      if (res.status !== 200) {
        throw new Error('No se ha podido ampliar la sesión')
      }

      const result = await res.json()

      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async delete(type) {
    const options = {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ type }),
    }

    try {
      const res = await fetch(api.userEndpoint, options)

      if (res.status !== 200) {
        throw new Error('Se ha producido un error al realizar la eliminación')
      }

      return true
    } catch (error) {
      console.error(error)
      return null
    }
  }
}

export default UserTools
