<template>
  <div>
    <div>
      <h1>Create Cheatsheet</h1>
      <Form class="flex flex-column gap-3" @submit="submitForm">
        <!-- Champ de Titre Général -->
        <div class="field">
          <Field name="cheatsheetTitle">
            <label for="cheatsheetTitle" class="font-bold"
              >Title of the cheatsheet</label
            >
            <InputText
              v-bind="titleAttrs"
              id="cheatsheetTitle"
              v-model="cheatsheetTitle"
              class="w-full"
              name="cheatsheetTitle"
              type="text"
              required
            />
            <Message
              v-if="errors.cheatsheetTitle"
              class="mt-2"
              severity="error"
              >{{ errors.cheatsheetTitle }}</Message
            >
          </Field>
        </div>

        <!-- Champ de Sélection de Catégorie -->
        <div class="field">
          <label for="category" class="font-bold">Category</label>
          <Select
            id="category"
            v-model="selectedCategory"
            class="w-full"
            :options="categories"
            option-label="name"
            option-value="@id"
            required
          />
        </div>

        <!-- Sections (Blocs) -->
        <div v-for="(block, blockIndex) in blocks" :key="block.id">
          <div class="field">
            <label for="section-title" class="font-bold"
              >Section {{ blockIndex + 1 }}</label
            >
            <InputText
              id="section-title"
              v-model="block.title"
              class="w-full"
              type="text"
              required
            />
          </div>

          <!-- Contenu de la Section -->
          <div
            v-for="(content, contentIndex) in block.contents"
            :key="content.id"
          >
            <div class="field">
              <label for="content" class="font-bold">Content</label>
              <div class="textarea-container">
                <Editor
                  id="content"
                  v-model="content.values['fixed-column-id']"
                  rows="5"
                  class="w-full"
                  type="text"
                  editor-style="height: 320px"
                  required
                />
                <Button
                  type="button"
                  icon="pi pi-times"
                  class="p-button-rounded p-button-danger clear-button"
                  severity="danger"
                  @click="removeContent(blockIndex, contentIndex)"
                />
              </div>
            </div>
          </div>

          <div class="card flex justify-center">
            <ButtonGroup>
              <!-- Ajouter du Contenu -->
              <Button
                type="button"
                severity="info"
                icon="pi pi-plus"
                label="Add a content line"
                @click="addContent(blockIndex)"
              />

              <!-- Ajouter une Section -->
              <Button
                type="button"
                severity="info"
                label="Add a section"
                icon="pi pi-folder-plus"
                @click="addBlock"
              />

              <!-- Supprimer la Section -->
              <Button
                type="button"
                severity="danger"
                label="Delete section"
                icon="pi pi-times"
                @click="removeBlock(blockIndex)"
              />
            </ButtonGroup>
          </div>
        </div>
        <!-- Soumettre la Cheatsheet -->
        <Button
          type="submit"
          severity="success"
          :loading="isLoading"
          label="Publish"
          class="w-full"
          icon="pi pi-check"
        />
      </Form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from "vue";
import { v4 as uuidv4 } from "uuid";
import axios from "axios";

import { z } from "zod";
import { Field, Form, useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";

// Import PrimeVue Components (PrimeVue v4)
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import { useToast } from "primevue/usetoast";
import Editor from "primevue/editor";
import 'quill/dist/quill.snow.css';
import 'highlight.js/styles/default.css';

import type { Category, Block, Content } from "../types/cheatsheet";

const config = useRuntimeConfig();

// State
const blocks = ref<Block[]>([]);
const isLoading = ref(false);
const toast = useToast();

// Pour les catégories
const categories = ref<Category[]>([]);
const selectedCategory = ref<string>(""); // IRI de la catégorie sélectionnée

const { errors, defineField } = useForm({
  validationSchema: toTypedSchema(
    z.object({
      cheatsheetTitle: z
        .string()
        .min(3, "The title must contain at least 3 characters")
        .max(100, "The title must not exceed 100 characters."),
    })
  ),
});

const [cheatsheetTitle, titleAttrs] = defineField("cheatsheetTitle");

// Configuration des modules de l'éditeur
// eslint-disable-next-line @typescript-eslint/no-unused-vars
let hljs = null;

// const editorModules = ref({
//   toolbar: [
//     ['bold', 'italic', 'underline', 'strike'],
//     ['code-block'],
//   ],
// });

onMounted(async () => {
  if (import.meta.client) {
    // Importez highlight.js dynamiquement côté client
    const hljsModule = await import('highlight.js');
    hljs = hljsModule.default;

    // editorModules.value = {
    //   ...editorModules.value,
    //   syntax: {
    //     highlight: (text) => hljs.highlightAuto(text).value,
    //   },
    // };
  }
});

// Fetch categories au montage
onMounted(async () => {
  try {
    const response = await axios.get(
      `${config.public.apiBaseUrl}/api/categories`,
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    categories.value = response.data["member"];
  } catch (error) {
    console.error("Error", error);
  }
});

// Méthodes avec uuid
const addBlock = () => {
  blocks.value.push({
    id: uuidv4(),
    title: "",
    contents: [
      {
        id: uuidv4(),
        values: {
          "fixed-column-id": "",
        },
      },
    ],
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
      "fixed-column-id": "",
    },
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
      sections: blocks.value.map((block) => ({
        title: block.title,
        content: block.contents
          .map((content) => content.values["fixed-column-id"])
          .join("\n"),
      })),
      category: selectedCategory.value,
    };

    const response = await axios.post(
      `${config.public.apiBaseUrl}/api/cheatsheets`,
      payload,
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    if (response.status === 201 || response.status === 200) {
      toast.add({
        severity: "success",
        summary: "Cheatsheet enregistrée avec succès !",
        life: 3000,
      });
      cheatsheetTitle.value = "";
      blocks.value = [];
      selectedCategory.value = "";
      addBlock();
    } else {
      toast.add({
        severity: "error",
        summary: "Une erreur est survenue lors de l'enregistrement",
        life: 3000,
      });
    }
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Une erreur est survenue lors de l'enregistrement",
      detail: error,
      life: 3000,
    });
  } finally {
    isLoading.value = false;
  }
};

// Initialiser avec un bloc par défaut
addBlock();
</script>

<style scoped>
.textarea-container {
  position: relative;
}

.clear-button {
  position: absolute !important;
  top: 0.5rem;
  right: 0.5rem;
  width: 2rem;
  height: 2rem;
  z-index: 1;
}

.textarea-container .p-inputtextarea {
  padding-right: 2.5rem;
}
</style>
