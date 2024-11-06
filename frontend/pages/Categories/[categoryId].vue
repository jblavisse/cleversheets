<template>
    <div>
        <h1>Cheatsheets pour la catégorie : {{ categoryName }}</h1>
        <div v-if="loading">Chargement...</div>
        <div v-else-if="error">Erreur : {{ error.message }}</div>
        <div v-else class="cheatsheets">
            <div v-for="(cheatsheet, index) in cheatsheets" :key="index" class="cheatsheet">
                <div class="cheatsheet-title">{{ cheatsheet.title }}</div>
                <div class="category-label">{{ cheatsheet.category.name }}</div>
                <div class="cheatsheet-content">
                    <h3>{{ cheatsheet.sections[0]?.title }}</h3>
                    <div v-html="sanitizeContent(cheatsheet.sections[0]?.content)" class="code-block"></div>
                    <div class="cheatsheet-footer">
                        See: The &lt;a&gt; Attributes
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRoute } from 'vue-router';
import DOMPurify from 'dompurify';

const cheatsheets = ref([]);
const loading = ref(false);
const error = ref(null);
const categoryName = ref("");

const route = useRoute();
const categoryId = route.params.categoryId;

const config = useRuntimeConfig();

// Fonction pour récupérer le nom de la catégorie à partir de son ID
const fetchCategoryName = async () => {
    try {
        const response = await axios.get(`${config.public.apiBaseUrl}/api/categories/${categoryId}`);
        if (response.data && response.data.name) {
            categoryName.value = response.data.name; // Affecter le nom de la catégorie récupérée
        } else {
            throw new Error(`Catégorie avec l'ID "${route.params.slug}" non trouvée`);
        }
    } catch (err) {
        throw new Error(`Erreur lors de la récupération du nom de la catégorie : ${err.message}`);
    }
};

// Fonction pour récupérer les cheatsheets de l'API pour une catégorie donnée
const fetchCheatsheets = async () => {
    loading.value = true;
    error.value = null;

    try {
        // Faire une requête API pour récupérer les cheatsheets de la catégorie donnée
        const response = await axios.get(`${config.public.apiBaseUrl}/api/cheatsheets?category=${categoryId}`);
        cheatsheets.value = response.data['member']; // Accéder à la liste des cheatsheets
    } catch (err) {
        error.value = err;
    } finally {
        loading.value = false;
    }
};

// Fonction pour nettoyer le contenu HTML
function sanitizeContent(htmlContent) {
    return DOMPurify.sanitize(htmlContent);
}

// Charger le nom de la catégorie et les cheatsheets dès que la page est montée
onMounted(async () => {
    loading.value = true;
    try {
        await fetchCategoryName();
        await fetchCheatsheets();
    } catch (err) {
        error.value = err;
        loading.value = false;
    }
});
</script>

<style scoped>
.cheatsheets {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
.cheatsheet {
    background-color: white;
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
.code-block {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    margin-bottom: 20px;
    overflow-x: auto;
}
.cheatsheet-footer {
    margin-top: 20px;
    font-style: italic;
    color: #666;
}
</style>
