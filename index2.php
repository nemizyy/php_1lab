<?php
// Включение отображения всех ошибок (Крок 7)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Поліфіл на випадок, якщо розширення mbstring не підключено в php.ini
if (!function_exists('mb_strlen')) {
    function mb_strlen(string $string, ?string $encoding = null): int {
        return function_exists('iconv_strlen') ? (int) @iconv_strlen($string, 'UTF-8') : (int) preg_match_all('/./us', $string);
    }
}

$errors = [];
$formData = [
    'title' => '',
    'author' => '',
    'message' => ''
];
$isSuccess = false;

// ============================================================================
// Крок 3. Серверная обработка и валидация данных
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из суперглобального массива $_POST
    $formData['title'] = trim($_POST['title'] ?? '');
    $formData['author'] = trim($_POST['author'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');

    // 1. Валидация поля title (без запрещенных спецсимволов via preg_match)
    if ($formData['title'] === '') {
        $errors['title'] = 'Заголовок теми є обов\'язковим.';
    } elseif (!preg_match('/^[\p{L}\p{N}\s\?\!\.\,\-\–\—\'\"«»]+$/u', $formData['title'])) {
        $errors['title'] = 'Заголовок містить заборонені спецсимволи (дозволені лише літери, цифри, пробіли та розділові знаки).';
    }

    // 2. Валидация поля author
    if ($formData['author'] === '') {
        $errors['author'] = 'Ім\'я автора є обов\'язковим.';
    }

    // 3. Валидация поля message (не менее 10 символов)
    if ($formData['message'] === '') {
        $errors['message'] = 'Текст повідомлення є обов\'язковим.';
    } elseif (mb_strlen($formData['message']) < 10) {
        $errors['message'] = 'Повідомлення має містити не менше 10 символів (наразі: ' . mb_strlen($formData['message']) . ').';
    }

    // Проверка наличия ошибок
    if (empty($errors)) {
        $isSuccess = true;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практична робота №2 — Варіант 9</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #eef2f7;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 120px;
        }

        button {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0b5ed7;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .field-error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 4px;
        }

        .js-error-summary {
            display: none;
        }

        .draft-notice {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Форум — Створення нової теми</h1>

        <!-- Контейнер для ошибок JS-валидации -->
        <div id="jsErrorBlock" class="alert alert-danger js-error-summary"></div>

        <?php if ($isSuccess): ?>
            <!-- Крок 4. Вывод подтверждения с принятыми данными -->
            <div class="alert alert-success">
                <h3>Тему успішно створено!</h3>
                <p><strong>Заголовок:</strong>
                    <?= htmlspecialchars($formData['title']) ?>
                </p>
                <p><strong>Автор:</strong>
                    <?= htmlspecialchars($formData['author']) ?>
                </p>
                <p><strong>Повідомлення:</strong>
                    <?= nl2br(htmlspecialchars($formData['message'])) ?>
                </p>
            </div>
            <p><a href="index2.php">Створити ще одну тему</a></p>
        <?php else: ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    Будь ласка, виправте помилки у формі перед відправкою.
                </div>
            <?php endif; ?>

            <!-- Крок 2. HTML-форма -->
            <form id="forumForm" action="index2.php" method="post" novalidate>
                <div class="form-group">
                    <label for="title">Заголовок теми *</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($formData['title']) ?>" required>
                    <?php if (isset($errors['title'])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors['title']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="author">Автор *</label>
                    <input type="text" id="author" name="author" value="<?= htmlspecialchars($formData['author']) ?>"
                        required>
                    <?php if (isset($errors['author'])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors['author']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="message">Текст повідомлення (мін. 10 символів) *</label>
                    <textarea id="message" name="message" required><?= htmlspecialchars($formData['message']) ?></textarea>
                    <div class="draft-notice">Чернетка автоматично зберігається у вашому браузері.</div>
                    <?php if (isset($errors['message'])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors['message']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit">Опублікувати тему</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('forumForm');
            if (!form) return; // Если форма не отображается (успешная отправка)

            const titleInput = document.getElementById('title');
            const authorInput = document.getElementById('author');
            const messageInput = document.getElementById('message');
            const jsErrorBlock = document.getElementById('jsErrorBlock');

            const STORAGE_KEY = 'forum_topic_draft';

            // =========================================================================
            // Крок 6. Восстановление черновика из localStorage
            // =========================================================================
            // Восстанавливаем данные только если PHP не вернул уже введенные данные из-за ошибок
            const isPhpRepopulated = <?= (!empty($formData['title']) || !empty($formData['author']) || !empty($formData['message'])) ? 'true' : 'false' ?>;

            if (!isPhpRepopulated) {
                const savedDraft = localStorage.getItem(STORAGE_KEY);
                if (savedDraft) {
                    try {
                        const draft = JSON.parse(savedDraft);
                        if (draft.title) titleInput.value = draft.title;
                        if (draft.author) authorInput.value = draft.author;
                        if (draft.message) messageInput.value = draft.message;
                    } catch (e) {
                        console.error('Помилка зчитування чернетки з localStorage', e);
                    }
                }
            }

            // Сохранение черновика при каждом вводе символа (событие input)
            function saveDraft() {
                const draft = {
                    title: titleInput.value,
                    author: authorInput.value,
                    message: messageInput.value
                };
                localStorage.setItem(STORAGE_KEY, JSON.stringify(draft));
            }

            titleInput.addEventListener('input', saveDraft);
            authorInput.addEventListener('input', saveDraft);
            messageInput.addEventListener('input', saveDraft);

            // =========================================================================
            // Крок 5. Клиентская JavaScript-валидация
            // =========================================================================
            form.addEventListener('submit', (event) => {
                let clientErrors = [];

                // Проверка сообщения (не менее 10 символов)
                if (messageInput.value.trim().length < 10) {
                    clientErrors.push('JS-помилка: Текст повідомлення повинен містити не менше 10 символів.');
                }

                // Проверка заголовка на недопустимые спецсимволы
                const titleRegex = /^[\p{L}\p{N}\s\?\!\.\,\-\–\—\'\"«»]+$/u;
                if (!titleInput.value.trim()) {
                    clientErrors.push('JS-помилка: Заголовок теми є обов\'язковим.');
                } else if (!titleRegex.test(titleInput.value.trim())) {
                    clientErrors.push('JS-помилка: Заголовок містить заборонені спецсимволи.');
                }

                // Если есть ошибки — отменяем отправку формы
                if (clientErrors.length > 0) {
                    event.preventDefault(); // Предотвращаем отправку
                    jsErrorBlock.innerHTML = clientErrors.join('<br>');
                    jsErrorBlock.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Если валидация прошла успешно, очищаем черновик из localStorage
                    localStorage.removeItem(STORAGE_KEY);
                }
            });
        });
    </script>

</body>

</html>