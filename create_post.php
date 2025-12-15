<?php
require_once 'includes/init.php';
// 1. ЗАЩИТА: Если не вошел — выгоняем
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Получаем категории из БД (для выпадающего списка)
$sql = "SELECT * FROM categories ORDER BY name";
$categories = $pdo->query($sql)->fetchAll();

$error = '';

// 3. ОБРАБОТКА ФОРМЫ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- ТВОЙ КОД БУДЕТ ЗДЕСЬ (ШАГ 2) ---
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    $slug = strtolower(trim($title)) . '-' . time();
    $user_id = $_SESSION['user_id'];
    // ... insert ...
    $sql = "INSERT INTO posts (title, slug, content, user_id, category_id, is_published) VALUES (:title, :slug, :content, :user_id, :category_id, :is_published)";
    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([':title' => $title,':slug' => $slug,':content' => $content,':user_id' => $user_id,':category_id' => $category_id,':is_published' => true]);
        header('Location: index.php');
        exit;
    }catch(PDOException $e){
        $error = $e->getMessage();
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4>📝 Написать новый пост</h4>
                </div>
                <div class="card-body">

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="post">

                        <!-- Заголовок -->
                        <div class="mb-3">
                            <label class="form-label">Заголовок поста</label>
                            <input type="text" name="title" class="form-control" placeholder="О чем будем писать?" required>
                        </div>

                        <!-- Категория -->
                        <div class="mb-3">
                            <label class="form-label">Категория</label>
                            <select name="category_id" class="form-select">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Текст -->
                        <div class="mb-3">
                            <label class="form-label">Текст</label>
                            <textarea name="content" class="form-control" rows="8" placeholder="Пиши здесь..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Опубликовать</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>