import { defineStore } from 'pinia'
import { useUserStore } from './userStore.js'

const uStore = useUserStore()

export const usePasswordStore = defineStore('password', {
  state: () => ({
    passwords: [],
    passwordSearch: null,
    sharedPasswords: [],
    sharedPasswordSearch: null,
    sharedPasswordType: null,
    folders: [],
    foldersIdSelected: [],
  }),
  getters: {
    passwordsFiltered() {
      let passwords = this.passwords

      if (this.foldersIdSelected.length) {
        passwords = this.filterByFolder(passwords)
      }

      if (this.passwordSearch) {
        passwords = this.filterByName(passwords, this.passwordSearch)
      }

      return passwords
    },

    sharedPasswordsFiltered() {
      let passwords = this.sharedPasswords

      if (this.sharedPasswordType === 'own') {
        passwords = passwords.filter((password) => {
          return password.owner_user_id === uStore.user.id
        })
      } else if (this.sharedPasswordType === 'shared') {
        passwords = passwords.filter((password) => {
          return password.owner_user_id !== uStore.user.id
        })
      }

      if (this.sharedPasswordSearch) {
        passwords = this.filterByName(passwords, this.sharedPasswordSearch)
      }

      return passwords
    },
  },
  actions: {
    filterByName(passwords, name) {
      return passwords.filter((password) => {
        return password.name.toLowerCase().includes(name.toLowerCase())
      })
    },
    filterByFolder(passwords) {
      return passwords.filter((password) => {
        return this.foldersIdSelected.includes(password.folder_id)
      })
    },
  },
})
