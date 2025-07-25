<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Vue Simples</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>🔍 Teste Vue Simples</h1>
    
    <div id="app">
        <div class="test-box">
            <h3>🎉 Vue.js Funcionando!</h3>
            <p>Contador: @{{ count }}</p>
            <button @click="count++" style="background: #2196f3; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                Incrementar
            </button>
        </div>
    </div>

    <div id="test-results">
        <div class="test-box">
            <h3>📊 Resultados do Teste</h3>
            <p id="vue-status">Verificando...</p>
            <p id="component-status">Verificando...</p>
        </div>
    </div>

    <script>
        const { createApp } = Vue;
        
        // Aplicação principal
        const app = createApp({
            data() {
                return {
                    count: 0
                }
            }
        });
        
        app.mount('#app');
        
        // Verificar status
        setTimeout(() => {
            const vueStatus = document.getElementById('vue-status');
            const componentStatus = document.getElementById('component-status');
            
            if (window.Vue) {
                vueStatus.innerHTML = '✅ Vue.js está funcionando!';
                vueStatus.className = 'success';
            } else {
                vueStatus.innerHTML = '❌ Vue.js não está funcionando';
                vueStatus.className = 'error';
            }
            
            if (document.querySelector('#app').__vue_app__) {
                componentStatus.innerHTML = '✅ Componente Vue montado com sucesso!';
                componentStatus.className = 'success';
            } else {
                componentStatus.innerHTML = '❌ Componente Vue não foi montado';
                componentStatus.className = 'error';
            }
        }, 1000);
    </script>
</body>
</html> 