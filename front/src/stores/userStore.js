import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
  }),
  getters: {
    isUserLogged() {
      return this.user !== null
    },
  },
})
