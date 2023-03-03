# API Registro Jornadas

  

## Contenido (Esquema Archivos creados)

 
|  					| Migration (DB) 			| Model 			| Relaciones 				|Controller 		    |Resource	|EndPoint               |
|-------------------|---------------------------|-------------------|---------------------------|-----------------------|-----------|-----------------------|
| Centros           |Centros_table              |Centro             |                           |Centro (CRUD)          |           |centros/               |
| Departamentos     |Departamentos_table        |Departamento       |                           |Departamento (CRUD)    |           |departamentos/         |
| Centros           |Centros_table              |Centro             |                           |Centro (CRUD)          |           |centros/               |
|                   |                           |*Pivot*            |Centro Depart (n:m)        |                       |           |                       |
| Usuarios          |Usuarios_table             |Usuario : Auth     |CentroDepartamento         |Usuario (CRUD+Auth)    |           |auth/ usuarios/        |
| Roles             |Roles_table                |Role               |                           |Role (CRUD)            |           |roles/                 |
|                   |                           |*Pivot*            |Usuarios Roles (n:m)       |                       |           |                       |
| Motivos           |Motivos_table              |Motivo             |                           |Motivo (C)             |           |                       |
| Pausas            |Pausas_table               |Pausa              |Motivo (1:n) Reg (1:n)     |Pausas (CRUD)          |           |calendario/break /back |
| Extras            |Extras_table               |Extra              |Motivo (1:n) Reg (1:n)     |Extras (CRUD)          |           |calendario/extra /ende |
| Tipo Jornadas     |Tipo_jornadas_table        |TipoJornadas       |                           |TipoJornadas (CR)      |           |                       |
| Jornadas          |Jornadas_table             |Jornadas           |Tipo_jornada (1:n)         |Jornadas (CRUD)        |           |jornada                |
| Cal_Centros       |Calendario_centros_table   |CalendarioCentro   |Centro (1:n) Jornada (1:n) |CalCentro (CR)         |           |calendario             |
| Cal_Usuarios      |Calendario_usuarios_table  |CalendarioUsuario  |Usuario (1:n) Jornada (1:n)|CalUsuario ()          |           |                       | 
| Exportar          |                           |                   |                           |Excel                  |           |archivo                |


## Proceso - TODO list

 - [X] Auth (Login - Registro)
 - [X] Migraciones Base de Datos
 - [ ] Seeder (Datos de prueba - autogeneracion)
    - [X] Datos Desarrollo - Test
    - [ ] Datos Iniciales Produccion
 - [X] Modelos y Relaciones (M)
    - [X] Centros y Departamentos
    - [X] Usuarios
    - [X] Roles
    - [X] Registros
    - [X] Motivos
    - [X] Pausas y Extras
    - [X] Jornadas y tipos
    - [X] Calendarios
 - [ ] Funcionalidad (C)
    - [X] Centros y Departamentos
    - [X] Usuarios
    - [X] Roles
    - [X] Registros
    - [X] Motivos
    - [X] Pausas y Extras
    - [X] Jornadas y tipos
    - [X] Calendarios
 - [ ] Exposicion y encapsulacion (R) 
    - [ ] Centros y Departamentos
    - [ ] Usuarios
    - [ ] Roles
    - [X] Registros
    - [ ] Motivos
    - [ ] Pausas y Extras
    - [ ] Jornadas y tipos
    - [ ] Calendarios
 - [ ] Ataque (API) 
    - [X] Centros y Departamentos
    - [X] Usuarios
    - [X] Roles
    - [X] Registros
    - [X] Motivos
    - [X] Pausas y Extras
    - [X] Jornadas y tipos
    - [X] Calendarios
 - [X] Exportacion Excel
 - [ ] Autorizacion y Permisos
    - [X] Auth
    - [ ] Admin
    - [ ] Sistemas
    - [ ] Personal
    - [ ] Jefe Departamento
 - [X] Configurar CORS (Probar en produccion)
