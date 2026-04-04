<template>
  <div class="intranet-page">

    <div class="page-header">
      <div class="d-flex align-items-center gap-3">
        <RouterLink to="/intranet/voluntarios" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i>
        </RouterLink>
        <div>
          <h2 class="page-title mb-0">{{ volunteer.name || 'Voluntario' }}</h2>
          <p class="page-subtitle mb-0">{{ isNew ? 'Nuevo voluntario' : `Editando voluntario #${id}` }}</p>
        </div>
      </div>
      <div class="d-flex gap-2 align-items-center">
        <button v-if="isNew" class="btn btn-primary" @click="handleCreate" :disabled="saving">
          <i class="fas fa-plus me-2"></i>
          {{ saving ? 'Creando...' : 'Crear voluntario' }}
        </button>
        <button v-else class="btn btn-primary" @click="handleUpdate" :disabled="saving">
          <i class="fas fa-floppy-disk me-2"></i>
          {{ saving ? 'Guardando...' : 'Guardar cambios' }}
        </button>
      </div>
    </div>

    <div v-if="loading" class="table-loading">
      <div class="spinner-border text-primary" role="status"></div>
      <span>Cargando voluntario...</span>
    </div>

    <div v-else class="row g-4">

      <div class="col-lg-8 d-flex flex-column gap-4">

        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="text-uppercase text-muted fw-bold small mb-3">
            <i class="fas fa-circle-info me-2"></i>Información personal
          </h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre completo *</label>
              <input type="text" class="form-control"
                     :class="{ 'is-invalid': errors.name?.length }"
                     v-model="volunteer.name" placeholder="Nombre completo" />
              <div v-for="error in errors.name" :key="error" class="invalid-feedback">{{ error }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control"
                     :class="{ 'is-invalid': errors.email?.length }"
                     v-model="volunteer.email" placeholder="correo@email.com" />
              <div v-for="error in errors.email" :key="error" class="invalid-feedback">{{ error }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">DNI / NIE *</label>
              <input type="text" class="form-control"
                     :class="{ 'is-invalid': errors.dniNie?.length }"
                     v-model="volunteer.dniNie" placeholder="12345678A" />
              <div v-for="error in errors.dniNie" :key="error" class="invalid-feedback">{{ error }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de nacimiento *</label>
              <input type="date" class="form-control"
                     :class="{ 'is-invalid': errors.birthDate?.length }"
                     v-model="volunteer.birthDate" />
              <div v-for="error in errors.birthDate" :key="error" class="invalid-feedback">{{ error }}</div>
            </div>
            <div class="col-12">
              <label class="form-label">Bio</label>
              <textarea class="form-control"
                        :class="{ 'is-invalid': errors.bio?.length }"
                        rows="3" v-model="volunteer.bio"
                        placeholder="Cuéntanos algo sobre este voluntario..."></textarea>
              <div v-for="error in errors.bio" :key="error" class="invalid-feedback">{{ error }}</div>
            </div>
          </div>
        </div>

        <div v-if="!isNew" class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="text-uppercase text-muted fw-bold small mb-3">
            <i class="fas fa-calendar-alt me-2"></i>Actividades asignadas
            <span class="badge bg-secondary ms-2">{{ activities.length }}</span>
          </h6>
          <div v-if="activities.length === 0" class="text-muted text-center py-3">
            <i class="fas fa-calendar-xmark fa-2x mb-2 d-block"></i>
            Sin actividades asignadas
          </div>
          <div v-else class="table-responsive">
            <table class="table intranet-table mb-0">
              <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Colectivo</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="act in activities" :key="act.id">
                <td class="text-muted">#{{ act.id }}</td>
                <td class="fw-semibold">{{ act.name }}</td>
                <td>
                    <span class="collective-badge" :class="act.collective">
                      <i :class="collectiveIcon(act.collective)"></i>
                      {{ collectiveLabel(act.collective) }}
                    </span>
                </td>
                <td>{{ formatDate(act.startDate) }}</td>
                <td>{{ formatDate(act.endDate) }}</td>
                <td>
                  <RouterLink :to="`/intranet/actividades/${act.id}`" class="btn btn-sm btn-ghost">
                    <i class="fas fa-pen"></i>
                  </RouterLink>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div v-if="!isNew" class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="text-uppercase text-muted fw-bold small mb-3">
            <i class="fas fa-chart-simple me-2"></i>Resumen
          </h6>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
              <span class="text-muted small fw-semibold">Actividades totales</span>
              <span class="fw-bold fs-5">{{ volunteer.activitiesCount ?? 0 }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
              <span class="text-muted small fw-semibold">Fecha de nacimiento</span>
              <span class="fw-semibold">{{ formatDate(volunteer.birthDate) }}</span>
            </div>
          </div>
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
import { Volunteer } from '../../entity/volunteer.ts'
import { create, findOne, update } from '../../repository/volunteer.ts'
import { collectives } from '../../utils/generalVars.ts'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const id = route.params.id as string | undefined
const isNew = computed(() => !route.params.id)

const loading = ref(true)
const saving = ref(false)
const volunteer = ref<Volunteer>(plainToInstance(Volunteer, {}))
const activities = ref<any[]>([])
const errors = ref<Record<string, string[]>>({})

const collectiveLabel = (key: string) => collectives.find(c => c.key === key)?.name ?? key
const collectiveIcon  = (key: string) => collectives.find(c => c.key === key)?.icon ?? 'fas fa-tag'

const formatDate = (iso: string | null) => {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' })
}

const toDateLocal = (iso: string) => {
  if (!iso) return ''
  return new Date(iso).toISOString().slice(0, 10)
}

onMounted(() => {
  if (!isNew.value) {
    findOne(id!)
        .then(data => {
          data.birthDate = toDateLocal(data.birthDate ?? '')
          volunteer.value = data
          loading.value = false
        })

    fetch(`/api/activities?volunteer.id=${id}`)
        .then(res => res.json())
        .then(data => {
          activities.value = data['member'] ?? []
        })
  } else {
    loading.value = false
  }
})

function handleCreate() {
  saving.value = true
  errors.value = {}
  create(volunteer.value)
      .then(data => {
        saving.value = false
        toast.success('Voluntari@ creado correctamente')
        router.push(`/intranet/voluntarios/${data.id}`)
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
  update(id!, volunteer.value)
      .then(data => {
        data.birthDate = toDateLocal(data.birthDate ?? '')
        volunteer.value = data
        saving.value = false
        toast.success('Voluntari@ guardado correctamente')
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