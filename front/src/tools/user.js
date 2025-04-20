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
}

export default UserTools
