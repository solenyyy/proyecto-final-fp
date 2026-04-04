<template>
  <div class="intranet-page">

    <div class="page-header">
      <div class="d-flex align-items-center gap-3">
        <RouterLink to="/intranet/actividades" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i>
        </RouterLink>
        <div>
          <h2 class="page-title mb-0">{{ activity.name || 'Actividad' }}</h2>
          <p class="page-subtitle mb-0">{{ isNew ? 'Nueva actividad' : `Editando actividad #${id}` }}</p>
        </div>
      </div>
      <div class="d-flex gap-2 align-items-center">
        <button v-if="isNew" class="btn btn-primary" @click="handleCreate" :disabled="saving">
          <i class="fas fa-plus me-2"></i>
          {{ saving ? 'Creando...' : 'Crear actividad' }}
        </button>
        <button v-else class="btn btn-primary" @click="handleUpdate" :disabled="saving">
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
            <input type="text" class="form-control"
                   :class="{ 'is-invalid': errors.name?.length }"
                   v-model="activity.name"
                   placeholder="Nombre de la actividad" />
            <div v-for="error in errors.name" :key="error" class="invalid-feedback">
              {{ error }}
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control"
                      :class="{ 'is-invalid': errors.description?.length }"
                      rows="4" v-model="activity.description"
                      placeholder="Describe la actividad..."></textarea>
            <div v-for="error in errors.description" :key="error" class="invalid-feedback">
              {{ error }}
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Fecha de inicio *</label>
              <input type="datetime-local" class="form-control"
                     :class="{ 'is-invalid': errors.startDate?.length }"
                     v-model="activity.startDate" />
              <div v-for="error in errors.startDate" :key="error" class="invalid-feedback">
                {{ error }}
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Fecha de fin *</label>
              <input type="datetime-local" class="form-control"
                     :class="{ 'is-invalid': errors.endDate?.length }"
                     v-model="activity.endDate" />
              <div v-for="error in errors.endDate" :key="error" class="invalid-feedback">
                {{ error }}
              </div>
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
            <div v-for="error in errors.collective" :key="error" class="text-danger small mt-1">
              <i class="fas fa-triangle-exclamation me-1"></i>{{ error }}
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
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { plainToInstance } from 'class-transformer'
import { Activity, Collective } from '../../entity/activity.ts'
import { create, findOne, update } from '../../repository/activity.ts'
import { collectives } from '../../utils/generalVars.ts'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const id = route.params.id as string | undefined
const isNew = computed(() => !route.params.id)

const loading = ref(true)
const saving = ref(false)
const activity = ref<Activity>(plainToInstance(Activity, {}))
const volunteers = ref<any[]>([])
const errors = ref<Record<string, string[]>>({})

const toDatetimeLocal = (iso: string) => {
  if (!iso) return ''
  return new Date(iso).toISOString().slice(0, 16)
}

onMounted(() => {
  if (!isNew.value) {
    findOne(id!)
        .then(data => {
          data.startDate = toDatetimeLocal(data.startDate ?? '')
          data.endDate   = toDatetimeLocal(data.endDate ?? '')
          activity.value = data
          loading.value  = false
        })
  } else {
    loading.value = false
  }

  fetch('/api/volunteers')
      .then(res => res.json())
      .then(data => {
        volunteers.value = data['member'] ?? []
      })
})

function handleCreate() {
  saving.value = true
  errors.value = {}
  create(activity.value)
      .then(data => {
        saving.value = false
        toast.success('Actividad creada correctamente')
        router.push(`/intranet/actividades/${data.id}`)
      })
      .catch(err => {
        saving.value = false
        if (typeof err === 'object' && !(err instanceof Error)) {
          errors.value = err
        } else {
          toast.error(err.message)
        }
      })
}

function handleUpdate() {
  saving.value = true
  errors.value = {}
  const idUpdate = router.currentRoute.value.params.id
  update(idUpdate as any, activity.value)
      .then(data => {
        activity.value = data
        activity.value.startDate = toDatetimeLocal(data.startDate ?? '')
        activity.value.endDate   = toDatetimeLocal(data.endDate ?? '')
        saving.value = false
        toast.success('Actividad guardada correctamente')
      })
      .catch(err => {
        saving.value = false
        if (typeof err === 'object' && !(err instanceof Error)) {
          errors.value = err
        } else {
          toast.error(err.message)
        }
      })
}
</script>