<template>
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Exportador Contábil</h2>
      
      <!-- Mensagem de status -->
      <div v-if="mensagem" class="mb-4 p-4 rounded-lg" :class="mensagemClass">
        {{ mensagem }}
      </div>

      <div class="space-y-6">
        <!-- Seleção de arquivo importado -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Arquivo Importado</label>
          <select v-model="importacaoId" @change="selecionarImportacao" class="w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Selecione um arquivo importado (opcional)</option>
            <option v-for="importacao in importacoes" :key="importacao.id" :value="importacao.id">
              {{ importacao.nome_arquivo }} 
              <span v-if="importacao.empresa">- {{ importacao.empresa.nome }}</span>
              ({{ importacao.data_inicial }} a {{ importacao.data_final }})
              - {{ formatarData(importacao.created_at) }}
            </option>
          </select>
        </div>
        
        <!-- Informações da Importação Selecionada -->
        <div v-if="importacaoSelecionada" class="bg-blue-50 p-4 rounded-lg">
          <h3 class="font-semibold text-blue-800 mb-3">Informações da Importação</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
              <p class="text-blue-700"><strong>Arquivo:</strong> {{ importacaoSelecionada.nome_arquivo }}</p>
              <p class="text-blue-700"><strong>Empresa:</strong> 
                <span v-if="importacaoSelecionada.empresa">
                  {{ importacaoSelecionada.empresa.nome }}
                  <span v-if="importacaoSelecionada.empresa.codigo_sistema">
                    (Código: {{ importacaoSelecionada.empresa.codigo_sistema }})
                  </span>
                </span>
                <span v-else class="text-gray-500">Não informada</span>
              </p>
              <p class="text-blue-700"><strong>Período:</strong> {{ importacaoSelecionada.data_inicial }} a {{ importacaoSelecionada.data_final }}</p>
            </div>
            <div>
              <p class="text-blue-700"><strong>Data de Importação:</strong> {{ formatarDataHora(importacaoSelecionada.created_at) }}</p>
              <p class="text-blue-700"><strong>Status:</strong> 
                <span class="px-2 py-1 text-xs rounded-full" :class="statusClass(importacaoSelecionada.status)">
                  {{ formatarStatus(importacaoSelecionada.status) }}
                </span>
              </p>
              <p v-if="importacaoSelecionada.total_registros" class="text-blue-700">
                <strong>Registros Importados:</strong> {{ formatarNumero(importacaoSelecionada.total_registros) }}
              </p>
            </div>
          </div>
        </div>
        
        <!-- Período -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
            <input type="date" v-model="dataInicio" class="w-full rounded-md border-gray-300 shadow-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
            <input type="date" v-model="dataFim" class="w-full rounded-md border-gray-300 shadow-sm">
          </div>
        </div>

        <!-- Formato -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Formato do Arquivo</label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="radio" v-model="formato" value="txt" class="mr-2">
              <span>TXT (campos posicionais)</span>
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="formato" value="csv" class="mr-2">
              <span>CSV (separado por ponto e vírgula)</span>
            </label>
          </div>
        </div>

        <!-- Layout -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Layout de Exportação</label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="radio" v-model="layoutExport" value="dominio" class="mr-2">
              <span>Lançamentos Contábeis em Lote (Leiaute <b>Domínio Sistemas</b>)</span>
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="layoutExport" value="padrao" class="mr-2">
              <span>Padrão - Uma linha por lançamento</span>
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="layoutExport" value="contabil" class="mr-2">
              <span>Contábil - Linha de débito e crédito separadas</span>
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="layoutExport" value="simples" class="mr-2">
              <span>Simples - Apenas data, histórico e valor</span>
            </label>
          </div>
        </div>

        <!-- Campos específicos para Domínio -->
        <div v-if="layoutExport === 'dominio'" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-blue-50 rounded-lg">
          <div v-if="!codigoEmpresa || !cnpjEmpresa" class="col-span-2 mb-4 p-3 bg-yellow-100 border border-yellow-400 rounded-lg">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Atenção</h3>
                <div class="mt-2 text-sm text-yellow-700">
                  <p>Para o layout Domínio, os campos <strong>Código da Empresa</strong> e <strong>CNPJ da Empresa</strong> são obrigatórios.</p>
                  <p v-if="importacaoId" class="mt-1">Selecione uma importação que tenha empresa associada ou preencha manualmente.</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Seleção manual de empresa -->
          <div v-if="!codigoEmpresa || !cnpjEmpresa" class="col-span-2 mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Empresa</label>
            <select v-model="empresaSelecionada" @change="selecionarEmpresa" class="w-full rounded-md border-gray-300 shadow-sm">
              <option value="">Selecione uma empresa...</option>
              <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                {{ empresa.nome }} (Código: {{ empresa.codigo_sistema }}, CNPJ: {{ empresa.cnpj }})
              </option>
            </select>
            <p class="mt-1 text-sm text-gray-500">Selecione uma empresa para preencher automaticamente os campos obrigatórios.</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Código da Empresa</label>
            <input type="text" v-model="codigoEmpresa" placeholder="Ex: 0000001" class="w-full rounded-md border-gray-300 shadow-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">CNPJ da Empresa</label>
            <input type="text" v-model="cnpjEmpresa" placeholder="00.000.000/0000-00" class="w-full rounded-md border-gray-300 shadow-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Nota</label>
            <select v-model="tipoNota" class="w-full rounded-md border-gray-300 shadow-sm">
              <option value="01">01 - Contabilidade</option>
              <option value="02">02 - Entradas</option>
              <option value="03">03 - Saídas</option>
              <option value="04">04 - Serviços</option>
              <option value="05">05 - Contabilidade-Lançamentos em lote</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sistema</label>
            <select v-model="sistema" class="w-full rounded-md border-gray-300 shadow-sm">
              <option value="1">1 - Contabilidade</option>
              <option value="2">2 - Caixa</option>
              <option value="0">0 - Outro</option>
            </select>
          </div>
        </div>

        <!-- Quantidade de Registros -->
        <div v-if="importacaoId || (dataInicio && dataFim)" class="bg-green-50 p-4 rounded-lg">
          <h3 class="font-semibold text-green-800 mb-2">Resumo da Exportação</h3>
          <div class="text-sm text-green-700">
            <p><strong>Registros que serão exportados:</strong> {{ formatarNumero(quantidadeRegistros) }} lançamento(s)</p>
            <div v-if="importacaoSelecionada">
              <p><strong>Arquivo:</strong> {{ importacaoSelecionada.nome_arquivo }}</p>
              <p><strong>Empresa:</strong> 
                <span v-if="importacaoSelecionada.empresa">
                  {{ importacaoSelecionada.empresa.nome }}
                </span>
                <span v-else class="text-gray-500">Não informada</span>
              </p>
              <p><strong>Período:</strong> {{ importacaoSelecionada.data_inicial }} a {{ importacaoSelecionada.data_final }}</p>
              <p><strong>Data de Importação:</strong> {{ formatarData(importacaoSelecionada.created_at) }}</p>
            </div>
            <div v-else>
              <p><strong>Período:</strong> {{ dataInicio }} a {{ dataFim }}</p>
            </div>
          </div>
        </div>

        <!-- Descrição dos Layouts -->
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="font-semibold text-gray-800 mb-2">Descrição dos Layouts:</h3>
          <div class="space-y-2 text-sm text-gray-600">
            <div>
              <strong>Padrão:</strong> Exporta todos os campos em uma linha por lançamento
            </div>
            <div>
              <strong>Contábil:</strong> Separa débito e crédito em linhas diferentes (padrão contábil)
            </div>
            <div>
              <strong>Simples:</strong> Exporta apenas os campos essenciais para relatórios básicos
            </div>
            <div>
              <strong>Domínio Sistemas:</strong> Layout específico para importação no sistema Domínio, com registros 01, 02, 03 e 99
            </div>
          </div>
        </div>

        <button 
          type="button"
          @click="exportar"
          :disabled="processando"
          class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="!processando">Exportar Lançamentos</span>
          <span v-else>Gerando arquivo...</span>
        </button>
      </div>

      <!-- Arquivo Gerado -->
      <div v-if="arquivoGerado" class="mt-6 p-4 bg-green-50 rounded-lg">
        <h3 class="font-semibold text-green-800 mb-2">Arquivo Gerado</h3>
        <div class="mb-3">
          <p class="text-green-700"><strong>Nome do arquivo:</strong> {{ arquivoGerado }}</p>
          <p class="text-green-700"><strong>Registros exportados:</strong> {{ formatarNumero(quantidadeRegistros) }} lançamento(s)</p>
        </div>
        <a 
          :href="downloadUrl"
          class="inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700"
        >
          Download do Arquivo
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

