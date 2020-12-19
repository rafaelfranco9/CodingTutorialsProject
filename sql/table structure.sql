
CREATE TABLE usuario (
id INT PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(255),
apellido VARCHAR(255),
correo VARCHAR(255),
contrasena VARCHAR(255)
)

CREATE TABLE tutorial (
id INT PRIMARY KEY AUTO_INCREMENT,
titulo VARCHAR(255),
descripcion TEXT,
etiquetas VARCHAR(255),
codigo TEXT,
texto TEXT,
url VARCHAR(255),
imagen VARCHAR(255),
video VARCHAR(255), 
estado VARCHAR(255)
)

CREATE TABLE tutorial_usuario (
id INT PRIMARY KEY AUTO_INCREMENT,
id_usuario INT, 
id_tutorial INT,
FOREIGN KEY (id_usuario) REFERENCES usuario(id),
FOREIGN KEY (id_tutorial) REFERENCES tutorial(id)
)


