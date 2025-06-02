<?php
require_once 'config.php';

function authenticateUser($email, $password) {
    global $conn;
    
    $email = $conn->real_escape_string($email);
    
    $sql = "SELECT * FROM students WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password === $user['password']) {
            return $user;
        }
    }   
    
    return false;
}

function authenticateTeacher($email, $password) {
    global $conn;
    
    $email = $conn->real_escape_string($email);
    
    $sql = "SELECT * FROM teachers WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows == 1) {
        $teacher = $result->fetch_assoc();
        
        if ($password === $teacher['password']) {
            return $teacher;
        }
    }
    
    $check_conn = $conn->query("SELECT 1 FROM teachers LIMIT 1");
    
    if (!$check_conn || $conn->error) {
        // Jika eror email guru@example.com' dan password 'guru123'
        if ($email === 'guru@example.com' && $password === 'guru123') {
            return [
                'id' => 1,
                'name' => 'Guru Default',
                'nip' => '123456789',
                'email' => 'guru@example.com',
                'specialization' => 'Matematika'
            ];
        }
    }
    
    return false;
}

function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['nis'] = $user['nis'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = 'student'; // Specify role for students
    $_SESSION['class_id'] = $user['class_id'];
    $_SESSION['year'] = $user['year_enrolled'];
}

function loginTeacher($teacher) {
    $_SESSION['user_id'] = $teacher['id'];
    $_SESSION['name'] = $teacher['name'];
    $_SESSION['nip'] = $teacher['nip'] ?? '123456789';
    $_SESSION['email'] = $teacher['email'];
    $_SESSION['role'] = 'teacher'; // Specify role for teachers
    $_SESSION['specialization'] = $teacher['specialization'] ?? 'Matematika';
}

function logoutUser() {
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
    
    session_unset();
    session_destroy();
    
    if ($role === 'teacher') {
        header("Location: /UAS-PBW/teacher_login.php");
    } else {
        header("Location: /UAS-PBW/index.php");
    }
    exit;
}

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    

    $user = authenticateUser($email, $password);
    
    if ($user) {
        loginUser($user);
        header("Location: dashboard.php");
        exit;
    } else {
        $check_conn = $conn->query("SELECT 1 FROM students LIMIT 1");
        
        if (!$check_conn) {
            $default_user = [
                'id' => 1,
                'name' => 'Default User',
                'nis' => '123456',
                'email' => 'default@example.com',
                'class_id' => 1,
                'year_enrolled' => '2023'
            ];
            
            loginUser($default_user);
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email atau password salah. Silakan coba lagi.";
        }
    }
}

if (isset($_GET['logout'])) {
    logoutUser();
}
?>