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
        // Clean departments and deduplicate
        $cleanDepts = [];
        if (is_array($departments)) {
            foreach ($departments as $d) {
                $t = trim($d);
                if ($t !== '') $cleanDepts[] = $t;
            }
        }
        $cleanDepts = array_values(array_unique($cleanDepts));

        if (count($cleanDepts) === 0) {
            return ['success' => false, 'error' => 'At least one department is required.'];
        }

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE {$this->table} SET course_name = :course_name, duration_years = :duration_years WHERE id = :id");
            $stmt->execute([
                ':course_name' => $courseName,
                ':duration_years' => $durationYears,
                ':id' => $id
            ]);

            $existingStmt = $this->conn->prepare("SELECT id, department_name FROM departments WHERE course_id = :course_id");
            $existingStmt->execute([':course_id' => $id]);
            $existingDepartments = $existingStmt->fetchAll(PDO::FETCH_ASSOC);

            $existingNames = array_column($existingDepartments, 'department_name');
            $removedNames = array_diff($existingNames, $cleanDepts);
            $addedNames = array_diff($cleanDepts, $existingNames);

            if (!empty($removedNames)) {
                $removedIds = [];
                foreach ($existingDepartments as $dept) {
                    if (in_array($dept['department_name'], $removedNames, true)) {
                        $removedIds[] = $dept['id'];
                    }
                }

                if (!empty($removedIds)) {
                    $placeholders = implode(',', array_fill(0, count($removedIds), '?'));
                    $countSql = "SELECT COUNT(*) FROM students WHERE course_id = ? AND department_id IN ($placeholders)";
                    $countStmt = $this->conn->prepare($countSql);
                    $countStmt->execute(array_merge([$id], $removedIds));
                    $count = (int) $countStmt->fetchColumn();

                    if ($count > 0) {
                        $this->conn->rollBack();
                        return [
                            'success' => false,
                            'error' => 'Cannot remove a department while students are still assigned to it. Remove or reassign those students first.'
                        ];
                    }

                    $deleteSql = "DELETE FROM departments WHERE course_id = ? AND id IN ($placeholders)";
                    $deleteStmt = $this->conn->prepare($deleteSql);
                    $deleteStmt->execute(array_merge([$id], $removedIds));
                }
            }

            if (!empty($addedNames)) {
                $dsql = "INSERT INTO departments (course_id, department_name) VALUES (:course_id, :department_name)";
                $dstmt = $this->conn->prepare($dsql);
                foreach ($addedNames as $dept) {
                    $dstmt->execute([
                        ':course_id' => $id,
                        ':department_name' => $dept
                    ]);
                }
            }

            $this->conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'error' => 'Failed to update course'];
        }
    }

    public function delete($id)
    {
        try {
            $this->conn->beginTransaction();

            $dstmt = $this->conn->prepare("DELETE FROM departments WHERE course_id = :course_id");
            $dstmt->execute([':course_id' => $id]);

            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $result = $stmt->execute([':id' => $id]);

            $this->conn->commit();
            return $result;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

}

?>
