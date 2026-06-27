CREATE TABLE admin(
	id INT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(50),
	password VARCHAR(255)
);

INSERT INTO  admin(username, password) VALUES (admin, admin123);

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
	admission_year YEAR,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
	course_id INT;
	department_id INT;
	academic_year INT;
	semester INT
);

CREATE TABLE IF NOT EXISTS courses (
	id INT PRIMARY KEY AUTO_INCREMENT,
	course_name VARCHAR(255) NOT NULL,
	duration_years INT DEFAULT 1,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS departments (
	id INT PRIMARY KEY AUTO_INCREMENT,
	course_id INT NOT NULL,
	department_name VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

ALTER TABLE students ADD CONSTRAINT fk_student_course
        FOREIGN KEY (course_id)
        REFERENCES courses(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

ALTER TABLE students ADD CONSTRAINT fk_student_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE

UPDATE admin SET password = 'admin123' where id = 1;

UPDATE admin SET password = '$2y$10$9.bXFH5u60X6bfEXtBHhI.6Cpw9pybHL/CE4JrxEytC70ZRP43h3.' where id = 1;