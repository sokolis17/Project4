<?php
require_once __DIR__ . '/includes/init.php';

// 1. ЗАЩИТА (В самом верху!)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. ИНИЦИАЛИЗАЦИЯ (Важно: делаем их пустыми!)
$error = '';
$success = '';
$id = $_SESSION['user_id'];

// 3. ОБРАБОТКА ФОРМЫ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Улучшение №1: trim() убирает случайные пробелы по краям
    $new_pass = trim($_POST['new_pass']);

    // Улучшение №2: Проверка на пустоту И длину
    if (empty($new_pass)) {
        $error = "Пароль не может быть пустым";
    } elseif (strlen($new_pass) < 6) { 
        $error = "Пароль слишком короткий (минимум 6 символов)";
    } else {
        // Если ошибок нет — работаем с базой
        $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        
        $query = "UPDATE users SET password_hash = :hash WHERE id = :id";
        $stmt = $pdo->prepare($query);
        
        try {
            $stmt->execute([
                ':hash' => $new_pass_hash, 
                ':id' => $id
            ]);
            // Только тут присваиваем успех!
            $_SESSION['flash_success'] = "✅ Пароль успешно изменен!";
            header('Location: change_password.php');
            exit;
        } catch (Exception $e) {
            $error = 'Ошибка БД: '.$e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>🔒 Смена пароля</h4>
                </div>
                <div class="card-body">

                    <!-- Улучшение №3: Показываем блоки только если в них есть текст -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['flash_success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                echo $_SESSION['flash_success']; 
                                unset($_SESSION['flash_success']);?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <div class="mb-3">
                            <label class="form-label">Введите новый пароль:</label>
                            <input type='password' name='new_pass' class="form-control" placeholder="Минимум 6 символов">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>