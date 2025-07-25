import './bootstrap';
import { createApp } from 'vue';
import ExportadorContabil from './components/ExportadorContabil.vue';

// Criar aplicação Vue
const app = createApp({});

// Registrar componente global
app.component('exportador-contabil', ExportadorContabil);

// Montar aplicação
app.mount('#app');
