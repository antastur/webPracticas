Es una pagina web que se ha de desplegar en el servidor web y configurarlo para que lea home.html y no index.html.
La pagina contiene un formulario que se envia al correo del propietario del dominio.Para que funcione es necesaria la instalación de las librerías phpmailer y vendor de PHP,
así como en el archivo .env indicar los correspondientes parámetros y en contact.htl sustituir la keyhtml de recaptcha por la pareja de la key secreta obtenida en google y añadida en .env.
