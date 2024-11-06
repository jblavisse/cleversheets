<template>
    <div>
        <div v-if="loading">Chargement...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else>
            <div v-for="(section, index) in cheatsheet.sections" :key="index">
                <h1>{{ cheatsheet.title }}</h1>
                <small>Category : {{ cheatsheet.category.name }}</small>
                <h3>{{ section.title }}</h3>
                <div v-html="sanitizeContent(section.content)" class="content"></div>
            </div>
        </div>
        </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import DOMPurify from 'dompurify';

// Variables réactives pour stocker l'état de la requête
const cheatsheet = ref(null);
const loading = ref(true);
const error = ref(null);

// Utilisation de useRoute pour obtenir l'ID de la cheatsheet depuis l'URL
const route = useRoute();
const config = useRuntimeConfig();
const cheatsheetId = route.params.id;

// Fonction pour récupérer la cheatsheet
const fetchCheatsheet = async (id) => {
try {
    // Envoie la requête GET à l'API
    const response = await axios.get(`${config.public.apiBaseUrl}/api/cheatsheets/${id}`);
    // Assigne la réponse à la variable cheatsheet
    cheatsheet.value = response.data;
} catch (err) {
    // Gestion des erreurs
    error.value = 'Erreur lors de la récupération de la cheatsheet : ' + err.message;
} finally {
    // Met fin à l'état de chargement
    loading.value = false;
}
};

// Fonction pour nettoyer le contenu HTML
function sanitizeContent(htmlContent) {
    return DOMPurify.sanitize(htmlContent);
}

// Utilisation de onMounted pour appeler la fonction lorsque le composant est monté
onMounted(() => {
    fetchCheatsheet(cheatsheetId);
});
</script>
