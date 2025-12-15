<?php
require_once 'includes/init.php';
require_once 'includes/header.php';

// --- ТВОЙ PHP КОД ЗДЕСЬ ---
// 1. Напиши SQL с JOIN (posts + users + categories)
$sql = "SELECT posts.*, users.username, categories.name as category_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        JOIN categories ON posts.category_id = categories.id 
        ORDER BY posts.created_at DESC";

$posts = $pdo->query($sql)->fetchAll();



?>

<!-- Блок приветствия (Jumbotron) -->
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Добро пожаловать в Блог!</h1>
        <p class="col-md-8 fs-4">Пишем код, ловим баги, пьем кофе.</p>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a class="btn btn-primary btn-lg" href="register.php">Создать аккаунт</a>
        <?php else: ?>
            <a class="btn btn-success btn-lg" href="create_post.php">Написать пост</a>
        <?php endif; ?>
    </div>
</div>

<h2>📰 Свежие записи</h2>

<div class="row">
    
    <!-- --- ТВОЙ PHP КОД ЗДЕСЬ (УСЛОВИЕ) --- -->
    <!-- Если $posts пустой, выведи <p>Нет постов</p> -->
    <?php if (empty($posts)):?>
    <p>Нет постов</p>
    <!-- Иначе запускай цикл foreach -->
    <?php else: ?>
        <!-- --- HTML КАРТОЧКА (Она должна повторяться в цикле) --- -->
         <?php foreach ($posts as $post): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <!-- Категория -->
                    <span class="badge bg-secondary mb-2">
                         <?php echo $post['category_name']; ?>
                    </span>

                    <!-- Заголовок -->
                    <h5 class="card-title">
                         <?php echo $post['title']; ?>
                    </h5>
                    
                    <!-- Текст (обрежь до 100 символов) -->
                    <p class="card-text text-muted">
                        <?php echo substr($post['content'], 0, 100) . '...'; ?>
                    </p>
                </div>
                
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <!-- ВЫВЕДИ АВТОРА И ДАТУ -->
                        👤 <?php echo $post['username']; ?><br>
                        📅 <?php echo date('d,m,Y',strtotime($post['created_at'])); ?>
                    </small>
                    <a href="#" class="btn btn-outline-primary btn-sm">Читать</a>
                </div>
            </div>
        </div>
        <!-- --- КОНЕЦ КАРТОЧКИ --- -->
            
    <!-- --- КОНЕЦ ЦИКЛА --- -->
<?php endforeach; ?>
<?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>