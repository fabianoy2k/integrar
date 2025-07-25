import './bootstrap';
import { createApp } from 'vue';
import ExportadorContabil from './components/ExportadorContabil.vue';
import Navigation from './components/Navigation.vue';
import VueMenu from './components/VueMenu.vue';

// Tornar Vue disponível globalmente para debug
window.Vue = { createApp };

// Função para montar componentes Vue
function mountVueComponents() {
    console.log('🔧 Iniciando montagem dos componentes Vue...');
    
    // Montar aplicação principal
    const appElement = document.getElementById('app');
    if (appElement) {
        console.log('📱 Montando aplicação principal...');
        const app = createApp({});
        app.component('exportador-contabil', ExportadorContabil);
        app.component('navigation-component', Navigation);
        app.component('vue-menu', VueMenu);
        app.mount('#app');
    }

    // Montar navegação se existir
    const navigationElement = document.getElementById('vue-navigation');
    if (navigationElement) {
        console.log('🧭 Montando navegação Vue...');
        const navigationApp = createApp({});
        navigationApp.component('navigation-component', Navigation);
        navigationApp.component('vue-menu', VueMenu);
        navigationApp.mount('#vue-navigation');
    }

    // Montar componentes Vue diretamente no body se necessário
    const navigationComponents = document.querySelectorAll('navigation-component');
    console.log('🔍 Componentes de navegação encontrados:', navigationComponents.length);
    
    navigationComponents.forEach((component, index) => {
        if (!component.__vue_app__) { // Verificar se já não foi montado
            console.log(`🎯 Montando componente ${index + 1}...`);
            const vueApp = createApp({});
            vueApp.component('navigation-component', Navigation);
            vueApp.component('vue-menu', VueMenu);
            vueApp.mount(component);
        }
    });
    
    console.log('✅ Montagem dos componentes Vue concluída!');
}

// Executar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    console.log('⏳ Aguardando carregamento do DOM...');
    document.addEventListener('DOMContentLoaded', mountVueComponents);
} else {
    console.log('🚀 DOM já carregado, executando montagem...');
    mountVueComponents();
}
