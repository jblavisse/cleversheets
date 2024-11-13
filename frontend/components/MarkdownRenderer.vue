<template>
    <vue-markdown-it :options="markdownOptions" />
</template>

<script setup>
import { VueMarkdownIt } from '@f3ve/vue-markdown-it';
import 'highlight.js/styles/github.css';
import hljs from 'highlight.js';

const markdownOptions = {
    html: true,
    linkify: true,
    highlight: (str, lang) => {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return hljs.highlight(str, { language: lang }).value;
            // eslint-disable-next-line @typescript-eslint/no-unused-vars, no-empty
            } catch (__) {}
        }
        return '';
    },
};
</script>