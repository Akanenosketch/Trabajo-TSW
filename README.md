**TaskGroup** es una aplicación web que implementa un sistema de **gestión colaborativa de tareas en proyectos compartidos**.  
Su objetivo es facilitar que un grupo de personas que trabaja en conjunto (por ejemplo, en un proyecto académico) pueda **crear un espacio común donde planificar tareas, asignarlas entre los miembros y controlar su progreso** de forma clara y accesible.

Una aplicación conocida similar sería *Trello*, pero **TaskGroup** está diseñada para ofrecer una **solución más simple, ligera y centrada en la colaboración entre personas**, sin necesidad de una estructura empresarial ni funcionalidades avanzadas.


La aplicación permitirá **registrar usuarios**, y cada usuario podrá **crear un nuevo proyecto** (por ejemplo: *“Proyecto de TSW”*), al que podrá **invitar a otros usuarios**.  
Dentro de cada proyecto, cualquier miembro podrá **crear tareas**, **asignarlas**, **marcarlas como resueltas**, y consultar un **resumen general del progreso del equipo**.

---



### Públicas (no requieren login)
- (F1) **Registrarse:**  
  Indicar un alias (sin espacios), una contraseña y un email.

- (F2) **Autenticarse:**  
  Comprobar las credenciales. Una vez autenticado, se accede al listado de proyectos (ver F3).

---

### Privadas (requieren login)

- (F3) **Listar proyectos:**  
  Mostrar todos los proyectos donde el usuario está incluido.  
  Al hacer clic en un proyecto, se accede a **F5**.

- (F4) **Crear proyecto nuevo:**  
  Indicar un nombre de proyecto.

- (F5) **Ver proyecto:**  
  Panel con todas las tareas agrupadas en:
  - Pendientes  
  - Resueltas  

  Desde este panel se puede además:
  - (F6) **Añadir un usuario al proyecto**, indicando el email del usuario.  
  - (F7) **Crear tarea nueva**, indicando:
    - Usuario asignado (por defecto, el usuario autenticado)  
    - Nombre de la tarea  
    - Estado (resuelta o pendiente; por defecto: pendiente)  
      - El cambio de estado se hace en **F8**.  

- (F8) **Editar una tarea existente:**  
  Permite cambiar cualquier campo (nombre, estado o usuario asignado).

- (F9) **Eliminar una tarea.**

- (F10) **Ver resumen del proyecto:**  
  Muestra:
  - Número total de tareas  
  - Número de tareas pendientes  
  - Número de tareas resueltas  
  - Progreso global del proyecto (en porcentaje)

- (F11) **Eliminar proyecto.**