export class ClipboardTools {
  /**
   * Copia el texto en el portapapeles
   *
   * @param {string} text
   * @returns {boolean} Retorna `true` si se ha podido copiar el texto, `false` si no
   */
  static async copyText(text) {
    // Copiar sin Clipboard API
    if (!navigator.clipboard) {
      const tmpElement = document.createElement('textarea')
      tmpElement.value = text

      document.body.appendChild(tmpElement)
      tmpElement.select()

      try {
        document.execCommand('copy')
        return true
      } catch (e) {
        console.error(e)
        return false
      } finally {
        document.body.removeChild(tmpElement)
      }
    }

    // Copiar con Clipboard API
    try {
      await navigator.clipboard.writeText(text)

      return true
    } catch (e) {
      console.error(e)
      return false
    }
  }
}
