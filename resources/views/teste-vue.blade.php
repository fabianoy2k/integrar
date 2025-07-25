@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">🔍 Teste Vue.js</h1>
                
                <div id="test-results" class="mb-6">
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <strong>Status:</strong> Verificando se Vue.js está funcionando...
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Como verificar:</h3>
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Abra o DevTools (F12)</li>
                            <li>Vá na aba Console</li>
                            <li>Procure por mensagens do Vue</li>
                            <li>Verifique se há badges "Vue.js" na navegação</li>
                        </ol>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Teste no Console:</h3>
                        <p class="mb-2">Cole este código no console:</p>
                        <code class="bg-gray-200 p-2 rounded text-sm block">
                            console.log('Vue:', !!window.Vue);<br>
                            console.log('Componente:', !!document.querySelector('navigation-component'));
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script de teste para verificar Vue
document.addEventListener('DOMContentLoaded', function() {
    const resultsDiv = document.getElementById('test-results');
    
    setTimeout(() => {
        let results = '';
        
        // Verificar se Vue está disponível
        if (window.Vue) {
            results += '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">✅ Vue.js está disponível!</div>';
        } else {
            results += '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">❌ Vue.js não está disponível</div>';
        }
        
        // Verificar se o componente está presente
        const navComponent = document.querySelector('navigation-component');
        if (navComponent) {
            results += '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">✅ Componente de navegação encontrado!</div>';
        } else {
            results += '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">❌ Componente de navegação não encontrado</div>';
        }
        
        // Verificar indicadores visuais
        const vueIndicators = document.querySelectorAll('.bg-green-500, .bg-blue-500');
        if (vueIndicators.length > 0) {
            results += '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">✅ Indicadores Vue encontrados: ' + vueIndicators.length + '</div>';
        } else {
            results += '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-2">⚠️ Indicadores Vue não encontrados</div>';
        }
        
        resultsDiv.innerHTML = results;
    }, 2000);
});
</script>
@endsection 