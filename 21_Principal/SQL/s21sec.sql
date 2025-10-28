
-- Base de datos: s21sec
-- DROP DATABASE IF EXISTS `s21sec`;

CREATE DATABASE s21sec;
USE s21sec;

-- Tamaño empresa
CREATE TABLE TamanoEmpresa (
  id_tamano INT NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (id_tamano)
);

-- Sector empresa
CREATE TABLE SectorEmpresa (
  id_sector INT NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (id_sector)
);

select * from asignaciones;
select * from clientes;
select * from Deseo;
select * from solicitudes;


-- Tabla Clientes

CREATE TABLE Clientes (	
  id_cliente INT NOT NULL AUTO_INCREMENT,
  empresa VARCHAR(100) NOT NULL,
  nombre VARCHAR(50) NOT NULL,
  apellidos VARCHAR(50) NOT NULL,
  pais VARCHAR(50) NOT NULL,
  ciudad VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  telefono VARCHAR(15) NOT NULL,
  cargo VARCHAR(100) DEFAULT NULL,
  id_tamano INT NOT NULL,
  id_sector INT NOT NULL,
  PRIMARY KEY (id_cliente),
  FOREIGN KEY (id_tamano) REFERENCES TamanoEmpresa(id_tamano) ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (id_sector) REFERENCES SectorEmpresa(id_sector) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Tabla de Deseos
CREATE TABLE Deseo (
  id_deseo INT NOT NULL AUTO_INCREMENT,
  nombre_deseo VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (id_deseo)
);

-- Tabla de Servicios
CREATE TABLE Servicios (
  id_servicio INT NOT NULL AUTO_INCREMENT,
  nombre_servicio VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (id_servicio)
);

-- Tabla de Departamentos
CREATE TABLE Departamentos (
  id_departamento INT NOT NULL AUTO_INCREMENT,
  nombre_departamento VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (id_departamento)
);

-- Tabla de Solicitudes
CREATE TABLE Solicitudes (
  id_solicitud INT NOT NULL AUTO_INCREMENT,
  id_cliente INT NOT NULL,
  id_deseo INT NOT NULL,
  id_servicio INT DEFAULT NULL,
  mensaje TEXT DEFAULT NULL,
  fecha_solicitud TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_solicitud),
  FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_deseo) REFERENCES Deseo(id_deseo) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_servicio) REFERENCES Servicios(id_servicio) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla de Asignaciones
CREATE TABLE Asignaciones (
  id_asignacion INT NOT NULL AUTO_INCREMENT,
  id_solicitud INT NOT NULL,
  id_departamento INT NOT NULL,
  estado ENUM('Pendiente','En proceso','Finalizado') DEFAULT 'Pendiente',
  fecha_asignacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_asignacion),
  FOREIGN KEY (id_solicitud) REFERENCES Solicitudes(id_solicitud) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_departamento) REFERENCES Departamentos(id_departamento) ON DELETE CASCADE ON UPDATE CASCADE
);

-- crear campo de mensaje de asignacion (nota de administrador)
alter table Asignaciones add mensaje_asignacion varchar(256);


select * from HistorialEstados;

-- Tabla de Usuarios
CREATE TABLE Usuarios (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  nombre_usuario VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('Administrador', 'Departamento') DEFAULT 'Departamento',
  id_departamento INT DEFAULT NULL,
  PRIMARY KEY (id_usuario),
  FOREIGN KEY (id_departamento) REFERENCES Departamentos(id_departamento) ON DELETE SET NULL ON UPDATE CASCADE
);

Select * from Clientes;	

