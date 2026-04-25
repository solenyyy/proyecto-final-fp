<template>
  <div class="intranet-page">
    <div class="page-header">
      <div>
        <h2 class="page-title">Actividades</h2>
        <p class="page-subtitle">Gestión de actividades por colectivo</p>
      </div>
      <RouterLink to="/intranet/actividades/nueva" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva actividad
      </RouterLink>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-6 col-lg-3" v-for="col in collectiveStats" :key="col.key">
        <div class="stat-card">
          <span class="stat-icon">
            <i :class="[col.icon, 'stat-icon']"></i>
          </span>
          <div>
            <p class="stat-value">{{ col.count }}</p>
            <p class="stat-label">{{ col.name }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="table-loading">
      <div class="spinner-border text-primary" role="status"></div>
      <span>Cargando actividades...</span>
    </div>

    <div v-else class="table-card">
      <div class="table-toolbar d-flex align-items-center">
        <i class="fas fa-search search-icon"></i>
        <input
            v-model="search"
            type="text"
            class="form-control search-input"
            placeholder="Buscar actividad..."
        />
        <select v-model="filterCollective" class="form-select filter-select">
          <option value="">Todos los colectivos</option>
          <option v-for="col in collectives" :key="col.key" :value="col.key">
            {{ col.name }}
          </option>
        </select>
      </div>

      <div class="table-responsive">
        <table class="table intranet-table">
          <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Colectivo</th>
            <th>Voluntario</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="filtered.length === 0">
            <td colspan="7" class="empty-row">No hay actividades que coincidan</td>
          </tr>
          <tr v-for="act in filtered" :key="act.id">
            <td class="text-muted">#{{ act.id }}</td>
            <td class="fw-semibold">{{ act.name }}</td>
            <td>
              <span class="collective-badge" :class="act.collective">
                <i :class="collectiveIcon(act.collective)"></i>
                {{ collectiveLabel(act.collective) }}
              </span>
            </td>
            <td>{{ act.volunteer?.name }}</td>
            <td>{{ formatDate(act.startDate) }}</td>
            <td>{{ formatDate(act.endDate) }}</td>
            <td>
              <RouterLink :to="`/intranet/actividades/${act.id}`" class="btn btn-sm btn-ghost">
                <i class="fas fa-pen"></i>
              </RouterLink>            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import {findAll} from "../../repository/activity.ts";

const loading = ref(true)
const activities = ref<any[]>([])
const search = ref('')
const filterCollective = ref('')

const collectives = [
  { key: 'ciudadanos', icon: 'fas fa-city',        name: 'Ciudadanos' },
  { key: 'mayores',    icon: 'fas fa-user-clock',   name: 'Mayores' },
  { key: 'jovenes',    icon: 'fas fa-graduation-cap', name: 'Jóvenes' },
  { key: 'mujeres',    icon: 'fas fa-venus',         name: 'Mujeres' },
]

onMounted(() => {
  findAll()
      .then(data => {
        activities.value = data
        loading.value = false
      })
})

const filtered = computed(() => {
  return activities.value.filter(a => {
    const matchSearch = a.name.toLowerCase().includes(search.value.toLowerCase())
    const matchCol = filterCollective.value ? a.collective === filterCollective.value : true
    return matchSearch && matchCol
  })
})

const collectiveStats = computed(() =>
    collectives.map(c => ({
      ...c,
      count: activities.value.filter(a => a.collective === c.key).length
    }))
)

const collectiveLabel = (key: string) => {
  return collectives.find(c => c.key === key)?.name ?? key
}

const collectiveIcon = (key: string) => {
  return collectives.find(c => c.key === key)?.icon ?? 'fas fa-tag'
}
const formatDate = (iso: string) => {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>