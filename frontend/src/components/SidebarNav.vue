<template>
  <aside class="sidebar">
    <nav class="sidebar-nav">
      <p class="nav-section-label">Gestión</p>
      <RouterLink to="/intranet/actividades" class="sidebar-link">
        <span class="link-icon"><i class="fas fa-calendar"></i></span> Actividades
      </RouterLink>
      <RouterLink to="/intranet/voluntarios" class="sidebar-link">
        <span class="link-icon"><i class="fas fa-users"></i></span> Voluntarios
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <RouterLink to="/" class="sidebar-link">
        <span class="link-icon"><i class="fas fa-home"></i></span> Volver al inicio
      </RouterLink>
    </div>
  </aside>
</template>