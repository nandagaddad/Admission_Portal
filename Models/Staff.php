<?php

class Staff
{
    private $conn;
    private $table = "staff";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $sql = "SELECT st.*, c.course_name, d.department_name
                FROM staff AS st
                LEFT JOIN courses AS c ON c.id = st.course_id
                LEFT JOIN departments AS d ON d.id = st.department_id
                ORDER BY st.id DESC";

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

    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (
                    staff_id,
                    first_name,
                    last_name,
                    gender,
                    email,
                    phone,
                    designation,
                    course_id,
                    department_id,
                    qualification,
                    joining_date
                )
                VALUES
                (
                    :staff_id,
                    :first_name,
                    :last_name,
                    :gender,
                    :email,
                    :phone,
                    :designation,
                    :course_id,
                    :department_id,
                    :qualification,
                    :joining_date
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':staff_id' => $data['staff_id'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':gender' => $data['gender'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':designation' => $data['designation'],
            ':course_id' => $data['course_id'],
            ':department_id' => $data['department_id'],
            ':qualification' =>$data['qualification'],
            ':joining_date' =>$data['joining_date']
        ]);
    }

}