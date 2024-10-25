<template>
    <div class="flex flex-col md:flex-row">
        <div class="w-full flex flex-col items-center justify-center">
            <form @submit.prevent="login">
                <div class="flex flex-col mb-2">
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-at" />
                        </InputGroupAddon>
                        <InputText id="email" v-model="email" type="email" placeholder="Email" required />
                    </InputGroup>
                </div>
                <div class="flex flex-col mb-2">
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-lock" />
                        </InputGroupAddon>
                        <InputText id="password" v-model="password" type="password" placeholder="Password" required />
                    </InputGroup>
                </div>
                <div class="flex">
                    <Button type="submit">Se connecter</Button>
                </div>
                <p v-if="errorMessage">{{ errorMessage }}</p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '~/stores/auth';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();
const authStore = useAuthStore();

const config = useRuntimeConfig();

const login = async () => {
    try {
        const response = await axios.post(`${config.public.apiBaseUrl}/auth`, {
            email: email.value,
            password: password.value
        }, {
            withCredentials: true
        });

        if (response.status === 204) {
        // Mettre à jour l'état d'authentification
        authStore.setAuthenticated(true);
        // Rediriger l'utilisateur
        router.push('/dashboard');
        }
    } catch (error) {
        errorMessage.value = "Erreur lors de la connexion. Vérifiez vos identifiants." + error;
    }
};
</script>
