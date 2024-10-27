<?php
require_once 'includes/db_config.php';

function validateInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isValidName($name)
{
    return preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s-]{2,50}$/', $name);
}

function isValidStudentId($id)
{
    return preg_match('/^\d{5,6}$/', $id);
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidPhone($phone)
{
    return empty($phone) || preg_match('/^[\d\s+()-]{9,15}$/', $phone);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Walidacja i sanityzacja danych wejściowych
        $firstName = validateInput($_POST['first_name'] ?? '');
        $lastName = validateInput($_POST['last_name'] ?? '');
        $studentId = validateInput($_POST['student_id'] ?? '');
        $email = validateInput($_POST['email'] ?? '');
        $phone = validateInput($_POST['phone'] ?? '');

        // Sprawdzanie poprawności danych
        $errors = [];

        if (!isValidName($firstName)) {
            $errors[] = 'Nieprawidłowe imię';
        }

        if (!isValidName($lastName)) {
            $errors[] = 'Nieprawidłowe nazwisko';
        }

        if (!isValidStudentId($studentId)) {
            $errors[] = 'Nieprawidłowy numer indeksu';
        }

        if (!isValidEmail($email)) {
            $errors[] = 'Nieprawidłowy adres email';
        }

        if (!isValidPhone($phone)) {
            $errors[] = 'Nieprawidłowy numer telefonu';
        }

        if (!empty($errors)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Błędy walidacji: ' . implode(', ', $errors)
            ]);
            exit;
        }

        // Przygotowanie zapytania
        $stmt = $db->prepare("
            INSERT INTO candidates 
            (first_name, last_name, student_id, email, phone) 
            VALUES 
            (:first_name, :last_name, :student_id, :email, :phone)
        ");

        // Wykonanie zapytania z przekazanymi danymi
        $result = $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'student_id' => $studentId,
            'email' => $email,
            'phone' => $phone ?: null
        ]);

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Zgłoszenie zostało przyjęte'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Wystąpił błąd podczas zapisywania danych'
            ]);
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Ten numer indeksu już istnieje w bazie'
            ]);
        } else {
            error_log($e->getMessage()); // Logowanie błędu
            echo json_encode([
                'status' => 'error',
                'message' => 'Wystąpił błąd podczas zapisywania danych'
            ]);
        }
    }
}
