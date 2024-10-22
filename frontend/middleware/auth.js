/* eslint-disable no-unused-vars */
import { useAuthStore } from '~/stores/auth';

export default defineNuxtRouteMiddleware(async (to, from) => {
    const authStore = useAuthStore();

    if (!authStore.isAuthenticated) {
        // Vérifie l'authentification côté serveur
        await authStore.checkAuth();
    }

    if (!authStore.isAuthenticated) {
        return navigateTo('/login');
    }
});
