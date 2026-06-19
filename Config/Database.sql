CREATE TABLE admin(
id INT PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(50),
password VARCHAR(255)
);

CREATE TABLE students(
id INT PRIMARY KEY AUTO_INCREMENT,

application_no VARCHAR(30),

first_name VARCHAR(50),

last_name VARCHAR(50),

father_name VARCHAR(50),

mother_name VARCHAR(50),

gender VARCHAR(20),

dob DATE,

email VARCHAR(100),

phone VARCHAR(20),

address TEXT,

course VARCHAR(100),

admission_year YEAR,

status ENUM('Pending','Approved'),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);