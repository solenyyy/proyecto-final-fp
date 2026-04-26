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
        <ul class="navbar-nav mx-auto gap-2 text-center">
          <li class="nav-item"><a class="nav-link" href="#about">Sobre nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="#how">Cómo funciona</a></li>
          <li class="nav-item d-flex justify-content-center mb-3 mb-lg-0">
            <a class="nav-link btn btn-outline-primary px-3" href="#signup">Únete</a>
          </li>
        </ul>
      </div>

      <RouterLink v-if="!authenticated" to="/login" class="btn btn-primary btn-sm px-3 mx-auto">
        <i class="fas fa-right-to-bracket me-2"></i>Conéctate
      </RouterLink>

      <div v-else class="d-flex align-items-center gap-2">
        <span class="text-muted small d-none d-md-block">{{ coordinatorName }}</span>

        <div class="dropdown">
          <button
              class="btn btn-ghost btn-sm rounded-circle apps-btn"
              data-bs-toggle="dropdown"
              aria-expanded="false"
          >
            <span class="dots-grid">
              <span v-for="i in 9" :key="i" class="dot" />
            </span>
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2">
            <li>
              <RouterLink to="/intranet/actividades" class="dropdown-item rounded-2 d-flex align-items-center gap-2">
                <span class="item-icon bg-body-secondary rounded-2">
                  <i class="fas fa-lock"></i>
                </span>
                Intranet
              </RouterLink>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <button class="dropdown-item rounded-2 d-flex align-items-center gap-2 text-danger" @click="handleLogout">
                <span class="item-icon rounded-2" style="background: rgba(220,53,69,.1)">
                  <i class="fas fa-right-from-bracket"></i>
                </span>
                Salir
              </button>
            </li>
          </ul>
        </div>
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

<style scoped>
.apps-btn {
  width: 36px;
  height: 36px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.dots-grid {
  display: grid;
  grid-template-columns: repeat(3, 5px);
  gap: 3px;
}

.dot {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: currentColor;
  opacity: 0.7;
}

.item-icon {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  flex-shrink: 0;
}
</style>