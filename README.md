# LeaseIn - Sistema de Gestión de Equipos

Sistema completo con backend Laravel + PostgreSQL y frontend Vue 3 + Tailwind + PrimeVue.

## 📁 Estructura del Proyecto

```
prueba-tecnica-leasein/
├── backend/              # Laravel API
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   └── ...
├── frontend/             # Vue 3 Frontend
│   ├── src/
│   ├── public/
│   └── ...
├── docker-compose.yml    # Orquestación de contenedores
└── inicio.bat           # Script de inicio automático
```

## 🚀 Inicio Rápido

### Instalación y Ejecución

```bash
.\inicio.bat
```

Este script automáticamente:
1. Levanta todos los contenedores (backend, frontend, base de datos)
2. Instala dependencias
3. Configura la base de datos
4. Inserta datos de ejemplo

## 🌐 URLs

- **Frontend:** http://localhost
- **Backend API:** http://localhost:8000/api/equipos
- **PostgreSQL:** localhost:5432

## 📋 Características

### Backend (Laravel 10 + PostgreSQL)
- ✅ API REST con 2 endpoints
- ✅ GET `/api/equipos` - Lista todos los equipos
- ✅ POST `/api/validar-equipos` - Valida códigos de equipos
- ✅ Migración y seeder incluidos
- ✅ Dockerizado en puerto 8000

### Frontend (Vue 3 + Tailwind + PrimeVue)
- ✅ DataTable con filtros por columna
- ✅ Búsqueda por subcadena en todas las columnas
- ✅ Textarea para pegar múltiples códigos
- ✅ Validación de códigos con alertas visuales
- ✅ Diseño responsive y moderno
- ✅ Dockerizado con Nginx en puerto 80

## 📊 Estructura de la Base de Datos

### Tabla: equipos

| Campo          | Tipo      | Descripción                |
|----------------|-----------|----------------------------|
| id             | bigint    | Clave primaria (auto)      |
| codigo         | string    | Código único del equipo    |
| tipo           | string    | Tipo de equipo             |
| cliente        | string    | Nombre del cliente         |
| estado         | string    | Estado del equipo          |
| fecha_entrega  | date      | Fecha de entrega           |
| created_at     | timestamp | Fecha de creación          |
| updated_at     | timestamp | Fecha de actualización     |

## 🔧 Tecnologías Utilizadas

### Backend
- Laravel 10
- PostgreSQL 15
- PHP 8.2
- Docker

### Frontend
- Vue 3 (Composition API)
- Tailwind CSS
- PrimeVue
- Vite
- Axios
- Docker + Nginx

## 📝 Endpoints API

### GET /api/equipos
Obtiene todos los equipos registrados.

**Respuesta:**
```json
[
  {
    "id": 1,
    "codigo": "EQ001",
    "tipo": "Laptop",
    "cliente": "Cliente A",
    "estado": "Activo",
    "fecha_entrega": "2024-01-15",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
]
```

### POST /api/validar-equipos
Valida si los códigos de equipos existen.

**Request:**
```json
{
  "codigos": ["EQ001", "EQ002", "EQ999"]
}
```

**Respuesta:**
```json
{
  "encontrados": ["EQ001", "EQ002"],
  "no_encontrados": ["EQ999"]
}
```

## 🐛 Solución de Problemas

### Puerto 80 ocupado
Modifica el puerto en `docker-compose.yml`:
```yaml
frontend:
  ports:
    - "8080:80"  # Cambia 8080 por el puerto que prefieras
```

### Reiniciar desde cero
```bash
docker-compose down -v
.\inicio.bat
```

## 📄 Licencia

MIT
