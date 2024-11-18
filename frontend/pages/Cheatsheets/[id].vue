<template>
    <div>
        <div v-if="loading">Chargement...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else>
            <Card v-for="(section, index) in cheatsheet.sections" :key="index" class="cheatsheet">
                <template #title>{{ cheatsheet.title }}</template>
                <template #header>
                    <div class="category-label">{{ cheatsheet.category.name }}</div>
                </template>
                <template #content>
                    <h3>{{ section.title }}</h3>
                        <p class="m-0"><MarkdownRenderer :source="section.content" /></p>
                </template>
            </Card>
        </div>
        </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import MarkdownRenderer from '~/components/MarkdownRenderer.vue';
import Card from 'primevue/card';

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

// Utilisation de onMounted pour appeler la fonction lorsque le composant est monté
onMounted(() => {
    fetchCheatsheet(cheatsheetId);
});
</script>

<style>
.cheatsheets {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
.cheatsheet {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    position: relative;
}
.cheatsheet-title {
    background-color: #f2f2f2;
    padding: 10px 20px;
    font-size: 18px;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
}
.category-label {
    position: absolute;
    top: 0;
    right: 0;
    background-color: #00c853;
    color: white;
    padding: 4px 8px;
    font-size: 12px;
    border-bottom-left-radius: 8px;
}
.cheatsheet-content {
    padding: 20px;
}
.cheatsheet-footer {
    margin-top: 20px;
    font-style: italic;
    color: #666;
}
</style>
