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
                    course,
                    admission_year,
                    status
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
                    :course,
                    :admission_year,
                    :status
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
            ':course' => $data['course'],
            ':admission_year' => $data['admission_year'],
            ':status' => $data['status']
        ]);
    }
    public function getAll()
    {
        $sql = "SELECT * FROM students ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
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
}

?>