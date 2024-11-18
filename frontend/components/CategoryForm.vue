<template>
    <div>
        <div>
        <h1>Create Category</h1>
        <Form class="flex flex-column gap-3" @submit="submitForm">
            <div class="field">
            <Field name="categoryName">
                <label for="categoryName" class="font-bold">Name</label>
                <InputText
                v-bind="categoryAttrs"
                id="categoryName"
                v-model="categoryName"
                class="w-full"
                name="categoryName"
                type="text"
                required
                />
                <Message v-if="errors.categoryName" class="mt-2" severity="error">{{
                errors.categoryName
                }}</Message>
            </Field>
            </div>
            <div class="field">
            <label for="color" class="font-bold">Color</label>
            <ColorPicker
                id="color"
                v-model="color"
                name="color"
                class="w-full"
                type="text"
            />
            </div>
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
import axios from "axios";

import { z } from "zod";
import { Field, Form, useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";

// Import PrimeVue Components (PrimeVue v4)
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import { useToast } from "primevue/usetoast";
import ColorPicker from "primevue/colorpicker";

const config = useRuntimeConfig();

const isLoading = ref(false);
const toast = useToast();

const color = ref<string>("");

const { errors, defineField } = useForm({
    validationSchema: toTypedSchema(
        z.object({
        categoryName: z
            .string()
            .min(3, "The title must contain at least 3 characters")
            .max(100, "The title must not exceed 100 characters."),
        })
    ),
});

const [categoryName, categoryAttrs] = defineField("categoryName");

const submitForm = async () => {
    isLoading.value = true;
    try {
        const payload = {
        name: categoryName.value,
        color: color.value,
        };

        const response = await axios.post(
        `${config.public.apiBaseUrl}/api/categories`,
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
        categoryName.value = "";
        color.value = "";
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
</script>
