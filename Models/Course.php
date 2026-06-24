<?php

class Course
{
    private $conn;
    private $table = 'courses';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createWithDepartments($courseName, $durationYears, $departments = [])
    {
        // Ensure at least one non-empty department
        $cleanDepts = [];
        if (is_array($departments)) {
            foreach ($departments as $d) {
                $t = trim($d);
                if ($t !== '') $cleanDepts[] = $t;
            }
        }

        if (count($cleanDepts) === 0) {
            return false;
        }

        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO {$this->table} (course_name, duration_years) VALUES (:course_name, :duration_years)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':course_name' => $courseName,
                ':duration_years' => $durationYears
            ]);

            $courseId = $this->conn->lastInsertId();

            $dsql = "INSERT INTO departments (course_id, department_name) VALUES (:course_id, :department_name)";
            $dstmt = $this->conn->prepare($dsql);

            foreach ($cleanDepts as $dept) {
                $dstmt->execute([
                    ':course_id' => $courseId,
                    ':department_name' => $dept
                ]);
            }

            $this->conn->commit();

            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM courses ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            $dstmt = $this->conn->prepare("SELECT * FROM departments WHERE course_id = :course_id ORDER BY id ASC");
            $dstmt->execute([':course_id' => $id]);
            $course['departments'] = $dstmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $course;
    }

    public function updateWithDepartments($id, $courseName, $durationYears, $departments = [])
    {
        // Clean departments
        $cleanDepts = [];
        if (is_array($departments)) {
            foreach ($departments as $d) {
                $t = trim($d);
                if ($t !== '') $cleanDepts[] = $t;
            }
        }

        if (count($cleanDepts) === 0) {
            return false;
        }

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE {$this->table} SET course_name = :course_name, duration_years = :duration_years WHERE id = :id");
            $stmt->execute([
                ':course_name' => $courseName,
                ':duration_years' => $durationYears,
                ':id' => $id
            ]);

            // delete existing departments and re-insert
            $ddelete = $this->conn->prepare("DELETE FROM departments WHERE course_id = :course_id");
            $ddelete->execute([':course_id' => $id]);

            $dsql = "INSERT INTO departments (course_id, department_name) VALUES (:course_id, :department_name)";
            $dstmt = $this->conn->prepare($dsql);
            foreach ($cleanDepts as $dept) {
                $dstmt->execute([
                    ':course_id' => $id,
                    ':department_name' => $dept
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

}

?>
