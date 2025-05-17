import { api } from '@/config.js'
import FolderTools from './folder.js'

class PasswordTools {
  static checkMasterPassword(mPass) {
    const length = mPass.length
    const hasInvalidChars = /[^a-zA-Z0-9_\-,;!.@*&#%+$/]/.test(mPass)
    const hasLower = /[a-z]/.test(mPass)
    const hasUpper = /[A-Z]/.test(mPass)
    const hasNumber = /[0-9]/.test(mPass)
    const hasSpecial = /[_\-,;!.@*&#%+$/]/.test(mPass)

    return (
      length >= 8 &&
      length <= 50 &&
      !hasInvalidChars &&
      hasLower &&
      hasUpper &&
      hasNumber &&
      hasSpecial
    )
  }

  /**
   * Recupera las contraseñas creadas por el usuario
   */
  static async getPasswords() {
    const options = {
      method: 'GET',
      credentials: 'include',
    }

    try {
      const res = await fetch(api.paswordsEndpoint, options)
      const result = await res.json()

      if (res.status === 401) return null

      if (res.status !== 200) {
        throw new Error('No se ha podido recuperar las contraseñas')
      }

      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las contraseñas creadas por el usuario
   */
  static async getSharedPasswords() {
    const options = {
      method: 'GET',
      credentials: 'include',
    }

    try {
      const res = await fetch(api.sharedPasswordsEndpoint, options)
      const result = await res.json()

      if (res.status === 401) return null

      if (res.status !== 200) {
        throw new Error('No se ha podido recuperar las contraseñas')
      }

      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las contraseñas creadas por el usuario
   */
  static async getSharedPasswordUsers(id) {
    const options = {
      method: 'GET',
      credentials: 'include',
    }

    try {
      const res = await fetch(`${api.sharedPasswordsEndpoint}?shared_password_id=${id}`, options)
      const result = await res.json()

      if (res.status === 401) return null

      if (res.status !== 200) {
        throw new Error('No se ha podido recuperar los usuarios')
      }

      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las contraseñas creadas por el usuario
   */
  static async add(data) {
    const options = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.paswordsEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido crear la contraseña')
      }

      const result = await res.json()
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las contraseñas creadas por el usuario
   */
  static async update(data) {
    const options = {
      method: 'PATCH',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.paswordsEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido crear la contraseña')
      }

      const result = await res.json()
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Recupera las contraseñas creadas por el usuario
   */
  static async updateSharedPasswordUsers(data) {
    const options = {
      method: 'PATCH',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.sharedPasswordsEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido crear la contraseña')
      }

      const result = await res.json()
      return result.data
    } catch (error) {
      console.error(error)
      return null
    }
  }

  /**
   * Elimina una contraseña
   */
  static async delete(data) {
    const options = {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }

    try {
      const res = await fetch(api.paswordsEndpoint, options)

      if (res.status !== 200 && res.status !== 400) {
        throw new Error('No se ha podido eliminar la contraseña')
      }

      return true
    } catch (error) {
      console.error(error)
      return null
    }
  }

  static async getPasswordsGroupByPassword(onlyDuplicates = false) {
    const passwordObject = {}
    const folderObject = {}

    const folders = await FolderTools.getFolders()
    folders.forEach((folder) => {
      folderObject[folder.id] = folder
    })

    const passwords = await PasswordTools.getPasswords()

    passwords.forEach((pass) => {
      if (!pass.password) return

      pass.folder = folderObject[pass.folder_id]
      if (!passwordObject[pass.password]) {
        passwordObject[pass.password] = [pass]
      } else {
        passwordObject[pass.password].push(pass)
      }
    })

    if (!onlyDuplicates) return Object.entries(passwordObject)

    return Object.entries(passwordObject).filter((item) => item[1].length > 1)
  }

  static async getFilteredPasswords() {
    const groupedPasswords = await PasswordTools.getPasswordsGroupByPassword()
    const passwords = []

    for (const item of groupedPasswords) {
      const filterNumber = await this.getFilterNumber(item[0])
      if (filterNumber === 0) continue

      item.push(filterNumber)
      passwords.push(item)
    }

    return passwords
  }

  static async getFilterNumber(password) {
    const encoder = new TextEncoder()
    const data = encoder.encode(password)
    const hashBuffer = await crypto.subtle.digest('SHA-1', data)
    const hashArray = Array.from(new Uint8Array(hashBuffer))
    const hashHex = hashArray
      .map((b) => b.toString(16).padStart(2, '0'))
      .join('')
      .toUpperCase()

    const result = await fetch(api.filteredPasswordsEndpoint + hashHex.slice(0, 5))
    const text = await result.text()

    const match = text.split(hashHex.slice(5))[1]

    if (!match) return 0
    return parseInt(match.split(':')[1])
  }

  static async getSecurityPasswords() {
    const groupedPasswords = await PasswordTools.getPasswordsGroupByPassword()
    const securityPasswords = {
      1: [],
      2: [],
      3: [],
    }

    for (const item of groupedPasswords) {
      const result = this.getSecurityPasswordRating(item[0])

      item.push(result)
      securityPasswords[result.rating].push(item)
    }

    return Object.entries(securityPasswords)
  }

  static getSecurityPasswordRating(password) {
    const result = {
      rating: 0,
      length: 1,
      hasLower: false,
      hasUpper: false,
      hasNumber: false,
      hasSpecial: false,
      hasRepeatedChar: false,
      hasCharSecuences: false,
    }

    // Longitud
    if (password.length > 7) {
      result.rating++
      result.length = 2
    }
    if (password.length > 14) {
      result.rating++
      result.length = 3
    }

    // Tipos de caracteres
    if (password.match(/[a-z]/g)?.length > 1) {
      result.rating++
      result.hasLower = true
    }
    if (password.match(/[A-Z]/g)?.length > 1) {
      result.rating++
      result.hasUpper = true
    }
    if (password.match(/[0-9]/g)?.length > 1) {
      result.rating++
      result.hasNumber = true
    }
    if (password.match(/[^a-zA-Z0-9]/g)?.length > 1) {
      result.rating++
      result.hasSpecial = true
    }

    // Caracteres repetidos o secuencias
    if (this.hasDuplicatedChars(password)) {
      result.rating--
      result.hasRepeatedChar = true
    }
    if (this.hasCharSecuences(password)) {
      result.rating--
      result.hasCharSecuences = true
    }

    // Ajustar rating a 1, 2 o 3
    result.rating = Math.max(Math.floor(result.rating / 2), 1)

    return result
  }

  static hasDuplicatedChars(password) {
    const chars = {}

    for (let i = 0; i < password.length; i++) {
      const char = password[i]

      if (!chars[char]) {
        chars[char] = 1
        continue
      }

      chars[char]++

      if (chars[char] > 2) {
        return true
      }
    }

    return false
  }

  static hasCharSecuences(password) {
    if (password.length < 3) return false

    let ascCounter = 1,
      descCounter = 1

    for (let i = 0; i < password.length - 1; i++) {
      const charCodeDiff = password.charCodeAt(i + 1) - password.charCodeAt(i)

      if (charCodeDiff === 1) {
        ascCounter++
        descCounter = 1
      } else if (charCodeDiff === -1) {
        descCounter++
        ascCounter = 1
      } else {
        ascCounter = 1
        descCounter = 1
      }

      if (ascCounter >= 3 || descCounter >= 3) return true
    }

    return false
  }
}

export default PasswordTools
