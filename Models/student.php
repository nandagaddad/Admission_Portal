<?php

class Student
{
    private $conn;
    private $table = "students";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (
                    application_no,
                    first_name,
                    last_name,
                    father_name,
                    mother_name,
                    gender,
                    dob,
                    email,
                    phone,
                    address,
                    admission_year,
                    course_id,
                    department_id,
                    academic_year,
                    semester
                )
                VALUES
                (
                    :application_no,
                    :first_name,
                    :last_name,
                    :father_name,
                    :mother_name,
                    :gender,
                    :dob,
                    :email,
                    :phone,
                    :address,
                    :admission_year,
                    :course_id,
                    :department_id,
                    :academic_year,
                    :semester
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':application_no' => $data['application_no'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':father_name' => $data['father_name'],
            ':mother_name' => $data['mother_name'],
            ':gender' => $data['gender'],
            ':dob' => $data['dob'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':address' => $data['address'],
            ':admission_year' => $data['admission_year'],
            ':course_id' => $data['course_id'],
            ':department_id' => $data['department_id'],
            ':academic_year' =>$data['academic_year'],
            ':semester' =>$data['semester']
        ]);
    }
    public function getAll()
    {
        $sql = "SELECT * FROM students ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDepartments($CourseID)
    {
        $sql = "SELECT * FROM departments WHERE course_id = :course_id ORDER BY id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':course_id', $CourseID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getYear($CourseID)
    {
        $sql = "SELECT duration_years FROM courses WHERE id = :course_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':course_id', $CourseID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchByYear($year)
    {
        $sql = "SELECT * FROM students
                WHERE admission_year = :year
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':year', $year);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get single student by ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id, $data)
    {
        $sql = "UPDATE students SET
                application_no=?,
                first_name=?,
                last_name=?,
                father_name=?,
                mother_name=?,
                gender=?,
                dob=?,
                email=?,
                phone=?,
                address=?,
                course=?,
                admission_year=?,
                status=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['id'],
            $data['application_no'],
            $data['first_name'],
            $data['last_name'],
            $data['father_name'],
            $data['mother_name'],
            $data['gender'],
            $data['dob'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['course'],
            $data['admission_year'],
            $data['status'],
        ]);
    }
}

?>