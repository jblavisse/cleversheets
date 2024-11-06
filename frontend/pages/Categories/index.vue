<template>
    <div>
        <h1>Liste des Catégories</h1>
        <div v-if="loading">Chargement...</div>
        <div v-else-if="error">Erreur : {{ error.message }}</div>
        <div v-else class="categories">
            <button 
                v-for="category in categories"
                :key="category.id"
                class="category-button"
                :style="{ backgroundColor: `#${category.color}` }"
                @click="goToCategory(category.id)">
                <span class="category-name">{{ category.name }}</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from 'vue-router';

const categories = ref([]);
const loading = ref(false);
const error = ref(null);
const config = useRuntimeConfig();
const router = useRouter();

// Fonction pour récupérer la liste des catégories
const fetchCategories = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(`${config.public.apiBaseUrl}/api/categories`);
        categories.value = response.data['member']; // Accéder à la liste des catégories
    } catch (err) {
        error.value = err;
    } finally {
        loading.value = false;
    }
};

// Naviguer vers la page de la catégorie spécifique
const goToCategory = (categoryId) => {
    router.push(`/categories/${categoryId}`);
};

// Charger les catégories dès que la page est montée
onMounted(() => {
    fetchCategories();
});
</script>

<style scoped>
.categories {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}
.category-button {
    background-color: #f0f0f0;
    border: 0;
    border-radius: 4px;
    cursor: pointer;
    padding: 1.3rem;
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #333;
}
.category-name {
    font-size: 1.1rem;
}
.category-button:hover {
    background-color: #45a049;
}
</style>
