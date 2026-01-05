-- Eliminado de la base de datos
DROP DATABASE IF EXISTS tswdb;

-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS tswdb;

-- Crear un usuario para poder manipular la base de datos
DROP USER IF EXISTS 'tswuser'@'localhost';
CREATE USER 'tswuser'@'localhost' IDENTIFIED BY 'tswpass';
GRANT ALL PRIVILEGES ON tswdb.* TO 'tswuser'@'localhost' WITH GRANT OPTION;

-- Seleccionar la base de datos
USE tswdb;

-- Crear una tabla para los usuarios
CREATE TABLE users (
    user_mail VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (user_mail)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para los proyectos
CREATE TABLE projects (
    project_id INT AUTO_INCREMENT NOT NULL,
    project_name VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (project_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para las tareas
CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT NOT NULL,
    task_name VARCHAR(255) NOT NULL,
    task_desc VARCHAR(255) NOT NULL,
    project_id INT NOT NULL,
    task_status ENUM("ToDo","Working","Done") NOT NULL,
    task_priority ENUM("Low","Medium","High") NOT NULL,
    begin_date VARCHAR(10) NOT NULL,
    end_date VARCHAR(10) NOT NULL,

    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    CONSTRAINT PK_Task PRIMARY KEY (task_id, project_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para la asignacion de usuarios a proyectos
CREATE TABLE users_on_projects (
    user_mail VARCHAR(255) NOT NULL,
    project_id INT NOT NULL,

    FOREIGN KEY (user_mail) REFERENCES users(user_mail) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    CONSTRAINT PK_Users_projects PRIMARY KEY (user_mail, project_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para la asignacion de usuarios a tareas
CREATE TABLE users_on_tasks (
    user_mail VARCHAR(255) NOT NULL,
    project_id INT NOT NULL,
    task_id INT NOT NULL,
    
    FOREIGN KEY (user_mail,project_id) REFERENCES users_on_projects(user_mail, project_id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(task_id) ON DELETE CASCADE,
    CONSTRAINT PK_Users_Tasks PRIMARY KEY (user_mail, project_id, task_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;


-- Introducir datos de prueba en la base de datos
INSERT INTO users (user_mail,username, passwd) VALUES ('tsw@uvigo.es', 'tswuser','tswpass');
INSERT INTO users (user_mail,username, passwd) VALUES ('tsw2@uvigo.es', 'tswuser2','tswpass2');

INSERT INTO projects (project_name) VALUES ('TSWTestproject');
INSERT INTO projects (project_name) VALUES ('TSWTestproject2');
INSERT INTO users_on_projects (user_mail,project_id) VALUES ('tsw@uvigo.es',1);
INSERT INTO users_on_projects (user_mail,project_id) VALUES ('tsw@uvigo.es',2);
INSERT INTO users_on_projects (user_mail,project_id) VALUES ('tsw2@uvigo.es',1);

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWTestToDO', 1, "ToDo", "desc", "High", '2024-01-01', '2024-01-10');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWTestWork', 1, "Working", "des2", "Low", '2024-01-05', '2024-01-15');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWTestDone', 1, "Done", "desc3", "Medium", '2024-01-10', '2024-01-20');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWPrioAlta', 1, "ToDo", "desc", "High", '2024-01-01', '2024-01-10');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWPrioBaja', 1, "ToDo", "des2", "Low", '2024-01-05', '2024-01-15');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWPrioMedia', 1, "ToDo", "desc3", "Medium", '2024-01-10', '2024-01-20');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWTestToDO', 2, "ToDo", "desc4", "Medium", '2024-02-01', '2024-02-10');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWTestWork', 2, "Working", "desc5", "High", '2024-02-05', '2024-02-15');

INSERT INTO tasks (task_name, project_id, task_status, task_desc, task_priority, begin_date, end_date) 
VALUES ('TSWTestDone', 2, "Done", "desc6", "Low", '2024-02-10', '2024-02-20');

INSERT INTO users_on_tasks (user_mail,project_id,task_id) VALUES ('tsw@uvigo.es',1,1);
INSERT INTO users_on_tasks (user_mail,project_id,task_id) VALUES ('tsw@uvigo.es',1,2);
INSERT INTO users_on_tasks (user_mail,project_id,task_id) VALUES ('tsw@uvigo.es',1,3);