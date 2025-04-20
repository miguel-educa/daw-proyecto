import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from '@/router'
import App from '@/App.vue'
import '@/assets/css/bulma/bulma.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import '@/assets/css/main.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
