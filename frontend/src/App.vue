<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Equipos</h1>
        <p class="mt-2 text-sm text-gray-600">Sistema de consulta y validación de equipos</p>
      </div>

      <!-- Textarea para códigos múltiples -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Filtrar por códigos (uno por línea)
        </label>
        <textarea
          v-model="codigosTexto"
          rows="4"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          placeholder="EQ001&#10;EQ002&#10;EQ003"
        ></textarea>
        <div class="mt-4 flex gap-3">
          <button
            @click="filtrarPorCodigos"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
          >
            <i class="pi pi-filter mr-2"></i>
            Filtrar por códigos pegados
          </button>
          <button
            @click="limpiarFiltros"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
          >
            <i class="pi pi-times mr-2"></i>
            Limpiar filtros
          </button>
        </div>

        <!-- Alerta de códigos no encontrados -->
        <div
          v-if="codigosNoEncontrados.length > 0"
          class="mt-4 bg-red-50 border-l-4 border-red-400 p-4"
        >
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="pi pi-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                Códigos no encontrados
              </h3>
              <div class="mt-2 text-sm text-red-700">
                <p>Los siguientes códigos no existen en la base de datos:</p>
                <ul class="list-disc list-inside mt-1">
                  <li v-for="codigo in codigosNoEncontrados" :key="codigo">
                    {{ codigo }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla con DataTable de PrimeVue -->
      <div class="bg-white rounded-lg shadow">
        <DataTable
          :value="equiposFiltrados"
          :paginator="true"
          :rows="10"
          :loading="loading"
          filterDisplay="row"
          v-model:filters="filters"
          :globalFilterFields="['codigo', 'cliente', 'tipo', 'estado']"
          class="p-datatable-sm"
        >
          <template #empty>
            <div class="text-center py-8 text-gray-500">
              No se encontraron equipos
            </div>
          </template>

          <Column
            field="codigo"
            header="Código"
            :sortable="true"
            style="min-width: 150px"
          >
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                @input="filterCallback()"
                class="p-column-filter"
                placeholder="Buscar por código"
              />
            </template>
          </Column>

          <Column
            field="cliente"
            header="Cliente"
            :sortable="true"
            style="min-width: 200px"
          >
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                @input="filterCallback()"
                class="p-column-filter"
                placeholder="Buscar por cliente"
              />
            </template>
          </Column>

          <Column
            field="tipo"
            header="Tipo"
            :sortable="true"
            style="min-width: 150px"
          >
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                @input="filterCallback()"
                class="p-column-filter"
                placeholder="Buscar por tipo"
              />
            </template>
          </Column>

          <Column
            field="estado"
            header="Estado"
            :sortable="true"
            style="min-width: 150px"
          >
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                @input="filterCallback()"
                class="p-column-filter"
                placeholder="Buscar por estado"
              />
            </template>
            <template #body="{ data }">
              <span
                :class="{
                  'px-2 py-1 rounded-full text-xs font-medium': true,
                  'bg-green-100 text-green-800': data.estado === 'Activo',
                  'bg-yellow-100 text-yellow-800': data.estado === 'En reparación',
                  'bg-blue-100 text-blue-800': data.estado === 'Mantenimiento'
                }"
              >
                {{ data.estado }}
              </span>
            </template>
          </Column>

          <Column
            field="fecha_entrega"
            header="Fecha de Entrega"
            :sortable="true"
            style="min-width: 150px"
          >
            <template #body="{ data }">
              {{ formatearFecha(data.fecha_entrega) }}
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import axios from 'axios'

const equipos = ref([])
const loading = ref(false)
const codigosTexto = ref('')
const codigosNoEncontrados = ref([])
const codigosFiltrados = ref([])

const filters = ref({
  codigo: { value: null, matchMode: FilterMatchMode.CONTAINS },
  cliente: { value: null, matchMode: FilterMatchMode.CONTAINS },
  tipo: { value: null, matchMode: FilterMatchMode.CONTAINS },
  estado: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const equiposFiltrados = computed(() => {
  if (codigosFiltrados.value.length === 0) {
    return equipos.value
  }
  return equipos.value.filter(equipo =>
    codigosFiltrados.value.some(codigo =>
      equipo.codigo.toLowerCase().includes(codigo.toLowerCase())
    )
  )
})

const cargarEquipos = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/equipos')
    equipos.value = response.data
  } catch (error) {
    console.error('Error al cargar equipos:', error)
  } finally {
    loading.value = false
  }
}

const filtrarPorCodigos = async () => {
  if (!codigosTexto.value.trim()) {
    return
  }

  const codigos = codigosTexto.value
    .split('\n')
    .map(c => c.trim())
    .filter(c => c.length > 0)

  if (codigos.length === 0) {
    return
  }

  loading.value = true
  try {
    const response = await axios.post('/api/validar-equipos', { codigos })
    codigosNoEncontrados.value = response.data.no_encontrados
    codigosFiltrados.value = codigos
  } catch (error) {
    console.error('Error al validar códigos:', error)
  } finally {
    loading.value = false
  }
}

const limpiarFiltros = () => {
  codigosTexto.value = ''
  codigosNoEncontrados.value = []
  codigosFiltrados.value = []
  filters.value = {
    codigo: { value: null, matchMode: FilterMatchMode.CONTAINS },
    cliente: { value: null, matchMode: FilterMatchMode.CONTAINS },
    tipo: { value: null, matchMode: FilterMatchMode.CONTAINS },
    estado: { value: null, matchMode: FilterMatchMode.CONTAINS }
  }
}

const formatearFecha = (fecha) => {
  if (!fecha) return ''
  const date = new Date(fecha)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  })
}

onMounted(() => {
  cargarEquipos()
})
</script>

