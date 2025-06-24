# Despliegue de la Página Web

Es una página web que se debe desplegar en el servidor web y configurarlo para que lea `home.html` y no `index.html`.

## Características

- La página contiene un formulario que se envía al correo del propietario del dominio.
- Para que funcione correctamente, es necesaria la instalación de las librerías **phpmailer** y **vendor** de PHP.
- En el archivo `.env` debes indicar los correspondientes parámetros de configuración.
- En `contact.htl` debes sustituir la **keyhtml** de reCAPTCHA por la pareja de la **key secreta** obtenida en Google y añadida en `.env`.

## Requisitos

- Instalar las librerías `phpmailer` y `vendor` de PHP.
- Configurar el archivo `.env` con tus datos.
- Configurar reCAPTCHA en `contact.htl` y en `.env`.
