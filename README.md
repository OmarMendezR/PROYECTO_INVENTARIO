# 📦 Sistema de Inventario – PHP (MVC)

Sistema de gestión de inventario desarrollado en **PHP puro**, siguiendo el patrón **MVC**.

Proyecto académico orientado a buenas prácticas de organización, seguridad y lógica de negocio.

---

## 🚀 Funcionalidades

- Autenticación de usuarios
- Roles: Administrador y Empleado
- CRUD de productos
- Control de stock
- Gestión de pedidos
- Generación de PDF con Dompdf

---

## 🧱 Arquitectura

El proyecto sigue el patrón MVC:

app/
- controllers
- models
- views

public/
- index.php (Front Controller)

---

## 🔐 Seguridad

- Contraseñas cifradas con `password_hash()`
- Uso de PDO y consultas preparadas
- Control de acceso por roles
- El usuario administrador no se crea desde el registro público

---

## ⚙️ Requisitos

- PHP >= 8.0
- MySQL / MariaDB
- Composer
- Servidor local (XAMPP o similar)

---

## 🛠️ Instalación

1. Clonar el repositorio
2. Ejecutar `composer install`
3. Configurar la base de datos en `config/conexion.php`
4. Acceder desde `http://localhost/Proyecto/public`

---

## 👤 Usuario administrador

El usuario administrador debe crearse manualmente en la base de datos por seguridad.

---

## ✍️ Autor

Omar Méndez  
Proyecto académico – PHP & MySQL
