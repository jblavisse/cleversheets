// https://nuxt.com/docs/api/configuration/nuxt-config
import Aura from '@primevue/themes/aura';
import { definePreset } from '@primevue/themes';

const MyPreset = definePreset(Aura, {
  semantic: {
      primary: {
          50: '{neutral.50}',
          100: '{neutral.100}',
          200: '{neutral.200}',
          300: '{neutral.300}',
          400: '{neutral.400}',
          500: '{neutral.500}',
          600: '{neutral.600}',
          700: '{neutral.700}',
          800: '{neutral.800}',
          900: '{neutral.900}',
          950: '{neutral.950}'
      },
      colorScheme: {
        light: {
            primary: {
                color: '{zinc.950}',
                inverseColor: '#ffffff',
                hoverColor: '{zinc.900}',
                activeColor: '{zinc.800}'
            },
            highlight: {
                background: '{zinc.950}',
                focusBackground: '{zinc.700}',
                color: '#ffffff',
                focusColor: '#ffffff'
            }
        },
        dark: {
            primary: {
                color: '{zinc.50}',
                inverseColor: '{zinc.950}',
                hoverColor: '{zinc.100}',
                activeColor: '{zinc.200}'
            },
            highlight: {
                background: 'rgba(250, 250, 250, .16)',
                focusBackground: 'rgba(250, 250, 250, .24)',
                color: 'rgba(255,255,255,.87)',
                focusColor: 'rgba(255,255,255,.87)'
            }
        }
    }
  }
});

export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.API_BASE_URL,
    },
  },
  modules: [
    '@primevue/nuxt-module',
    '@nuxt/eslint',
    '@pinia/nuxt',
    'pinia-plugin-persistedstate/nuxt'
  ],
  piniaPluginPersistedstate: {
    storage: 'cookies',
    cookieOptions: {
      sameSite: 'lax',
    },
    debug: true,
  },
  primevue: {
    options: {
      theme: {
          preset: MyPreset,
          options: {
            darkModeSelector: '.my-app-dark',
          }
      },
      ripple: true,
    }
  },
  css: [
    'primeflex/primeflex.css',
    'primeicons/primeicons.css',
  ]
});