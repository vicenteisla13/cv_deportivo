CREATE DATABASE IF NOT EXISTS cv_deportivo;
USE cv_deportivo;

CREATE TABLE IF NOT EXISTS curriculum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  campeonato VARCHAR(255) NOT NULL,
  fecha DATE NOT NULL,
  categoria VARCHAR(100) NOT NULL,
  lugar_obtenido VARCHAR(100) NOT NULL,
  puntos_mejorar TEXT NOT NULL,
  observacion TEXT NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
