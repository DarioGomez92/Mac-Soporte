# 💻 Mac Soporte – Tienda Online y Servicio Técnico Informático

Mac Soporte es una aplicación web desarrollada como proyecto de fin de ciclo para el Grado Superior de Desarrollo de Aplicaciones Web. Está orientada a la venta de productos informáticos y la gestión de solicitudes de reparación por parte de los clientes.

---

## 🚀 Funcionalidades principales

- 🛒 Navegación por tienda de productos con categorías y carrito de compras
- 🔐 Registro, inicio de sesión y recuperación de contraseña
- 💳 Pasarela de pago real integrada con Stripe
- 🛠️ Formulario para solicitar reparaciones de dispositivos
- 📬 Formulario de contacto con envío de correo mediante PHPMailer
- 👨‍💼 Área de administración con vista de usuarios, compras y mensajes
- 📱 Diseño responsive adaptado a móviles y tablets

---

## 🧰 Tecnologías utilizadas

- HTML5, CSS3, JavaScript  
- PHP
- JSON para almacenamiento de productos  
- Stripe PHP para pagos online  
- PHPMailer para envío de formularios  
- MySQL para persistencia de datos  
- VS Code como entorno de desarrollo  
- Draw.io para diagramas  

---

## 📁 Estructura del proyecto

macsoporte/
├── index.html # Página principal
├── tienda.html # Catálogo de productos
├── carrito.html # Carrito de compras
├── contacto.html # Formulario de contacto
├── reparaciones.html # Solicitud de reparaciones
├── admin.php # Área de administración
├── cliente.php # Área de clientes
├── restablecer.php # Recuperación de contraseña
├── css/
├── img/
├── font/
├── data/productos.json # Catálogo de productos

---

## 📦 Instalación y despliegue

1. Clona o descarga el proyecto.  
2. Sitúalo en un entorno compatible con PHP (ej. XAMPP o WAMP).  
3. Configura tu base de datos y credenciales en los archivos PHP necesarios (`conexion.php`, etc.).  
4. Asegúrate de tener configurado Stripe y PHPMailer correctamente.  
5. Abre `index.html` desde tu navegador.

---

## 🔐 Requisitos

- Servidor local con PHP 7.x o superior  
- Conexión a base de datos MySQL  
- Claves API de Stripe y configuración SMTP para PHPMailer  

---

## 📌 Créditos

Desarrollado por Darío Gómez Trujillo  
Proyecto final del Grado Superior de Desarrollo de Aplicaciones Web  
Curso 2024/2025