<template>
  <Teleport to="body">
    <div v-if="modelValue" class="modal-backdrop fade show" @click="$emit('update:modelValue', false)"></div>
    <div v-if="modelValue" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
          <div class="modal-body text-center p-5">
            <div class="confirm-icon mb-4">
              <i class="fas fa-triangle-exclamation"></i>
            </div>
            <h5 class="fw-bold mb-2">{{ title }}</h5>
            <p class="text-muted mb-4">{{ message }}</p>
            <div class="d-flex gap-3 justify-content-center">
              <button class="btn btn-outline-secondary px-4" @click="$emit('update:modelValue', false)">
                Cancelar
              </button>
              <button class="btn btn-danger px-4" @click="confirm">
                <i class="fas fa-trash me-2"></i>Sí, eliminar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
defineProps<{
  modelValue: boolean
  title?: string
  message?: string
}>()

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
  'confirm': []
}>()

function confirm() {
  emit('confirm')
  emit('update:modelValue', false)
}
</script>

<style scoped>
.modal-backdrop {
  z-index: 1040;
}
.modal {
  z-index: 1050;
}
.confirm-icon {
  width: 64px;
  height: 64px;
  background: #fff3cd;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  font-size: 1.8rem;
  color: #e85d26;
}
</style>