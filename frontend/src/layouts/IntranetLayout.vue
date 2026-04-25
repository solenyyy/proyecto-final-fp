<template>
  <div class="d-flex flex-column min-vh-100">
    <IntranetHeader @toggle-sidebar="sidebarOpen = !sidebarOpen" />

    <div class="d-flex flex-grow-1 position-relative">
      <div
          v-if="sidebarOpen"
          class="sidebar-overlay d-lg-none"
          @click="sidebarOpen = false"
      />

      <IntranetSidebar
          :open="sidebarOpen"
          @close="sidebarOpen = false"
      />

      <main class="flex-grow-1 p-3 overflow-x-hidden">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import IntranetHeader from '../components/HeaderIntranet.vue'
import IntranetSidebar from '../components/SidebarNav.vue'

const sidebarOpen = ref(false)
</script>

<style scoped>
.sidebar-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  z-index: 99;
}
</style>