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
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE status = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = :id AND status = 1");
        $stmt->execute([':id' => $id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            $dstmt = $this->conn->prepare("SELECT * FROM departments WHERE course_id = :course_id AND status = 1 ORDER BY id ASC");
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

                    $studentCountSql = "SELECT COUNT(*) FROM students WHERE course_id = ? AND department_id IN ($placeholders)";
                    $studentCountStmt = $this->conn->prepare($studentCountSql);
                    $studentCountStmt->execute(array_merge([$id], $removedIds));
                    $studentCount = (int) $studentCountStmt->fetchColumn();

                    $staffCountSql = "SELECT COUNT(*) FROM staff WHERE course_id = ? AND department_id IN ($placeholders)";
                    $staffCountStmt = $this->conn->prepare($staffCountSql);
                    $staffCountStmt->execute(array_merge([$id], $removedIds));
                    $staffCount = (int) $staffCountStmt->fetchColumn();

                    if ($studentCount > 0 || $staffCount > 0) {
                        $this->conn->rollBack();
                        return [
                            'success' => false,
                            'error' => 'Cannot remove a department while students or staff are still assigned to it. Remove or reassign them first.'
                        ];
                    }

                    $deleteSql = "UPDATE departments SET status = 0 WHERE course_id = ? AND id IN ($placeholders)";
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

            $departmentStmt = $this->conn->prepare("SELECT id FROM departments WHERE course_id = :course_id AND status = 1");
            $departmentStmt->execute([':course_id' => $id]);
            $departmentRows = $departmentStmt->fetchAll(PDO::FETCH_ASSOC);
            $departmentIds = array_column($departmentRows, 'id');

            $studentCountSql = "SELECT COUNT(*) FROM students WHERE course_id = :course_id";
            $studentCountStmt = $this->conn->prepare($studentCountSql);
            $studentCountStmt->execute([':course_id' => $id]);
            $studentCount = (int) $studentCountStmt->fetchColumn();

            $staffCountSql = "SELECT COUNT(*) FROM staff WHERE course_id = :course_id";
            $staffCountStmt = $this->conn->prepare($staffCountSql);
            $staffCountStmt->execute([':course_id' => $id]);
            $staffCount = (int) $staffCountStmt->fetchColumn();

            $departmentStudentCount = 0;
            $departmentStaffCount = 0;

            if (!empty($departmentIds)) {
                $departmentPlaceholders = implode(',', array_fill(0, count($departmentIds), '?'));

                $departmentStudentSql = "SELECT COUNT(*) FROM students WHERE department_id IN ($departmentPlaceholders)";
                $departmentStudentStmt = $this->conn->prepare($departmentStudentSql);
                $departmentStudentStmt->execute($departmentIds);
                $departmentStudentCount = (int) $departmentStudentStmt->fetchColumn();

                $departmentStaffSql = "SELECT COUNT(*) FROM staff WHERE department_id IN ($departmentPlaceholders)";
                $departmentStaffStmt = $this->conn->prepare($departmentStaffSql);
                $departmentStaffStmt->execute($departmentIds);
                $departmentStaffCount = (int) $departmentStaffStmt->fetchColumn();
            }

            if ($studentCount > 0 || $staffCount > 0 || $departmentStudentCount > 0 || $departmentStaffCount > 0) {
                $this->conn->rollBack();
                return false;
            }

            $dstmt = $this->conn->prepare("UPDATE departments SET status = 0 WHERE course_id = :course_id");
            $dstmt->execute([':course_id' => $id]);

            $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = 0 WHERE id = :id");
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
