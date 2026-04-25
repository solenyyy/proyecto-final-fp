<template>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">
        <span class="brand-icon">❤️</span> VolunTrack
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto gap-2">
          <li class="nav-item"><a class="nav-link" href="#about">Sobre nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="#how">Cómo funciona</a></li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-primary px-3" href="#signup">Únete</a>
          </li>
        </ul>
      </div>
      <RouterLink v-if="!authenticated" to="/login" class="btn btn-primary btn-sm px-3">
        <i class="fas fa-right-to-bracket me-2"></i>Conéctate
      </RouterLink>
      <div v-else class="d-flex align-items-center gap-2">
        <span class="text-muted small">{{ coordinatorName }}</span>
        <button class="btn btn-outline-secondary btn-sm" @click="handleLogout">
          <i class="fas fa-right-from-bracket me-2"></i>Salir
        </button>
      </div>
    </div>
  </nav>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { isAuthenticated, logout } from '../services/auth'

const router = useRouter()
const authenticated = ref(isAuthenticated())
const coordinatorName = ref(localStorage.getItem('coordinatorName') ?? '')

function handleLogout() {
  logout()
  authenticated.value = false
  router.push('/')
}
</script>