export default {
  name: 'ExportadorContabil',
  setup() {
    // Estado reativo
    const dataInicio = ref('')
    const dataFim = ref('')
    const formato = ref('txt')
    const layoutExport = ref('dominio')
    const codigoEmpresa = ref('')
    const cnpjEmpresa = ref('')
    const tipoNota = ref('05')
    const sistema = ref('1')
    const processando = ref(false)
    const mensagem = ref('')
    const arquivoGerado = ref('')
    const quantidadeRegistros = ref(0)
    const importacaoId = ref('')
    const importacoes = ref([])
    const empresas = ref([])
    const empresaSelecionada = ref('')
    const usuario = ref('INTEGRAR02')

    // Computed properties
    const mensagemClass = computed(() => {
      return mensagem.value.includes('Erro') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'
    })

    const importacaoSelecionada = computed(() => {
      if (!importacaoId.value) return null
      return importacoes.value.find(imp => imp.id == importacaoId.value)
    })

    const downloadUrl = computed(() => {
      if (!arquivoGerado.value) return '#'
      return `/download-arquivo/${arquivoGerado.value}`
    })

    // Métodos
    const formatarData = (data) => {
      return new Date(data).toLocaleDateString('pt-BR')
    }

    const formatarDataHora = (data) => {
      return new Date(data).toLocaleString('pt-BR')
    }

    const formatarNumero = (numero) => {
      return new Intl.NumberFormat('pt-BR').format(numero)
    }

    const formatarStatus = (status) => {
      const statusMap = {
        'concluida': 'Concluída',
        'processando': 'Processando',
        'erro': 'Erro'
      }
      return statusMap[status] || status
    }

    const statusClass = (status) => {
      const classMap = {
        'concluida': 'bg-green-100 text-green-800',
        'processando': 'bg-yellow-100 text-yellow-800',
        'erro': 'bg-red-100 text-red-800'
      }
      return classMap[status] || 'bg-gray-100 text-gray-800'
    }

    const carregarDados = async () => {
      try {
        const [importacoesResponse, empresasResponse] = await Promise.all([
          axios.get('/api/importacoes'),
          axios.get('/api/empresas')
        ])
        
        importacoes.value = importacoesResponse.data
        empresas.value = empresasResponse.data
        
        // Definir datas padrão
        const hoje = new Date()
        const inicioMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1)
        const fimMes = new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0)
        
        dataInicio.value = inicioMes.toISOString().split('T')[0]
        dataFim.value = fimMes.toISOString().split('T')[0]
      } catch (error) {
        console.error('Erro ao carregar dados:', error)
        mensagem.value = 'Erro ao carregar dados iniciais'
      }
    }

    const selecionarImportacao = async () => {
      if (!importacaoId.value) return

      try {
        const response = await axios.get(`/api/importacoes/${importacaoId.value}`)
        const importacao = response.data

        if (importacao.empresa) {
          codigoEmpresa.value = importacao.empresa.codigo_sistema || ''
          cnpjEmpresa.value = (importacao.empresa.cnpj || '').replace(/\D/g, '')
        } else {
          // Tentar buscar empresa pelo código
          const empresaResponse = await axios.get(`/api/empresas/buscar-por-codigo/${importacao.codigo_empresa}`)
          if (empresaResponse.data) {
            codigoEmpresa.value = empresaResponse.data.codigo_sistema || ''
            cnpjEmpresa.value = (empresaResponse.data.cnpj || '').replace(/\D/g, '')
          } else {
            codigoEmpresa.value = ''
            cnpjEmpresa.value = ''
          }
        }

        // Buscar datas dos lançamentos
        const lancamentosResponse = await axios.get(`/api/lancamentos/datas/${importacaoId.value}`)
        if (lancamentosResponse.data.data_min && lancamentosResponse.data.data_max) {
          dataInicio.value = lancamentosResponse.data.data_min
          dataFim.value = lancamentosResponse.data.data_max
        } else {
          dataInicio.value = importacao.data_inicial || dataInicio.value
          dataFim.value = importacao.data_final || dataFim.value
        }

        await atualizarQuantidadeRegistros()
      } catch (error) {
        console.error('Erro ao selecionar importação:', error)
        mensagem.value = 'Erro ao carregar dados da importação'
      }
    }

    const selecionarEmpresa = () => {
      if (!empresaSelecionada.value) return

      const empresa = empresas.value.find(emp => emp.id == empresaSelecionada.value)
      if (empresa) {
        codigoEmpresa.value = empresa.codigo_sistema || ''
        cnpjEmpresa.value = (empresa.cnpj || '').replace(/\D/g, '')
      }
    }

    const atualizarQuantidadeRegistros = async () => {
      try {
        const params = {}
        if (importacaoId.value) {
          params.importacao_id = importacaoId.value
        } else if (dataInicio.value && dataFim.value) {
          params.data_inicio = dataInicio.value
          params.data_fim = dataFim.value
        }

        const response = await axios.get('/api/lancamentos/quantidade', { params })
        quantidadeRegistros.value = response.data.quantidade
      } catch (error) {
        console.error('Erro ao buscar quantidade de registros:', error)
        quantidadeRegistros.value = 0
      }
    }

    const exportar = async () => {
      // Validações
      if (!dataInicio.value || !dataFim.value) {
        mensagem.value = 'Erro de validação: Data início e data fim são obrigatórias'
        return
      }

      if (new Date(dataFim.value) < new Date(dataInicio.value)) {
        mensagem.value = 'Erro de validação: Data fim deve ser maior ou igual à data início'
        return
      }

      if (layoutExport.value === 'dominio') {
        if (!codigoEmpresa.value || !cnpjEmpresa.value) {
          mensagem.value = 'Erro de validação: Código da empresa e CNPJ são obrigatórios para o layout Domínio'
          return
        }
      }

      processando.value = true
      mensagem.value = 'Gerando arquivo...'

      try {
        const dados = {
          data_inicio: dataInicio.value,
          data_fim: dataFim.value,
          formato: formato.value,
          layout_export: layoutExport.value,
          codigo_empresa: codigoEmpresa.value,
          cnpj_empresa: cnpjEmpresa.value,
          tipo_nota: tipoNota.value,
          sistema: sistema.value,
          importacao_id: importacaoId.value
        }

        const response = await axios.post('/api/exportar-contabil', dados)
        
        arquivoGerado.value = response.data.arquivo
        quantidadeRegistros.value = response.data.quantidade_registros
        mensagem.value = `Arquivo gerado com sucesso! ${formatarNumero(response.data.quantidade_registros)} lançamento(s) exportado(s).`
      } catch (error) {
        console.error('Erro na exportação:', error)
        mensagem.value = error.response?.data?.message || 'Erro na exportação'
      } finally {
        processando.value = false
      }
    }

    // Watchers
    watch([dataInicio, dataFim], () => {
      if (dataInicio.value && dataFim.value) {
        atualizarQuantidadeRegistros()
      }
    })

    // Lifecycle
    onMounted(() => {
      carregarDados()
    })

    return {
      // Estado
      dataInicio,
      dataFim,
      formato,
      layoutExport,
      codigoEmpresa,
      cnpjEmpresa,
      tipoNota,
      sistema,
      processando,
      mensagem,
      arquivoGerado,
      quantidadeRegistros,
      importacaoId,
      importacoes,
      empresas,
      empresaSelecionada,
      usuario,

      // Computed
      mensagemClass,
      importacaoSelecionada,
      downloadUrl,

      // Métodos
      formatarData,
      formatarDataHora,
      formatarNumero,
      formatarStatus,
      statusClass,
      selecionarImportacao,
      selecionarEmpresa,
      exportar
    }
  }
}
</script> 