-- Tabla de Historial de estados
CREATE TABLE HistorialEstados (
  id_historial INT NOT NULL AUTO_INCREMENT,
  id_asignacion INT NOT NULL,
  estado_anterior ENUM('Pendiente','En proceso','Finalizado') DEFAULT NULL,
  estado_nuevo ENUM('Pendiente','En proceso','Finalizado') NOT NULL,
  fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  cambiado_por VARCHAR(100) NOT NULL,
  PRIMARY KEY (id_historial),
  FOREIGN KEY (id_asignacion) REFERENCES Asignaciones(id_asignacion) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO TamanoEmpresa (descripcion)
VALUES
('10 Empleados'),
('10 - 50 Empleados'),
('51 - 250 Empleados'),
('251 - 1000 Empleados'),
('1000 Empleados');

INSERT INTO SectorEmpresa (descripcion)
VALUES
('Automoción & Transporte'),
('Consultoría'),
('Consumo & Venta al por menor'),
('Energía y servicios públicos'),
('Financiero - Seguros y Fintech'),
('Servicios financieros'),
('Salud -Farmacéutico & Biotech'),
('Industria y Manufactura'),
('Media & Comms'),
('Administración pública'),
('Tecnología & Telecomunicaciones & Media'),
('Otro sector');

INSERT INTO Clientes (empresa, nombre, apellidos, pais, ciudad, email, telefono, cargo, id_tamano, id_sector)
VALUES
('TechNova S.L.', 'Carlos', 'Pérez', 'España', 'Madrid', 'carlos.perez@technova.com', '625928654', 'CTO', 2, 11),
('InnovaGlobal', 'Lucía', 'Martínez', 'México', 'Ciudad de México', 'lucia.martinez@innovaglobal.mx', '5589761234', 'Gerente IT', 3, 2),
('SecureWare', 'Andrés', 'López', 'Colombia', 'Bogotá', 'andres.lopez@secureware.co', '3165674321', 'Jefe de Ciberseguridad', 5, 5);

INSERT INTO Deseo (id_deseo, nombre_deseo)
VALUES
(1, 'Información sobre un servicio'),
(2, 'Respuesta a un incidente'),
(3, 'Acceso a webinars'),
(4, 'Descargar un informe');

INSERT INTO Servicios (nombre_servicio) VALUES
('Managed Detection and Response'),
('SOC gestionado y SIEM como servicio'),
('DFIR'),
('CSIRT'),
('Cyber Threat Intelligence'),
('Ciberseguridad en la nube'),
('SOAR'),
('Continuación de negocio'),
('Servicios de formación y concienciación'),
('Seguridad ATM'),
('Análisis de riesgos'),
('Cumplimiento regulatorio'),
('Red Team'),
('Otros (especificar)');

INSERT INTO Solicitudes (id_cliente, id_deseo, id_servicio, mensaje)
VALUES
(1, 1, 1, 'Nos interesa conocer más sobre el servicio de detección gestionada.'),
(2, 1, 2, 'Quisiéramos una demostración del SIEM gestionado.'),
(3, 1, 3, 'Solicito información sobre el servicio de inteligencia de amenazas.');

INSERT INTO Departamentos (nombre_departamento)
VALUES
('SOC'),
('DFIR'),
('Red Team'),
('Blue Team'),
('Cyber Threat Intelligence'),
('Compliance y Regulación'),
('Concienciación y Formación'),
('Gestión de Incidentes'),
('Continuidad de Negocio'),
('Administración');

INSERT INTO Usuarios (nombre_usuario, email, password, rol, id_departamento)
VALUES
-- Administrador
('Iván el Máquina', 'ivan@ciberseguridad.com', 'hashedpassword123', 'Administrador', NULL),

-- Usuarios por departamento
('Laura SOC', 'laura.soc@ciberseguridad.com', 'pass123', 'Departamento', 1),
('Carlos DFIR', 'carlos.dfir@ciberseguridad.com', 'pass123', 'Departamento', 2),
('Sofía Red Team', 'sofia.red@ciberseguridad.com', 'pass123', 'Departamento', 3),
('Andrés Blue Team', 'andres.blue@ciberseguridad.com', 'pass123', 'Departamento', 4),
('Marta CTI', 'marta.cti@ciberseguridad.com', 'pass123', 'Departamento', 5),
('Fernando Legal', 'fernando.legal@ciberseguridad.com', 'pass123', 'Departamento', 6),
('Paula Formación', 'paula.formacion@ciberseguridad.com', 'pass123', 'Departamento', 7),
('Javier Incidentes', 'javier.incidentes@ciberseguridad.com', 'pass123', 'Departamento', 8),
('Elena Continuidad', 'elena.continuidad@ciberseguridad.com', 'pass123', 'Departamento', 9);

-- Asignaciones
INSERT INTO Asignaciones (id_solicitud, id_departamento, estado)
VALUES
(1, 1, 'Pendiente'),
(2, 2, 'Pendiente'),
(3, 3, 'Pendiente');

-- Historial de estados
INSERT INTO HistorialEstados (id_asignacion, estado_anterior, estado_nuevo, cambiado_por)
VALUES
(1, NULL, 'Pendiente', 'admin'),
(2, NULL, 'Pendiente', 'admin'),
(3, NULL, 'Pendiente', 'admin');





