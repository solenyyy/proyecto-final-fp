<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Header from "./components/Header.vue"
import HeaderIntranet from "./components/HeaderIntranet.vue"
import Footer from "./components/Footer.vue"

const route = useRoute()
const isIntranet = computed(() => route.path.startsWith('/intranet'))
</script>

<template>
  <HeaderIntranet v-if="isIntranet" />
  <Header v-else />
  <router-view />
  <Footer v-if="!isIntranet" />
</template>