<template>
  <aside class="sidebar" :class="{ 'sidebar--open': open }">
    <div class="d-flex d-lg-none justify-content-end p-2">
      <button class="btn btn-ghost btn-sm text-white" @click="$emit('close')">
        <i class="fas fa-xmark"></i>
      </button>
    </div>

    <nav class="sidebar-nav">
      <p class="nav-section-label">Gestión</p>
      <RouterLink to="/intranet/actividades" class="sidebar-link" @click="$emit('close')">
        <span class="link-icon"><i class="fas fa-calendar"></i></span> Actividades
      </RouterLink>
      <RouterLink to="/intranet/voluntarios" class="sidebar-link" @click="$emit('close')">
        <span class="link-icon"><i class="fas fa-users"></i></span> Voluntarios
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <RouterLink to="/" class="sidebar-link" @click="$emit('close')">
        <span class="link-icon"><i class="fas fa-home"></i></span> Volver al inicio
      </RouterLink>
    </div>
  </aside>
</template>

<script setup lang="ts">
defineProps<{ open: boolean }>()
defineEmits(['close'])
</script>

<style scoped>
.sidebar {
  width: 240px;
  flex-shrink: 0;
}

@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 9999;
    transform: translateX(-100%);
    transition: transform 0.25s ease;
  }

  .sidebar--open {
    transform: translateX(0);
  }
}
</style>