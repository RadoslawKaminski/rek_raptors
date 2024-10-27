$(document).ready(function () {
    // Funkcje pomocnicze do walidacji
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return phone === '' || /^[\d\s+()-]{9,15}$/.test(phone);
    }

    function isValidStudentId(studentId) {
        return /^\d{5,6}$/.test(studentId);
    }

    function isValidName(name) {
        return /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s-]{2,50}$/.test(name);
    }

    $('#recruitmentForm').on('submit', function (e) {
        e.preventDefault();

        // Pobieranie wartości
        const firstName = $('#first_name').val().trim();
        const lastName = $('#last_name').val().trim();
        const studentId = $('#student_id').val().trim();
        const email = $('#email').val().trim();
        const phone = $('#phone').val().trim();

        // Walidacja
        let errors = [];

        if (!isValidName(firstName)) {
            errors.push('Imię może zawierać tylko litery, spacje i myślniki (2-50 znaków)');
        }

        if (!isValidName(lastName)) {
            errors.push('Nazwisko może zawierać tylko litery, spacje i myślniki (2-50 znaków)');
        }

        if (!isValidStudentId(studentId)) {
            errors.push('Numer indeksu musi składać się z 5-6 cyfr');
        }

        if (!isValidEmail(email)) {
            errors.push('Podaj prawidłowy adres email');
        }

        if (!isValidPhone(phone)) {
            errors.push('Numer telefonu może zawierać tylko cyfry, spacje, +, -, () (9-15 znaków)');
        }

        if (errors.length > 0) {
            alert('Proszę poprawić następujące błędy:\n\n' + errors.join('\n'));
            return;
        }

        // Jeśli walidacja przeszła, wysyłamy dane
        $.ajax({
            type: 'POST',
            url: 'add_candidate.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#recruitmentForm')[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Wystąpił błąd podczas wysyłania formularza');
            }
        });
    });
});