<template>
  <div>
    <div>
      <h1>Create Cheatsheet</h1>
      <Form @submit="submitForm">
        <!-- Champ de Titre Général -->
        <div class="field">
          <div class="flex flex-wrap mb-4 gap-2">
            <Field name="cheatsheetTitle">
              <FloatLabel>
                <label for="cheatsheetTitle">Title of the cheatsheet</label>
                <InputText v-bind="titleAttrs" id="cheatsheetTitle" v-model="cheatsheetTitle" name="cheatsheetTitle" type="text" required />
              </FloatLabel>
              <Message v-if="errors.cheatsheetTitle" severity="error">{{ errors.cheatsheetTitle }}</Message>
            </Field>
          </div>
        </div>

        <!-- Champ de Sélection de Catégorie -->
        <div class="field">
          <Select id="category" v-model="selectedCategory" :options="categories" option-label="name" option-value="@id" placeholder="Select a category" editable required />
        </div>

        <!-- Sections (Blocs) -->
        <div v-for="(block, blockIndex) in blocks" :key="block.id">
          <div class="field">
            <FloatLabel>
              <label>Section {{ blockIndex + 1 }}</label>
              <InputText v-model="block.title" type="text" placeholder="Section Title" required />
            </FloatLabel>
          </div>

          <!-- Contenu de la Section -->
          <div v-for="(content, contentIndex) in block.contents" :key="content.id">
            <div class="field">
              <Editor v-model="content.values['fixed-column-id']" type="text" placeholder="Contenu" :style="{ height: '150px' }" />
              <Button
                type="button"
                icon="pi pi-times"
                severity="danger"
                @click="removeContent(blockIndex, contentIndex)"
              />
            </div>
          </div>

          <!-- Ajouter du Contenu -->
          <div class="field">
            <Button
              type="button"
              severity="info"
              icon="pi pi-plus"
              label="Add a content line"
              @click="addContent(blockIndex)"
            />

            <!-- Supprimer la Section -->
            <Button
              type="button"
              severity="danger"
              label="Delete section"
              icon="pi pi-times"
              @click="removeBlock(blockIndex)"
            />
          </div>
        </div>

        <!-- Ajouter une Section -->
        <div class="field">
          <Button
            type="button"
            severity="info"
            label="Add a section"
            icon="pi pi-folder-plus"
            @click="addBlock"
          />

          <!-- Soumettre la Cheatsheet -->
          <Button
            type="submit"
            severity="success"
            :loading="isLoading"
            label="Publish"
            icon="pi pi-check"
          />
        </div>
      </Form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { v4 as uuidv4 } from 'uuid';
import axios from 'axios';
import { z } from 'zod';
import { Field, Form, useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';

// Import PrimeVue Components (PrimeVue v4)
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Editor from 'primevue/editor';

import type { Category, Block, Content } from '../types/cheatsheet';

const config = useRuntimeConfig();

// State
const blocks = ref<Block[]>([]);
const isLoading = ref(false);

// Pour les catégories
const categories = ref<Category[]>([]);
const selectedCategory = ref<string>(''); // IRI de la catégorie sélectionnée

const { errors, defineField } = useForm({
  validationSchema: toTypedSchema(
    z.object({
      cheatsheetTitle:
      z.string()
      .min(3, 'The title must contain at least 3 characters')
      .max(100, 'The title must not exceed 100 characters.'),
    }),
  ),
});

const [cheatsheetTitle, titleAttrs] = defineField('cheatsheetTitle');

// Fetch categories au montage
onMounted(async () => {
  try {
    const response = await axios.get(`${config.public.apiBaseUrl}/api/categories`, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    categories.value = response.data['member'];
  } catch (error) {
    console.error('Error', error);
  }
});

// Méthodes avec uuid
const addBlock = () => {
  blocks.value.push({
    id: uuidv4(),
    title: '',
    contents: []
  });
};

const removeBlock = (blockIndex: number) => {
  blocks.value.splice(blockIndex, 1);
};

const addContent = (blockIndex: number) => {
  const block = blocks.value[blockIndex];
  const newContentId = uuidv4();
  const newContent: Content = {
    id: newContentId,
    values: {
      'fixed-column-id': ''
    }
  };

  block.contents.push(newContent);
};

const removeContent = (blockIndex: number, contentIndex: number) => {
  blocks.value[blockIndex].contents.splice(contentIndex, 1);
};

const submitForm = async () => {
  isLoading.value = true;
  try {
    const payload = {
      title: cheatsheetTitle.value,
      sections: blocks.value.map(block => ({
        title: block.title,
        content: block.contents.map(content => content.values['fixed-column-id']).join("\n")
      })),
      category: selectedCategory.value
    };

    const response = await axios.post(`${config.public.apiBaseUrl}/api/cheatsheets`, payload, {
      headers: {
        'Content-Type': 'application/json'
      }
    });

    if (response.status === 201 || response.status === 200) {
      alert('Cheatsheet enregistrée avec succès!');
      cheatsheetTitle.value = '';
      blocks.value = [];
      selectedCategory.value = '';
      addBlock();
    } else {
      throw new Error('Erreur lors de l\'enregistrement');
    }
  } catch (error) {
    alert('Une erreur est survenue: ' + (error));
  } finally {
    isLoading.value = false;
  }
};

// Initialiser avec un bloc par défaut
addBlock();
</script>