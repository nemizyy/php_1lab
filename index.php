<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


$topics = [
    [
        'title' => 'Як почати вивчати PHP у 2026 році?',
        'author' => 'Олексій',
        'repliesCount' => 35,
        'createdAt' => '2026-09-20'
    ],
    [
        'title' => 'Обговорення практичної роботи №1 з WEB-програмування',
        'author' => 'Марія',
        'repliesCount' => 12,
        'createdAt' => '2026-09-25'
    ],
    [
        'title' => 'Вибір IDE для веб-розробки: VS Code vs PhpStorm',
        'author' => 'Дмитро',
        'repliesCount' => 28,
        'createdAt' => '2026-09-22'
    ],
    [
        'title' => 'Питання щодо налаштування локального сервера',
        'author' => 'Анна',
        'repliesCount' => 5,
        'createdAt' => '2026-09-27'
    ],
    [
        'title' => 'Кращі практики використання масивів та циклів в PHP',
        'author' => 'Ігор',
        'repliesCount' => 42,
        'createdAt' => '2026-09-15'
    ],
];


function formatTopic(array $topic): string
{
    return "«{$topic['title']}» (Автор: {$topic['author']}, Дата: {$topic['createdAt']})";
}


$totalReplies = array_sum(array_column($topics, 'repliesCount'));
$totalTopics = count($topics);
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практична робота №1 — Варіант 9</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #eef2f7;
            padding-bottom: 10px;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eef2f7;
        }

        th {
            background-color: #f8f9fa;
            color: #495057;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 12px;
        }

        .badge-hot {
            background-color: #ffe3e3;
            color: #d9381e;
            border: 1px solid #f8b4b4;
        }

        .badge-normal {
            background-color: #e9ecef;
            color: #495057;
        }

        .summary-card {
            margin-top: 25px;
            padding: 15px 20px;
            background-color: #eef6ff;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
        }

        .summary-card h3 {
            margin: 0 0 8px 0;
            color: #0b5ed7;
        }

        .summary-card p {
            margin: 4px 0;
            font-size: 15px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Форум обговорень — Каталог тем</h1>


        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Короткий опис теми</th>
                    <th>Відповіді</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topics as $index => $topic): ?>
                    <?php

                    $isHot = $topic['repliesCount'] > 20;
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= formatTopic($topic) ?></td>
                        <td><strong><?= $topic['repliesCount'] ?></strong></td>
                        <td>
                            <?php if ($isHot): ?>
                                <span class="badge badge-hot">🔥 Гаряча тема</span>
                            <?php else: ?>
                                <span class="badge badge-normal">Звичайна</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <div class="summary-card">
            <h3>Підсумкова статистика форуму</h3>
            <p>Загальна кількість тем: <strong><?= $totalTopics ?></strong></p>
            <p>Загальна кількість повідомлень (відповідей): <strong><?= $totalReplies ?></strong></p>
        </div>
    </div>

</body>

</html>