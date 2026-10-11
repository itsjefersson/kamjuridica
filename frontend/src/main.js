import { createApp } from 'vue'

// Bootstrap 5
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Estilos propios
import './style.css'

import App from './App.vue'
import router from './router'

const app = createApp(App)

// Directiva global de aparición al hacer scroll (animación "reveal").
const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in')
        revealObserver.unobserve(entry.target)
      }
    })
  },
  { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
)

app.directive('reveal', {
  mounted(el, binding) {
    el.classList.add('reveal')
    if (binding.value) {
      el.style.transitionDelay = `${binding.value}ms`
    }
    revealObserver.observe(el)
  },
})

app.use(router).mount('#app')
