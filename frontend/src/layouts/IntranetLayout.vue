<template>
  <div class="intranet-wrapper">
    <SidebarNav />
    <main class="intranet-main">
      <RouterView />
    </main>
  </div>
</template>

<script setup lang="ts">
import SidebarNav from '../components/SidebarNav.vue'
</script>