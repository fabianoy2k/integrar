import './bootstrap';

// Apenas para componentes que realmente precisam de Vue (como ExportadorContabil)
import { createApp } from 'vue';
import ExportadorContabil from './components/ExportadorContabil.vue';

// Montar apenas componentes Vue específicos (não o menu)
const appElement = document.getElementById('app');
if (appElement) {
    console.log('📱 Montando aplicação principal (sem menu Vue)...');
    const app = createApp({});
    app.component('exportador-contabil', ExportadorContabil);
    // NÃO montar navigation-component - agora é Blade
    app.mount('#app');
    console.log('✅ Aplicação principal montada (menu: Blade)');
}
