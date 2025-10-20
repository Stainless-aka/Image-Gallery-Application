-- fug_gallery SQL dump (includes demo data)

CREATE DATABASE IF NOT EXISTS fug_gallery;
USE fug_gallery;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(150) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name,email,password,role) VALUES
('Admin One','admin@fug.edu.ng','admin123','admin'),
('John Doe','john@fug.edu.ng','12345','user'),
('Mary James','mary@fug.edu.ng','password','user');

CREATE TABLE IF NOT EXISTS departments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  hod_name VARCHAR(150) NOT NULL,
  hod_image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO departments (name,hod_name,hod_image) VALUES
('Computer Science','Dr. A. Musa','hod_cs.jpg'),
('Physics','Dr. S. Bello','hod_phy.jpg'),
('Chemistry','Dr. K. Umar','hod_chem.jpg');

CREATE TABLE IF NOT EXISTS staffs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  position VARCHAR(150) NOT NULL,
  department_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO staffs (name,position,department_id) VALUES
('Engr. Ahmed','Senior Lecturer',1),
('Mr. Johnson','Assistant Lecturer',1),
('Mrs. Grace','Lab Technician',2),
('Dr. James','Research Fellow',3);

CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO events (title,image) VALUES
('University Convocation','convocation.jpg'),
('Science Fair Exhibition','science_fair.jpg'),
('Cultural Day','cultural_day.jpg');
