<template>
    <div>
        <h1>Dashboard</h1>
        <Button label="Add Category" class="mr-2" @click="addCategory" />
        <Button label="Add Cheatsheet" class="mr-2" @click="addCheatsheet" />
    </div>
</template>

<script setup>
import Button from 'primevue/button';

definePageMeta({
    middleware: 'auth'
});

const router = useRouter();

const addCategory = () => {
    router.push('/categories/create');
};
const addCheatsheet = () => {
    router.push('/cheatsheets/create');
};
</script>
