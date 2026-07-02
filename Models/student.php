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

    public function getAll($limit = null, $offset = 0, $search="")
    {
        $sql = "SELECT s.*, c.course_name, d.department_name
                FROM students AS s
                LEFT JOIN courses AS c ON c.id = s.course_id
                LEFT JOIN departments AS d ON d.id = s.department_id
                WHERE s.status = 1";

        if($search != "")
        {
            $sql .= "
                AND (
                    s.first_name LIKE :search
                    OR s.last_name LIKE :search
                    OR c.course_name LIKE :search
                    OR d.department_name LIKE :search
                )
            ";
        }

        $sql .= " ORDER BY s.id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        }

        if($search != "")
        {
            $stmt->bindValue(
                ":search",
                "%".$search."%"
            );
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($search="")
    {
        $sql = "SELECT COUNT(*)
                FROM students AS s
                LEFT JOIN courses AS c ON c.id = s.course_id
                LEFT JOIN departments AS d ON d.id = s.department_id
                WHERE s.status = 1";

        if($search != "")
        {
            $sql .= "
                AND (
                    s.first_name LIKE :search1
                    OR s.last_name LIKE :search2
                    OR c.course_name LIKE :search3
                    OR d.department_name LIKE :search4
                )
            ";
        }
        $stmt = $this->conn->prepare($sql);

        if($search != "")
        {
            $stmt->bindValue(":search1","%".$search."%");
            $stmt->bindValue(":search2","%".$search."%");
            $stmt->bindValue(":search3","%".$search."%");
            $stmt->bindValue(":search4","%".$search."%");
        }
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
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
        $sql = "SELECT s.*, c.course_name, d.department_name
                FROM students AS s
                LEFT JOIN courses AS c ON c.id = s.course_id
                LEFT JOIN departments AS d ON d.id = s.department_id
                WHERE s.admission_year = :year AND s.status = 1
                ORDER BY s.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':year', $year);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get single student by ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE id = ? AND status = 1");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id, $data)
    {
        $sql = "UPDATE students SET
                application_no = ?,
                first_name = ?,
                last_name = ?,
                father_name = ?,
                mother_name = ?,
                gender = ?,
                dob = ?,
                email = ?,
                phone = ?,
                address = ?,
                course_id = ?,
                department_id = ?,
                academic_year = ?,
                semester = ?,
                admission_year = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
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
            $data['course_id'],
            $data['department_id'],
            $data['academic_year'],
            $data['semester'],
            $data['admission_year'],
            $id,
        ]);
    }

    public function delete($id)
    {
        $sql = "UPDATE students SET status = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}

?>