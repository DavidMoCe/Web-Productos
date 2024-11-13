# **Tienda de Productos Reacondicionados**: Plataforma de Compras Online

Este proyecto es una tienda en línea para la venta de **productos reacondicionados**. Desarrollada con **Laravel 11.x**, la plataforma permite a los usuarios explorar y comprar productos reacondicionados de alta calidad, obteniendo información de productos tanto de una **base de datos privada** como de la **API de BackMarket**, y utilizando **PayPal** como método de pago seguro.

### **Características principales:**

- **Framework Laravel 11.x**: La aplicación está construida con el potente framework PHP **Laravel 11.x**, que proporciona una arquitectura sólida y un rendimiento optimizado.

- **Integración con la API de BackMarket**: Conectada a la **API de BackMarket**, la tienda obtiene un catálogo de productos reacondicionados con garantía de calidad y funcionalidad. Los productos se sincronizan y se muestran junto con los almacenados en la base de datos privada.

- **Base de Datos Privada**: Los productos también se gestionan a través de una **base de datos privada**, que contiene información adicional sobre productos y el historial de los mismos, almacenados localmente en el servidor.

- **Pagos seguros con PayPal**: Los usuarios pueden realizar pagos de manera segura y eficiente a través de la integración con **PayPal**, uno de los sistemas de pago más seguros del mundo.

- **Catálogo de productos combinado**: La plataforma muestra productos tanto de la base de datos privada como de la API de BackMarket, permitiendo a los usuarios navegar por un catálogo de productos reacondicionados filtrados por categoría, marca y precio.

- **Garantía de productos**: Todos los productos reacondicionados (ya sea desde la base de datos o la API de BackMarket) son verificados para asegurar su calidad y funcionalidad, y cuentan con una garantía para brindar confianza al comprador.

### **Tecnologías utilizadas:**

- **Laravel 11.x**: Framework PHP para desarrollo web robusto y seguro.
- **API de BackMarket**: Conexión a la API para acceder a productos reacondicionados verificados y actualizados.
- **PayPal API**: Integración con PayPal para pagos seguros y fáciles.
- **Base de Datos Privada**: Utiliza una base de datos alojada en **XAMPP** para gestionar productos y datos de usuarios, además de la API de BackMarket.
  
### **Instalación y configuración:**

1. Clona el repositorio:
   ```bash
   git clone https://github.com/DavidMoCe/Web-Productos.git

2. Navega al directorio del proyecto:
   ```bash
    cd tienda-reacondicionados

3. Instala las dependencias del proyecto:
   ```bash
    composer install

4. Crea el archivo `.env` a partir del archivo `.env.example`:
   ```bash
    cp .env.example .env

5. Abre el archivo `.env` y configura las credenciales necesarias para:
   - API de BackMarket
   - PayPal
   - Base de datos privada (XAMPP)

6. Ejecuta las migraciones de la base de datos para configurar las tablas necesarias:
   ```bash
    php artisan migrate

7. Inicia el servidor local de Laravel:
   ```bash
    php artisan serve

8. Accede a la aplicación en tu navegador en http://localhost:8000.

### **Licencia**
Este proyecto está licenciado bajo la licencia **CC BY-NC 4.0**. Consulta el archivo `LICENSE` para más detalles.

### **Créditos**
Desarrollado por **David Moreno Cerezo**.
