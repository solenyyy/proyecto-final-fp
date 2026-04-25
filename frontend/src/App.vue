<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Header from "./components/Header.vue"
import Footer from "./components/Footer.vue"

const route = useRoute()
const isIntranet = computed(() => route.path.startsWith('/intranet'))
</script>

<template>
  <Header v-if="!isIntranet" />
  <router-view />
  <Footer v-if="!isIntranet" />
</template>