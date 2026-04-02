<template>
  <div>
    <section class="hero-section">
      <div class="container">
        <div class="row align-items-center min-vh-85">
          <div class="col-lg-6 hero-text">
            <span class="badge-pill mb-3">🌍 Plataforma de voluntariado</span>
            <h1 class="hero-title">Transforma tu tiempo<br>en <span class="highlight">impacto real</span></h1>
            <p class="hero-subtitle">
              Conectamos voluntarios comprometidos con actividades sociales que marcan la diferencia
              en ciudadanos, jóvenes, mujeres y personas mayores.
            </p>
            <div class="d-flex gap-3 flex-wrap">
              <a href="#signup" class="btn btn-primary btn-lg px-4">Hazte voluntario</a>
              <a href="#how" class="btn btn-outline-secondary btn-lg px-4">Saber más</a>
            </div>
            <div class="hero-stats mt-5 d-flex gap-4 flex-wrap">
              <div class="stat-item">
                <span class="stat-number">+240</span>
                <span class="stat-label">Voluntarios activos</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">+80</span>
                <span class="stat-label">Actividades realizadas</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">4</span>
                <span class="stat-label">Colectivos atendidos</span>
              </div>
            </div>
          </div>
          <div class="col-lg-6 d-none d-lg-flex justify-content-center">
            <div class="hero-illustration">
              <div class="illustration-card card-1">🤝 Apoyo a mayores</div>
              <div class="illustration-card card-2">📚 Talleres jóvenes</div>
              <div class="illustration-card card-3">💪 Empoderamiento</div>
              <div class="illustration-blob"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="collectives-section py-5" id="about">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="section-title">¿A quién ayudamos?</h2>
          <p class="section-subtitle">Cuatro colectivos, infinitas formas de contribuir</p>
        </div>
        <div class="row g-4">
          <div class="col-sm-6 col-lg-3" v-for="col in collectives" :key="col.key">
            <div class="collective-card h-100">
              <div class="collective-icon">{{ col.icon }}</div>
              <h5>{{ col.name }}</h5>
              <p>{{ col.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="how-section py-5" id="how">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="section-title">Cómo funciona</h2>
          <p class="section-subtitle">Empezar es muy fácil</p>
        </div>
        <div class="row g-4 justify-content-center">
          <div class="col-md-4" v-for="(step, i) in steps" :key="i">
            <div class="step-card text-center">
              <div class="step-number">{{ i + 1 }}</div>
              <h5 class="mt-3">{{ step.title }}</h5>
              <p class="text-muted">{{ step.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="signup-section py-5" id="signup">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="signup-card">
              <div class="text-center mb-4">
                <h2 class="section-title">Únete como voluntario</h2>
                <p class="text-muted">Rellena el formulario y nos pondremos en contacto contigo</p>
              </div>
              <form @submit.prevent>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Nombre completo *</label>
                    <input type="text" class="form-control" placeholder="Ana García" v-model="form.name" />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" placeholder="ana@email.com" v-model="form.email" />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">DNI / NIE *</label>
                    <input type="text" class="form-control" placeholder="12345678A" v-model="form.dniNie" />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Fecha de nacimiento *</label>
                    <input type="date" class="form-control" v-model="form.birthDate" />
                  </div>
                  <div class="col-12">
                    <label class="form-label">Colectivo de interés</label>
                    <select class="form-select" v-model="form.collective">
                      <option value="">Selecciona una opción</option>
                      <option v-for="col in collectives" :key="col.key" :value="col.key">
                        {{ col.icon }} {{ col.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12">
                    <label class="form-label">Cuéntanos algo sobre ti</label>
                    <textarea class="form-control" rows="3"
                              placeholder="¿Por qué quieres ser voluntario? ¿Qué puedes aportar?"
                              v-model="form.bio"></textarea>
                  </div>
                  <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                      Enviar solicitud
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'

const collectives = [
  { key: 'ciudadanos', icon: '🏙️', name: 'Ciudadanos', desc: 'Actividades comunitarias abiertas a toda la población.' },
  { key: 'mayores',    icon: '🧓', name: 'Mayores',     desc: 'Acompañamiento, talleres y apoyo a personas de la tercera edad.' },
  { key: 'jovenes',   icon: '🎒', name: 'Jóvenes',     desc: 'Refuerzo escolar, orientación y actividades para adolescentes.' },
  { key: 'mujeres',   icon: '💜', name: 'Mujeres',     desc: 'Talleres de empoderamiento, igualdad y desarrollo personal.' },
]

const steps = [
  { title: 'Regístrate',       desc: 'Rellena el formulario con tus datos básicos.' },
  { title: 'Elige actividad',  desc: 'Consulta las actividades disponibles y apúntate.' },
  { title: '¡Haz el bien!',    desc: 'Participa y deja huella en tu comunidad.' },
]

const form = reactive({
  name: '',
  email: '',
  dniNie: '',
  birthDate: '',
  collective: '',
  bio: '',
})
</script>

<style scoped>
</style>