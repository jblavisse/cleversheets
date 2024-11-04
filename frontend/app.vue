<template>
  <div class="container">
    <Toast />
    <Header />
    <Panel class="mt-2">
      <NuxtPage />
    </Panel>
  </div>
</template>

<script setup lang="ts">
import Header from './components/Base/Header.vue';
import 'primeicons/primeicons.css';
import 'quill/dist/quill.snow.css';
import 'highlight.js/styles/default.css';
</script>

<style>
.container {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0.75rem;
}
</style>
