<template>
  <div class="container">
    <div class="form-section">
      <h1>Créer une Cheatsheet</h1>
      <form @submit.prevent="submitForm">

        <!-- Champ de Titre Général -->
        <div class="field">
          <FloatLabel>
            <label for="cheatsheetTitle">Titre Général</label>
            <InputText id="cheatsheetTitle" v-model="cheatsheetTitle" type="text" required />
          </FloatLabel>
        </div>

        <div v-for="(block, blockIndex) in blocks" :key="block.id" class="form-block">
          <h2>Bloc {{ blockIndex + 1 }}</h2>
          <div class="field">
            <FloatLabel>
              <label for="title">Titre</label>
              <InputText id="title" v-model="block.title" type="text" required />
            </FloatLabel>
          </div>
          <div class="field">
              <Button type="button" severity="info" @click="addColumn(blockIndex)">
                Ajouter une colonne
              </Button>
          </div>
          <div class="field">
            <div v-for="(content, contentIndex) in block.contents" :key="content.id" class="content-row">
              <div v-for="(column, columnIndex) in block.columns" :key="column.id" class="content-field">
                <InputGroup>
                  <InputText v-model="content.values[column.id]" type="text" placeholder="Contenu" required />
                  <Button type="button" icon="pi pi-times" severity="danger" @click="removeContent(blockIndex, contentIndex)" />
                </InputGroup>
              </div>
            </div>
              <Button type="button" severity="info" @click="addContent(blockIndex)">
                Ajouter une ligne de contenu
              </Button>
          </div>
          <Button type="button" severity="danger" @click="removeBlock(blockIndex)">
            Supprimer ce bloc
          </Button>
        </div>
        <Button type="button" severity="info" @click="addBlock">
          Ajouter un bloc
        </Button>
        <Button type="submit" severity="success" :loading="isLoading">
          Publier
        </Button>
      </form>
    </div>
    <div class="preview-section">
      <h1>Aperçu en Temps Réel</h1>
      <div v-if="blocks.length">
        <div class="preview-cheatsheet-title">
          <h2>{{ cheatsheetTitle }}</h2>
        </div>
        <div v-for="(block, blockIndex) in blocks" :key="block.id" class="preview-block">
          <h2>Bloc {{ blockIndex + 1 }} : {{ block.title }}</h2>
          <table>
            <tbody>
              <tr v-for="(content, contentIndex) in block.contents" :key="content.id">
                <td v-for="column in block.columns" :key="column.id">
                  {{ content.values[column.id] }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div v-else>
        <p>Aucune donnée à afficher.</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'
import axios from 'axios'
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import FloatLabel from 'primevue/floatlabel';

// Types
interface Column {
  id: string
  name: string
}

interface Content {
  id: string
  values: Record<string, string> // Clé : column.id, Valeur : contenu
}

interface Block {
  id: string
  title: string
  columns: Column[]
  contents: Content[]
}

// State
const cheatsheetTitle = ref<string>('')
const blocks = ref<Block[]>([])
const isLoading = ref(false)

// Méthodes avec uuid
const addBlock = () => {
  blocks.value.push({
    id: uuidv4(),
    title: '',
    columns: [
      { id: uuidv4(), name: 'Colonne 1' } // Initialiser avec une colonne par défaut
    ],
    contents: []
  })
}

const removeBlock = (blockIndex: number) => {
  blocks.value.splice(blockIndex, 1)
}

const addColumn = (blockIndex: number) => {
  const block = blocks.value[blockIndex]
  const newColumnId = uuidv4()
  block.columns.push({
    id: newColumnId,
    name: `Colonne ${block.columns.length + 1}`
  })

  // Ajouter une nouvelle valeur vide pour chaque contenu existant
  block.contents.forEach(content => {
    content.values[newColumnId] = ''
  })
}

const removeColumn = (blockIndex: number, columnIndex: number) => {
  const block = blocks.value[blockIndex]
  const columnIdToRemove = block.columns[columnIndex].id
  block.columns.splice(columnIndex, 1)

  // Supprimer la valeur correspondante dans chaque contenu
  block.contents.forEach(content => {
    delete content.values[columnIdToRemove]
  })
}

const addContent = (blockIndex: number) => {
  const block = blocks.value[blockIndex]
  const newContentId = uuidv4()
  const newContent: Content = {
    id: newContentId,
    values: {}
  }

  // Initialiser les valeurs pour chaque colonne
  block.columns.forEach(column => {
    newContent.values[column.id] = ''
  })

  block.contents.push(newContent)
}

const removeContent = (blockIndex: number, contentIndex: number) => {
  blocks.value[blockIndex].contents.splice(contentIndex, 1)
}

const updateColumnName = (blockIndex: number, columnIndex: number, newName: string) => {
  blocks.value[blockIndex].columns[columnIndex].name = newName
}

const updateContentValue = (blockIndex: number, contentIndex: number, columnId: string, newValue: string) => {
  blocks.value[blockIndex].contents[contentIndex].values[columnId] = newValue
}

const submitForm = async () => {
  isLoading.value = true
  try {
    // Préparer les données à envoyer
    const payload = {
      title: cheatsheetTitle.value, // Inclure le titre général
      blocks: blocks.value.map(block => ({
        title: block.title,
        columns: block.columns.map(column => ({
          name: column.name
        })),
        contents: block.contents.map(content => ({
          values: content.values
        }))
      }))
    }

    console.log(payload)

    // Envoyer les données au backend Symfony API Platform
    // const response = await axios.post('http://localhost:8000/api/cheatsheets', payload, {
    //   headers: {
    //     'Content-Type': 'application/json'
    //   }
    // })

    console.log(response)

    if (response.status === 201 || response.status === 200) {
      alert('Cheatsheet enregistrée avec succès!')
      // Réinitialiser le formulaire
      blocks.value = []
    } else {
      throw new Error('Erreur lors de l\'enregistrement')
    }
  } catch (error: any) {
    console.error(error)
    alert('Une erreur est survenue: ' + (error.response?.data?.message || error.message))
  }
}

// Initialiser avec un bloc par défaut
addBlock()
</script>

<style scoped>
.container {
  display: flex;
  gap: 20px;
  padding: 20px;
}

.form-section,
.preview-section {
  flex: 1;
  border: 1px solid #ccc;
  padding: 20px;
  border-radius: 8px;
  box-sizing: border-box;
}

.form-section {
  max-width: 50%;
}

.preview-section {
  max-width: 50%;
}

.form-block {
  margin-bottom: 20px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.field {
  margin-bottom: 15px;
}

.column-row,
.content-row {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.content-field {
  margin-right: 10px;
}

.add-button,
.remove-button,
.remove-column-button,
.remove-content-button,
.submit-button {
  margin-top: 10px;
}

.add-button {
  background-color: #4caf50;
  color: white;
  border: none;
  padding: 7px 12px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 14px;
}

.remove-button,
.remove-column-button,
.remove-content-button {
  background-color: #f44336;
  color: white;
  border: none;
  padding: 7px 12px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 14px;
}

.submit-button {
  background-color: #008cba;
  color: white;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 16px;
}

.add-button:hover,
.remove-button:hover,
.remove-column-button:hover,
.remove-content-button:hover,
.submit-button:hover {
  opacity: 0.8;
}

.preview-block {
  margin-bottom: 20px;
}

.preview-block h2 {
  margin-bottom: 10px;
}

.preview-block table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 10px;
}

.preview-block th,
.preview-block td {
  border: 1px solid #ddd;
  padding: 8px;
}

.preview-block th {
  text-align: left;
}

@media (max-width: 768px) {
  .container {
    flex-direction: column;
  }

  .form-section,
  .preview-section {
    max-width: 100%;
  }
}
</style>