<template>
  <div class="intranet-page">

    <div class="page-header">
      <div class="d-flex align-items-center gap-3">
        <RouterLink to="/intranet/actividades" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i>
        </RouterLink>
        <div>
          <h2 class="page-title mb-0">{{ activity.name || 'Actividad' }}</h2>
          <p class="page-subtitle mb-0">Editando actividad #{{ id }}</p>
        </div>
      </div>
      <div class="d-flex gap-2 align-items-center">
        <button class="btn btn-primary" @click="save" :disabled="saving">
          <i class="fas fa-floppy-disk me-2"></i>
          {{ saving ? 'Guardando...' : 'Guardar cambios' }}
        </button>
      </div>
    </div>

    <div v-if="loading" class="table-loading">
      <div class="spinner-border text-primary" role="status"></div>
      <span>Cargando actividad...</span>
    </div>

    <div v-else class="row g-4">

      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="text-uppercase text-muted fw-bold small mb-3">
            <i class="fas fa-circle-info me-2"></i>Información general
          </h6>
          <div class="mb-3">
            <label class="form-label">Nombre de la actividad *</label>
            <input type="text" class="form-control" v-model="activity.name" placeholder="Nombre de la actividad" />
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" rows="4" v-model="activity.description"
                      placeholder="Describe la actividad..."></textarea>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Fecha de inicio *</label>
              <input type="datetime-local" class="form-control" v-model="activity.startDate" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de fin *</label>
              <input type="datetime-local" class="form-control" v-model="activity.endDate" />
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 d-flex flex-column gap-4">

        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="text-uppercase text-muted fw-bold small mb-3">
            <i class="fas fa-users me-2"></i>Colectivo
          </h6>
          <div class="d-flex flex-column gap-2">
            <div
                v-for="col in collectives" :key="col.key"
                class="d-flex align-items-center gap-2 p-2 rounded-3 border cursor-pointer"
                :style="activity.collective === col.key
                ? 'border-color: var(--primary) !important; background: var(--primary-light); color: var(--primary); font-weight: 700'
                : 'border-color: #eee; color: #666'"
                @click="activity.collective = col.key as Collective">
              <i :class="col.icon"></i>
              <span>{{ col.name }}</span>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="text-uppercase text-muted fw-bold small mb-3">
            <i class="fas fa-hands-helping me-2"></i>Voluntario asignado
          </h6>
          <select class="form-select" v-model="activity.volunteer">
            <option value="">Sin asignar</option>
            <option v-for="vol in volunteers" :key="vol.id" :value="`/api/volunteers/${vol.id}`">
              {{ vol.name }}
            </option>
          </select>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'
import { plainToInstance } from 'class-transformer'
import { Activity, Collective } from '../../entity/activity.ts'
import { findOne, update } from '../../repository/activity.ts'

const route = useRoute()
const id = route.params.id as string

const loading = ref(true)
const saving = ref(false)
const toast = useToast()

const activity = ref<Activity>(plainToInstance(Activity, {}))
const volunteers = ref<any[]>([])

const collectives = [
  { key: 'ciudadanos', icon: 'fas fa-city',           name: 'Ciudadanos' },
  { key: 'mayores',    icon: 'fas fa-user-clock',      name: 'Mayores'   },
  { key: 'jovenes',   icon: 'fas fa-graduation-cap',  name: 'Jóvenes'   },
  { key: 'mujeres',   icon: 'fas fa-venus',            name: 'Mujeres'   },
]

const toDatetimeLocal = (iso: string) => {
  if (!iso) return ''
  return new Date(iso).toISOString().slice(0, 16)
}

onMounted(() => {
  findOne(id)
      .then(data => {
        data.startDate = toDatetimeLocal(data.startDate ?? '')
        data.endDate   = toDatetimeLocal(data.endDate ?? '')
        activity.value = data
        loading.value  = false
      })

  fetch('/api/volunteers')
      .then(res => res.json())
      .then(data => {
        volunteers.value = data['member'] ?? []
      })
})

function save() {
  saving.value = true
  update(id, activity.value)
      .then(() => toast.success('Actividad guardada correctamente'))
      .catch(err => toast.error(err.message))
      .finally(() => saving.value = false)
}
</script>