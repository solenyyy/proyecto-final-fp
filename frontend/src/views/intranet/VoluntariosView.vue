<template>
  <div class="intranet-page">
    <div class="page-header">
      <div>
        <h2 class="page-title">Voluntari@s</h2>
        <p class="page-subtitle">Listado de voluntari@s registrados</p>
      </div>
      <RouterLink to="/intranet/voluntarios/nueva" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo voluntari@
      </RouterLink>
    </div>

    <div v-if="loading" class="table-loading">
      <div class="spinner-border text-primary" role="status"></div>
      <span>Cargando voluntari@s...</span>
    </div>

    <div v-else class="table-card">
      <div class="table-toolbar">
        <div class="search-wrapper d-flex align-items-center gap-2">
          <i class="fas fa-search search-icon"></i>
          <input
              v-model="search"
              type="text"
              class="form-control search-input"
              placeholder="Buscar voluntari@..."
          />
        </div>
      </div>

      <div class="table-responsive">
        <table class="table intranet-table">
          <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>DNI / NIE</th>
            <th>Nacimiento</th>
            <th>Actividades</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="filtered.length === 0">
            <td colspan="7" class="empty-row">No hay voluntari@s que coincidan</td>
          </tr>
          <tr v-for="vol in filtered" :key="vol.id">
            <td class="text-muted">#{{ vol.id }}</td>
            <td>
              <div class="volunteer-name">
                <div class="avatar">{{ initials(vol.name) }}</div>
                <span class="fw-semibold">{{ vol.name }}</span>
              </div>
            </td>
            <td class="text-muted">{{ vol.email ?? '—' }}</td>
            <td><code>{{ vol.dniNie }}</code></td>
            <td>{{ formatDate(vol.birthDate) }}</td>
            <td>
              <span class="activity-count">{{ vol.activitiesCount ?? 0 }}</span>
            </td>
            <td>
              <RouterLink :to="`/intranet/voluntarios/${vol.id}`" class="btn btn-sm btn-ghost">
                <i class="fas fa-pen"></i>
              </RouterLink>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const loading = ref(true)
const volunteers = ref<any[]>([])
const search = ref('')

onMounted(() => {
  fetch('/api/volunteers')
      .then(res => res.json())
      .then((data) => {
        volunteers.value = data['member'] ?? []
        loading.value = false
      })
})

const filtered = computed(() =>
    volunteers.value.filter(v =>
        v.name.toLowerCase().includes(search.value.toLowerCase()) ||
        v.email?.toLowerCase().includes(search.value.toLowerCase()) ||
        v.dniNie?.toLowerCase().includes(search.value.toLowerCase())
    )
)

const initials = (name: string) =>
    name.split(' ').map((n: string) => n[0]).slice(0, 2).join('').toUpperCase()

const formatDate = (iso: string) => {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>