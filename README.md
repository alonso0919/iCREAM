iCREAM - Tienda de Helados Online

Sistema de e-commerce completo para una tienda de helados, desarrollado con PHP, HTML, CSS y MySQL. Incluye carrito de compras, pagos en linea, facturacion electronica y panel de administracion.

CARACTERISTICAS PRINCIPALES

Autenticacion de usuarios: registro, login y manejo de sesiones seguras con cookies HttpOnly
Catalogo de productos: galeria con productos destacados, categorias y detalle individual
Carrito de compras: persistente en base de datos, compatible con usuarios guest y registrados
Pagos en linea: integracion con Conekta, checkout y webhooks para actualizacion automatica de transacciones
Facturacion electronica: generacion de CFDI mediante la API de Facturama con catalogos SAT integrados
Panel de administracion: gestion de productos y visualizacion de ventas realizadas
Automatizacion de correos: envio de confirmacion de pago al cliente tras cada compra exitosa
Flujo completo de pago exitoso y pago fallido

TECNOLOGIAS UTILIZADAS

PHP: backend y logica de negocio
MySQL: base de datos relacional
HTML y CSS: frontend y estilos
JavaScript: interactividad del carrito y UI
Bootstrap: diseno responsivo
Conekta API: procesamiento de pagos
Facturama API: facturacion electronica CFDI
PHPMailer: automatizacion de correos

SEGURIDAD IMPLEMENTADA

Sesiones con cookies HttpOnly y SameSite Lax
Proteccion de rutas con guard.php para acceso restringido al panel admin
Consultas con prepared statements para prevenir SQL Injection
Archivo de ejemplo conexion.hosting.example.php para no exponer credenciales reales

APRENDIZAJES CLAVE

Integracion de pasarelas de pago reales con manejo de webhooks
Generacion de facturas electronicas con catalogos SAT Mexico
Arquitectura separando logica, configuracion y vistas
Manejo de sesiones y autenticacion sin frameworks

AUTOR
Gabriel Alonso
github.com/alonso0919