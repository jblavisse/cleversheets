<template>
  <div class="container">
    <Toast />
    <Header />
    <Panel class="mt-2">
      <NuxtPage />
    </Panel>
    <Panel class="mt-2">
      <Footer />
    </Panel>
  </div>
</template>

<script setup lang="ts">
import Header from './components/Base/Header.vue';
import Footer from './components/Base/Footer.vue';
import 'primeicons/primeicons.css';
</script>

<style>
.container {
  max-width: 1320px;
  margin: 0 auto;
  padding: 0.75rem;
}
</style>
