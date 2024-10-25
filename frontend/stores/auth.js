import { defineStore } from 'pinia';
import axios from 'axios';
import { useRuntimeConfig } from '#app';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAuthenticated: false
  }),
  actions: {
    setAuthenticated(authenticated) {
      this.isAuthenticated = authenticated;
    },

    async checkAuth() {
      try {
        const config = useRuntimeConfig();
        // Vérification de l'authentification via une requête vers l'API
        const response = await axios.get(`${config.public.apiBaseUrl}/check-auth`, {
          withCredentials: true // envoie le cookie HTTPOnly
        });

        if (response.status === 204) {
          this.setAuthenticated(true);
        } else {
          this.setAuthenticated(false);
        }
      } catch (error) {
        console.error("Auth check error:", error);
        this.setAuthenticated(false);
      };
    }
  },
  persist: true
});
