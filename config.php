<?php
// config.php - Configuración de base de datos
$host = 'localhost';
$dbname = 'u921254142_spc_gestion';
$username = 'u921254142_revestika';
$password = 'Revestika79145';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
?>

<?php
/*
INSTRUCCIONES PARA CONFIGURAR:

1. Ve al panel de control de tu hosting (cPanel, Plesk, etc.)
2. Busca "Bases de datos MySQL" o "MySQL Databases"
3. Crea una nueva base de datos llamada "spc_gestion"
4. Crea un usuario para esa base de datos
5. Asigna todos los permisos al usuario sobre la base de datos
6. Anota los siguientes datos:
   - Nombre del servidor (host): generalmente "localhost"
   - Nombre de la base de datos: "spc_gestion" (o el que hayas puesto)
   - Usuario: el que creaste
   - Contraseña: la que asignaste

7. Cambia las variables arriba con tus datos reales
8. Sube todos los archivos a tu hosting
9. Ve a phpMyAdmin (en tu panel de hosting)
10. Selecciona tu base de datos "spc_gestion"
11. Ve a la pestaña "SQL"
12. Copia y pega todo el contenido del archivo "database.sql"
13. Ejecuta el script SQL
14. ¡Listo! Tu sistema estará funcionando

ARCHIVOS NECESARIOS EN TU HOSTING:
- index.php (el archivo principal con todo el sistema)
- config.php (este archivo con la configuración)
- api.php (el archivo de la API)
- logout.php (para cerrar sesión)

ESTRUCTURA DE CARPETAS RECOMENDADA:
/public_html/spc/
  ├── index.php
  ├── config.php
  ├── api.php
  ├── logout.php
  └── database.sql (solo para referencia, no necesario subirlo)

PERMISOS DE ARCHIVOS:
- Todos los archivos .php: 644
- Carpetas: 755

SEGURIDAD:
- Cambia la contraseña "79145" por una más segura si lo deseas
- No compartas los datos de tu base de datos
- Mantén actualizado tu hosting

*/
?>