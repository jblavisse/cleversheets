<template>
<div>
    <h1>All Cheatsheets</h1>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">Erreur : {{ error.message }}</div>
    <div v-else class="cheatsheets">
        <div v-for="cheatsheet in cheatsheets" :key="cheatsheet.id" class="cheatsheet">
        <div class="cheatsheet-title">{{ cheatsheet.title }}</div>
        <div class="category-label">{{ cheatsheet.category.name }}</div>
        <div class="cheatsheet-content">
            <p>{{ cheatsheet.sections[0].title }}</p>
            <!--<div v-html="sanitizeContent(cheatsheet.sections[0].content)" class="content"></div>-->
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
import { ref, onMounted, watch, nextTick } from "vue";
import hljs from 'highlight.js';
import 'highlight.js/styles/nord.css';
// import DOMPurify from 'dompurify';

const cheatsheets = ref([]);
const loading = ref(false);
const error = ref(null);

const config = useRuntimeConfig();

// Fonction pour récupérer les cheatsheets de l'API
const fetchCheatsheets = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(`${config.public.apiBaseUrl}/api/cheatsheets`);
            cheatsheets.value = response.data["member"];
    } catch (err) {
        error.value = err;
    } finally {
        loading.value = false;
    }
};

// Fonction pour nettoyer le contenu HTML
// function sanitizeContent(htmlContent) {
//     return DOMPurify.sanitize(htmlContent);
// }

// Fonction pour appliquer le surlignage de syntaxe
function highlightCodeBlocks() {
    const codeBlocks = document.querySelectorAll('pre code');
    codeBlocks.forEach((block) => {
    hljs.highlightElement(block);
    });
}

// Charger les cheatsheets dès que la page est montée
onMounted(() => {
    fetchCheatsheets();
    watchCheatsheets();
});

// Surveiller les changements dans les cheatsheets
function watchCheatsheets() {
    watch(
    () => cheatsheets.value,
    () => {
        nextTick(() => {
        highlightCodeBlocks();
        });
    },
    { deep: true }
    );
}
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
.content pre {
    background-color: #2d2d2d;
    padding: 1em;
    border-radius: 5px;
    overflow-x: auto;
}
.content code {
    color: #f8f8f2;
}
.cheatsheet-footer {
    margin-top: 20px;
    font-style: italic;
    color: #666;
}
</style>