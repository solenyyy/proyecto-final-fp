<template>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <button class="btn btn-ghost btn-sm hamburger d-lg-none me-2" @click="$emit('toggle-sidebar')">
        <i class="fas fa-bars"></i>
      </button>

      <RouterLink class="navbar-brand fw-bold" to="/intranet/actividades">
        <span class="brand-icon">❤️</span> VolunTrack
      </RouterLink>

      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small d-none d-md-block">
          <i class="fas fa-user me-1"></i>{{ coordinatorName }}
        </span>

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

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { logout } from '../services/auth'

defineEmits(['toggle-sidebar'])

const router = useRouter()
const coordinatorName = localStorage.getItem('coordinatorName') ?? 'Coordinador'

function handleLogout() {
  logout()
  router.push('/login')
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