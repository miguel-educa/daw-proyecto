import { api } from '@/config.js'

class FolderTools {
  /**
   * Recupera las carpetas creadas por el usuario
   */
  static async getFolders() {
    const options = {
      method: 'GET',
      credentials: 'include',
    }

    try {
      const res = await fetch(api.foldersEndpoint, options)
      const result = await res.json()

      if (res.status === 401) return null

      if (res.status !== 200) {
        throw new Error('No se ha podido recuperar las carpetas')
      }

      result.data.unshift({
        id: null,
        name: 'Sin carpeta',
        notEdit: true,
        notDelete: true,
      })
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las carpetas creadas por el usuario
   */
  static async add(data) {
    const options = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }
    console.log(data)

    try {
      const res = await fetch(api.foldersEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido crear la carpeta')
      }

      const result = await res.json()
      console.log(result)
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las carpetas creadas por el usuario
   */
  static async update(data) {
    const options = {
      method: 'PATCH',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }
    console.log(data)

    try {
      const res = await fetch(api.foldersEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido crear la carpeta')
      }

      const result = await res.json()
      console.log(result)
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Elimina una carpeta
   */
  static async delete(data) {
    const options = {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    console.log(data)
    try {
      const res = await fetch(api.foldersEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido eliminar la carpeta')
      }

      console.log(res)
      return true
    } catch (error) {
      console.error(error)
      return null
    }
  }
}

export default FolderTools
