# Proyecto de Gestión de Tareas

## Descripción

Esta es una aplicación web de gestión de incidencias y tareas (CRUD) desarrollada con el framework Laravel y PHP puro. Permite la administración de usuarios, clientes, empleados y tareas de manera eficiente. El proyecto está diseñado para la asignatura de **Desarrollo Web en Entorno Servidor**.

La aplicación incluye funcionalidades como:
- Sistema de autenticación y login.
- Gestión de clientes (crear, leer, actualizar, eliminar).
- Gestión de empleados.
- Gestión de tareas/incidencias con paginación y filtrado.
- Interfaz web responsiva.

## Tecnologías Utilizadas
- **Framework**: Laravel
- **Lenguaje**: PHP
- **Base de Datos**: MySQL (configurada en `config/bd.php`)
- **Frontend**: HTML, CSS, JavaScript (con Vite para assets)
- **ORM**: Eloquent (modelos personalizados como `ClientModel`, `TaskModel`, `UserModel`)
- **Sesiones**: Gestión personalizada con `SessionManager`

## Requisitos
- PHP >= 8.0
- Composer
- Node.js y npm (para Vite)
- MySQL o MariaDB
- Servidor web (Apache/Nginx) o XAMPP

## Instalación

1. **Clona el repositorio**:
   ```bash
   git clone https://github.com/tu-usuario/proyecto-tareas.git
   cd proyecto-tareas/proyecto
   ```

2. **Instala las dependencias de PHP**:
   ```bash
   composer install
   ```

3. **Instala las dependencias de Node.js**:
   ```bash
   npm install
   ```

4. **Configura el entorno**:
   - Copia el archivo `.env.example` a `.env`:
     ```bash
     cp .env.example .env
     ```
   - Edita `.env` con tus configuraciones de base de datos (host, usuario, contraseña, nombre de BD).

5. **Genera la clave de aplicación**:
   ```bash
   php artisan key:generate
   ```

6. **Ejecuta las migraciones**:
   ```bash
   php artisan migrate
   ```

7. **Compila los assets**:
   ```bash
   npm run build
   ```

8. **Inicia el servidor**:
   ```bash
   php artisan serve
   ```
   O si usas XAMPP, configura el virtual host apuntando a `public/`.

## Uso

- Accede a la aplicación en `http://localhost:8000` (o tu configuración).
- Regístrate o inicia sesión.
- Navega por las secciones: Clientes, Empleados, Tareas.
- Realiza operaciones CRUD en cada módulo.

## Estructura del Proyecto
- `app/Models/`: Modelos personalizados (ClientModel, TaskModel, UserModel, SessionManager).
- `resources/views/`: Vistas Blade (auth/, clients/, tasks/, etc.).
- `routes/web.php`: Definición de rutas.
- `database/migrations/`: Migraciones de BD.
- `public/`: Assets públicos.

## Contribución
Este proyecto es para fines educativos. Si deseas contribuir:
1. Haz un fork del repositorio.
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`).
3. Commit tus cambios (`git commit -am 'Añade nueva funcionalidad'`).
4. Push a la rama (`git push origin feature/nueva-funcionalidad`).
5. Abre un Pull Request.

## Autor
- **Nombre**: dvazper
- **Email**: dvazper0204@g.educaand.es
- **Asignatura**: Desarrollo Web en Entorno Servidor

## Licencia
Este proyecto es de uso educativo y no tiene licencia específica.

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
