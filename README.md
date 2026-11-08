# CRUD en php

## Manual de instalación y uso: gestión de clientes (PHP + PDO + MariaDB)

### 1. Descripción

   Aplicación web para gestionar clientes. Permite listar, buscar por DNI o nombre, ordenar de forma ascendente o descendente, añadir, editar y borrar clientes. 
   Usa PHP con PDO y una base de datos MariaDB.


### 2. Requisitos

- Windows con un servidor local (por ejemplo, XAMPP) que incluya Apache, PHP 8 o superior y MariaDB.

- Extensión pdo_mysql activada (viene activada por defecto en XAMPP).

- Un navegador web actual.

- Conexión a Internet, ya que la interfaz carga Bootstrap desde un CDN.

### 3. Instalación

   #### 3.1. Copiar los archivos

Copia la carpeta del proyecto en el directorio público de Apache. 
En XAMPP es C:\xampp\htdocs\P3d_PDO\. 

Debe contener:

- conectar_db.php

- funcionesValidacion.php

- index.php

- clientenuevo.php

- editarcliente.php

- borrarcliente.php

- script.sql

#### 3.2. Arrancar los servicios

Abre el panel de XAMPP y pulsa Start en Apache y en MySQL/MariaDB. 
Si MariaDB no arranca, o aparece el error SQLSTATE[HY000] [2002], comprueba que el puerto no esté ocupado (por defecto, 3306).

#### 3.3. Crear la base de datos

Abre http://localhost/phpmyadmin, entra en la pestaña SQL y ejecuta el script copiando el archivo sql adjunto, o directamente ejecútalo desde la pestaña de importar

#### 3.4. Configurar la conexión

Abre conectar_db.php y comprueba estos valores:

| Constante  | Valor por defecto                                |
| ---------- | ------------------------------------------------ |
| HOSTNAME   | localhost                                        |
| DATABASE   | clientes (debe coincidir con el nombre de la BD) |
| USER_DB    | root                                             |
| Contraseña | vacía en XAMPP (ajústala si has puesto una)      |

Si MariaDB usa otro puerto, añade port=3306 (o el que sea) al DSN. Si falla localhost, prueba con 127.0.0.1.

#### 3.5. Ejecutar

Abre en el navegador: http://localhost/P3d_PDO/index.php

### 4. Manual de uso

- Listado. index.php muestra todos los clientes en una tabla con DNI, nombre, dirección, localidad, provincia, teléfono y los enlaces Editar y Borrar. Si no hay datos, aparece «No hay clientes registrados».

- Buscar. Escribe parte del DNI o del nombre en el campo de búsqueda y pulsa Buscar. Se muestran solo los clientes que contengan ese texto. Con el campo vacío se muestran todos.

- Ordenar. Pulsa A → Z o Z → A para ordenar por nombre de forma ascendente o descendente.

- Añadir Cliente. Pulsa para abrir un formulario para añadir un nuevo cliente. Pedirá tres campos obligatorios. Pulsa Guardar Cliente para confirmar, o Cancelar para volver a la página principal.. Si hay errores de validación, se muestran en pantalla. Si todo es correcto, se vuelve al listado y aparece un mensaje de confirmación.

- Editar. Pulsa Editar en la fila del cliente. En el formulario puedes modificar todos los campos menos el DNI, que identifica al cliente. Pulsa Actualizar Datos para confirmar, o Cancelar para volver a la página principal. Si hay errores de validación, se muestran en pantalla. Si todo es correcto, se vuelve al listado y aparece un mensaje de confirmación.

- Borrar. Pulsa Borrar en la fila del cliente, aparecerá una pantalla de confirmación. Se elimina el registro y se vuelve al listado con un mensaje de confirmación.

### 5. Validaciones

- DNI: formato de 8 números y una letra, con la letra comprobada.

- Nombre: obligatorio.

- Email: formato válido y obligatorio.

### 6. Seguridad aplicada

- Consultas preparadas con PDO, sin concatenar datos del usuario en el SQL.

- Los datos se escapan con htmlspecialchars al recibirlos y al mostrarlos.

- Los errores técnicos se guardan con error_log y no se muestran al usuario.

### 7. Problemas frecuentes

| Síntoma                      | Solución                                                    |
| ---------------------------- | ----------------------------------------------------------- |
| SQLSTATE[HY000] [2002]       | Arranca MariaDB y revisa el puerto y el host.               |
| Unknown database             | Crea la BD y comprueba que DATABASE coincide con su nombre. |
| Access denied                | Revisa usuario y contraseña en conectar_db.php.             |
| Caracteres raros (ñ, tildes) | Verifica charset=utf8mb4 en el DSN y UTF-8 en los archivos. |
     
