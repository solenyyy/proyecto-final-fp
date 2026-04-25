<template>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <RouterLink class="navbar-brand fw-bold" to="/intranet/actividades">
        <span class="brand-icon">❤️</span> VolunTrack
      </RouterLink>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small d-none d-md-block">
          <i class="fas fa-user me-1"></i>{{ coordinatorName }}
        </span>
        <button class="btn btn-outline-secondary btn-sm" @click="handleLogout">
          <i class="fas fa-right-from-bracket me-2"></i>Salir
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { logout } from '../services/auth'

const router = useRouter()
const coordinatorName = localStorage.getItem('coordinatorName') ?? 'Coordinador'

function handleLogout() {
  logout()
  router.push('/login')
}
</script>