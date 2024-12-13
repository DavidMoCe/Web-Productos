# **Refurbished Products Store** 🛒✨: Online Shopping Platform

## 🌍 Choose Your Language / Elige tu idioma:
- [English](#english-)
- [Español](#español-)

---

## English 🇬🇧

This project is an online store for selling **refurbished products**. Developed with **Laravel 11.x**, the platform allows users to explore and purchase high-quality refurbished items, retrieving product information from both a **private database** and the **BackMarket API**, with **PayPal** integrated for secure 💳 payments.

## **Key Features** 🚀

- **Laravel 11.x Framework**: Built with the powerful PHP framework **Laravel 11.x**, offering a solid architecture and optimized performance.
- **BackMarket API Integration** 🌐: Connected to the **BackMarket API**, the store displays a catalog of guaranteed refurbished products. Products are synchronized and combined with items stored in the private database.
- **Private Database**🗂️: Products are also managed through a **private database**, which stores additional product details and history locally on the server.
- **Secure PayPal Payments** 🔒💸: Users can make payments securely and efficiently through the integration with **PayPal**, one of the world’s most trusted payment systems.
- **Combined Product Catalog** 🛍️: The platform showcases products from both the private database and the BackMarket API, allowing users to browse a wide selection of refurbished items filtered by category, brand, and price.
- **Product Guarantee** ✅: All refurbished products (whether from the private database or BackMarket API) are verified to ensure quality and functionality and come with a guarantee for customer confidence.

## **Technologies Used** 🛠️

- **Laravel 11.x**: PHP framework for robust and secure web development.
- **BackMarket API**: Access to verified, up-to-date refurbished products.
- **PayPal API**: Integration for secure and easy payments.
- **Private Database**: Hosted on **XAMPP**, managing product and user data alongside the BackMarket API.

## **Installation and Setup** ⚙️

1. Clone the repository:
   ```bash
   git clone https://github.com/DavidMoCe/Web-Productos.git
   ```

2. Navigate to the project directory:
   ```bash
   cd Web-Productos
   ```

3. Install project dependencies:
   ```bash
   composer install
   ```

4. Create the `.env` file from the `.env.example` file:
  ```bash
   cp .env.example .env
   ``` 

5. Open the `.env` file and configure the necessary credentials for:
    - BackMarket API 🌐
    - PayPal 💳
    - Private database (XAMPP) 🗂️
    
6. Run database migrations to set up the necessary tables:
   ```bash
   php artisan migrate
   ``` 

7. Start Laravel's local server:
   ```bash
   php artisan serve
   ```

8. Access the application in your browser at `http://localhost:8000`. 🌟

### **License** 📜
This project is licensed under the **CC BY-NC 4.0** license. See the `LICENSE` file for details.

### **Credits** 👨‍💻
Developed by **David Moreno Cerezo**.

---

## Español 🇪🇸

Este proyecto es una tienda en línea para la venta de **productos reacondicionados**. Desarrollada con **Laravel 11.x**, la plataforma permite a los usuarios explorar y comprar productos reacondicionados de alta calidad, obteniendo información de productos tanto de una **base de datos privada** como de la **API de BackMarket**, y utilizando **PayPal** como método de pago 💳 seguro.

### **Características principales:** 🚀

- **Framework Laravel 11.x**: La aplicación está construida con el potente framework PHP **Laravel 11.x**, que proporciona una arquitectura sólida y un rendimiento optimizado.

- **Integración con la API de BackMarket** 🌐: Conectada a la **API de BackMarket**, la tienda obtiene un catálogo de productos reacondicionados con garantía de calidad y funcionalidad. Los productos se sincronizan y se muestran junto con los almacenados en la base de datos privada.

- **Base de Datos Privada** 🗂️: Los productos también se gestionan a través de una **base de datos privada**, que contiene información adicional sobre productos y el historial de los mismos, almacenados localmente en el servidor.

- **Pagos seguros con PayPal** 🔒💸: Los usuarios pueden realizar pagos de manera segura y eficiente a través de la integración con **PayPal**, uno de los sistemas de pago más seguros del mundo.

- **Catálogo de productos combinado** 🛍️: La plataforma muestra productos tanto de la base de datos privada como de la API de BackMarket, permitiendo a los usuarios navegar por un catálogo de productos reacondicionados filtrados por categoría, marca y precio.

- **Garantía de productos** ✅: Todos los productos reacondicionados (ya sea desde la base de datos o la API de BackMarket) son verificados para asegurar su calidad y funcionalidad, y cuentan con una garantía para brindar confianza al comprador.

### **Tecnologías utilizadas:** 🛠️

- **Laravel 11.x**: Framework PHP para desarrollo web robusto y seguro.
- **API de BackMarket**: Conexión a la API para acceder a productos reacondicionados verificados y actualizados.
- **PayPal API**: Integración con PayPal para pagos seguros y fáciles.
- **Base de Datos Privada**: Utiliza una base de datos alojada en **XAMPP** para gestionar productos y datos de usuarios, además de la API de BackMarket.
  
### **Instalación y configuración:** ⚙️

1. Clona el repositorio:
   ```bash
   git clone https://github.com/DavidMoCe/Web-Productos.git
   ```

2. Navega al directorio del proyecto:
   ```bash
    cd Web-Productos
   ```

3. Instala las dependencias del proyecto:
   ```bash
    composer install
   ```

4. Crea el archivo `.env` a partir del archivo `.env.example`:
   ```bash
    cp .env.example .env
   ```

5. Abre el archivo `.env` y configura las credenciales necesarias para:
   - API de BackMarket 🌐
   - PayPal 💳
   - Base de datos privada (XAMPP) 🗂️

6. Ejecuta las migraciones de la base de datos para configurar las tablas necesarias:
   ```bash
    php artisan migrate
   ```

7. Inicia el servidor local de Laravel:
   ```bash
    php artisan serve
   ```

8. Accede a la aplicación en tu navegador en http://localhost:8000. 🌟

### **Licencia** 📜
Este proyecto está licenciado bajo la licencia **CC BY-NC 4.0**. Consulta el archivo `LICENSE` para más detalles.

### **Créditos** 👨‍💻
Desarrollado por **David Moreno Cerezo**.
