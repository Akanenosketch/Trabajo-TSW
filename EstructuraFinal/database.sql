-- Para la entrega hacer volcado de DB


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
CREATE TABLE proyects (
	proyect_id INT AUTO_INCREMENT NOT NULL,
	proyect_name VARCHAR(255) NOT NULL,
    
	PRIMARY KEY (proyect_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para las tareas
CREATE TABLE tasks (
	task_id INT AUTO_INCREMENT NOT NULL,
	task_name VARCHAR(255) AUTO_INCREMENT,
	proyect_id INT NOT NULL,
    task_status ENUM("ToDo","Working","Done") NOT NULL,
    
	FOREIGN KEY (proyect_id) REFERENCES proyects(proyect_id),
    CONSTRAINT PK_Task PRIMARY KEY (task_id, proyect_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para la asignacion de usuarios a proyectos
CREATE TABLE users_on_proyects (
	user_mail VARCHAR(255) NOT NULL,
	proyect_id INT NOT NULL,

	FOREIGN KEY (user_mail) REFERENCES users(user_mail),
	FOREIGN KEY (proyect_id) REFERENCES proyects(proyect_id),
    CONSTRAINT PK_Users_Proyects PRIMARY KEY (user_mail, proyect_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Crear una tabla para la asignacion de usuarios a tareas
CREATE TABLE users_on_tasks (
	user_mail VARCHAR(255) NOT NULL,
	proyect_id INT NOT NULL,
	task_id INT NOT NULL,
	
	FOREIGN KEY (user_mail) REFERENCES users_on_proyects(user_mail, proyect_id),
	FOREIGN KEY (task_id) REFERENCES tasks(task_id),
    CONSTRAINT PK_Users_Tasks PRIMARY KEY (user_mail, proyect_id, task_id)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;


-- Introducir datos de prueba en la base de datos
INSERT INTO users (user_mail,username, passwd) VALUES ('tsw@uvigo.es', 'tswuser','tswpass');

INSERT INTO proyects (proyect_name) VALUES ('TSWTestProyect');
INSERT INTO users_on_proyects (user_mail,proyect_id) VALUES ('tsw@uvigo.es',1);

INSERT INTO tasks (task_name,proyect_id,task_status) VALUES ('TSWTestToDO',1,"ToDo");
INSERT INTO tasks (task_name,proyect_id,task_status) VALUES ('TSWTestWork',1,"Working");
INSERT INTO tasks (task_name,proyect_id,task_status) VALUES ('TSWTestDone',1,"Done");

INSERT INTO users_on_tasks (user_mail,proyect_id,task_id) VALUES ('tsw@uvigo.es',1,1);
INSERT INTO users_on_tasks (user_mail,proyect_id,task_id) VALUES ('tsw@uvigo.es',1,2);
INSERT INTO users_on_tasks (user_mail,proyect_id,task_id) VALUES ('tsw@uvigo.es',1,3);
