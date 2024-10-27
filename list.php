<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista kandydatów - Raptors</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #000;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1000px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Lista kandydatów</h1>
            <a href="index.html" class="back-button">Powrót do formularza</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Lp.</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Nr indeksu</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Data zgłoszenia</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'includes/db_config.php';

                try {
                    $stmt = $db->query("
                        SELECT * FROM candidates 
                        ORDER BY submission_date DESC
                    ");

                    $lp = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $lp++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone'] ?? '-') . "</td>";
                        echo "<td>" . date('Y-m-d H:i:s', strtotime($row['submission_date'])) . "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='7'>Wystąpił błąd podczas pobierania danych</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>