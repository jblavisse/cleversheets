<template>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>Lorem</p>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/">Cheat Sheets</a></li>
                    <li><a href="/">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@exemple.com</p>
            </div>
        </div>
        <Divider />
        <div class="footer-bottom">
            <p>&copy; {{ currentYear }} Sheetdeck. Tous droits réservés.</p>
        </div>
    </footer>
</template>

<script setup>
import { ref } from 'vue';

const currentYear = ref(new Date().getFullYear());
</script>

<style scoped>
.footer-content {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin: 0 auto;
}

.footer-section ul {
    list-style-type: none;
    padding: 0;
}

.footer-section ul li {
margin-bottom: 0.5rem;
}

.footer-section a {
    text-decoration: none;
}

.footer-bottom {
    text-align: center;
    font-size: 0.9rem;
    margin-top: 2rem;
}
</style>