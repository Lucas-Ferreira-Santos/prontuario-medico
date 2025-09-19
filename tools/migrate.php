<?php
require_once __DIR__ . '/../app/db.php';

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(30) NOT NULL DEFAULT 'medico',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  cpf VARCHAR(14),
  birthdate DATE,
  sex VARCHAR(10),
  marital_status VARCHAR(30),
  phone VARCHAR(40),
  email VARCHAR(120),
  address VARCHAR(200),
  city VARCHAR(80),
  uf CHAR(2),
  cep VARCHAR(12),
  emergency_contact VARCHAR(120),
  emergency_phone VARCHAR(40),
  allergies VARCHAR(255),
  comorbidities VARCHAR(255),
  created_by INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_patients_name (name),
  INDEX idx_patients_cpf (cpf),
  CONSTRAINT fk_patients_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS visits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  visit_date DATETIME NOT NULL,
  complaint TEXT,
  hpi TEXT,
  ros TEXT,
  personal_history TEXT,
  family_history TEXT,
  habits TEXT,
  physical_exam TEXT,
  bp_systolic INT,
  bp_diastolic INT,
  hr INT,
  rr INT,
  temp_c DECIMAL(5,2),
  spo2 INT,
  weight_kg DECIMAL(6,2),
  height_m DECIMAL(4,2),
  bmi DECIMAL(5,2),
  capillary_glucose DECIMAL(6,2),
  diagnosis TEXT,
  plan TEXT,
  created_by INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_visits_patient (patient_id, visit_date),
  CONSTRAINT fk_visits_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_visits_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(50) NOT NULL,
  entity VARCHAR(50),
  entity_id INT,
  details TEXT,
  ip VARCHAR(64),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit_user (user_id, created_at),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

try {
  $pdo = db();
  $pdo->exec($sql);
  echo "<pre>OK: Tabelas criadas/atualizadas.</pre>";
} catch (Throwable $e) {
  http_response_code(500);
  echo "<pre>Erro: ".$e->getMessage()."</pre>";
}
