Sistema de Denuncias de la OAC (Oficina de Atención al Ciudadano)
=================================================================

Es un sistema para el registro, seguimiento y control de las denuncias registradas en la oficina
de Atención al Ciudadano,  en el caso particular de la Contraloría del estado Barinas, sin embargo,
este sistema puede ajstarse a la mayoría de las OAC's de las diferentes instituciones del sector
público nacional

ESTRUCTURA DE LOS DIRECTORIOS
-------------------

      bootstrap/      contiene el framework css
      docs/           contiene los lineamientos y formatos sobre los qe se basa el sistema
      imagenes/       contiene las imagenes que se usan en la aplicación
      jquery/         contiene la librería jquery
      jquery-ui-1.11.3.custom/               contiene el componente datepicker
      proceso/        contiene lo necesario para la manipulación de atenciones, comunidades y demás procesos
      spoon/          contiene las clases necesarias para el manejo de los usuarios del sistema, conexión a la base de datos, filtros entre otras
      tcpdf/          clase para el manejo y generación de archvios PDF
      usuarios/       contiene la lógica para el login logout de los usuarios



REQUERIMIENTOS
------------

> PHP 5.3.*
> MySQL 5.5.*
> Apache 2.2.*


INSTALACION
------------

La estrutura de los directorios debe mantenerse tal como se especifica arriba.

La base de datos denunciasdb.sql se encuentra en la carpeta raíz.


Puede descargarse a través de https://github.com/szLuis/oac.git

CONFIGURACION
-------------

### Base de datos

Editar el archivo spoon/database/dbconexion.php y cambiar los datos necesarios
para conectar con el servidor de base de datos.

