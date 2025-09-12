## About the Proyect

Perfecto 🚀 Te dejo un **README modelo para tu repo `PROYECTO_INVENTARIO`**. Solo tendrías que copiarlo en el archivo `README.md` de GitHub (puedes editar desde la web o en tu editor).

---

# 📦 Proyecto Inventario

## 📖 Descripción

Este proyecto es un **sistema de gestión de inventario** desarrollado en **Laravel**, como parte del componente de formación en desarrollo de software.
El módulo permite registrar, editar, eliminar y consultar productos en un inventario, asegurando control y organización de los datos.


## 🎯 Objetivos del módulo

* Permitir al administrador gestionar productos en el inventario (CRUD).
* Proveer una interfaz sencilla para el control de existencias.
* Aplicar los conceptos vistos en el componente “Desarrollo de frontend con React JS” (ajustado a Laravel con aprobación del instructor).
* Cumplir con las buenas prácticas de codificación y versionamiento.



## 🛠️ Tecnologías utilizadas

* **Laravel 10**
* **PHP 8.x**
* **Blade (plantillas frontend)**
* **Bootstrap 5**
* **MySQL**



## 📂 Instalación y ejecución

1. Clonar el repositorio:

   ```bash
   git clone https://github.com/OmarMendezR/PROYECTO_INVENTARIO.git
   cd PROYECTO_INVENTARIO
   ```

2. Instalar dependencias:

   ```bash
   composer install
   npm install && npm run dev
   ```

3. Configurar archivo `.env`:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configurar la base de datos en el archivo `.env`.

5. Ejecutar migraciones:

   ```bash
   php artisan migrate
   ```

6. Levantar el servidor:

   ```bash
   php artisan serve
   ```

7. Acceder en el navegador:

   ```
   http://127.0.0.1:8000
   ```

## Funcionalidades principales

* Registro de productos
* Consulta de inventario
* Edición de productos
* Eliminación de productos

##  Autor

* **Omar Méndez**
  Proyecto desarrollado en el marco de formación del SENA.
