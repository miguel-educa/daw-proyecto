import { defineStore } from 'pinia'
import { PassConfig, PassCharacters, PassHistory } from '@/tools/passGenerator.js'

export const usePassGeneratorStore = defineStore('passGenerator', {
  state: () => ({
    passwordLength: PassConfig.get().passwordLength,
    lowerCharCount: PassConfig.get().lowerCharCount,
    upperCharCount: PassConfig.get().upperCharCount,
    numberCharCount: PassConfig.get().numberCharCount,
    specialCharCount: PassConfig.get().specialCharCount,
    history: PassHistory.get(),
  }),
  actions: {
    // Obtiene la cantidad total de caracteres mínimos
    getCharsMinCount() {
      return (
        this.lowerCharCount + this.upperCharCount + this.numberCharCount + this.specialCharCount
      )
    },
    // Valida la longitud de la contraseña
    validatePasswordLength() {
      const charsLength = this.getCharsMinCount()

      if (charsLength > PassCharacters.minPasswordLength && this.passwordLength < charsLength) {
        this.passwordLength = charsLength
      } else if (this.passwordLength < PassCharacters.minPasswordLength) {
        this.passwordLength = PassCharacters.minPasswordLength
      } else if (this.passwordLength > PassCharacters.maxPasswordLength) {
        this.passwordLength = PassCharacters.maxPasswordLength
      }
    },
    // Almacena la configuración en `localStorage`
    saveConfig() {
      PassConfig.save({
        passwordLength: this.passwordLength,
        lowerCharCount: this.lowerCharCount,
        upperCharCount: this.upperCharCount,
        numberCharCount: this.numberCharCount,
        specialCharCount: this.specialCharCount,
      })
    },
    // Añade la contraseña al historial
    addHistory(password) {
      PassHistory.add(password)
      this.history = PassHistory.get()
    },
    // Vacía el historial
    clearHistory() {
      PassHistory.clear()
      this.history = PassHistory.get()
    },
  },
})
