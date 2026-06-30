<?php 

class Dashboard
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getStudentsCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM students WHERE status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['total']) ? (int) $row['total'] : 0;
    }
    public function getCourseCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM courses WHERE status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['total']) ? (int) $row['total'] : 0;
    }
    public function getDepartmentsCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM departments WHERE status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['total']) ? (int) $row['total'] : 0;
    }
    public function getStaffsCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM staff WHERE status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['total']) ? (int) $row['total'] : 0;
    }
    public function getAdmissionsByYear()
    {
        $sql = "SELECT admission_year AS year, COUNT(*) AS total FROM students WHERE status = 1 GROUP BY admission_year ORDER BY admission_year";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentAdmissions($limit = 5)
    {
        $sql = "SELECT s.id, s.application_no, s.first_name, s.last_name, s.phone, s.email, c.course_name, d.department_name
                FROM students AS s
                LEFT JOIN courses AS c ON c.id = s.course_id
                LEFT JOIN departments AS d ON d.id = s.department_id
                WHERE s.status = 1
                ORDER BY s.id DESC
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>