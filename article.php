<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$postId = (int)$_GET['id'];

// Fetch Article
try {
    $stmt = $pdo->prepare("SELECT p.*, u.username, c.name as category_name 
                           FROM post p 
                           LEFT JOIN users u ON p.user_id = u.user_id 
                           LEFT JOIN categories c ON p.category_id = c.category_id 
                           WHERE p.id_post = ? AND p.status_Post = 'published'");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();

    if (!$post) {
        redirect('index.php');
    }
    
    // Increment view count
    $pdo->prepare("UPDATE post SET view_count = view_count + 1 WHERE id_post = ?")->execute([$postId]);

    // Fetch Comments
    $stmtComments = $pdo->prepare("SELECT c.*, u.username 
                                   FROM comments c 
                                   LEFT JOIN users u ON c.user_id = u.user_id 
                                   WHERE c.id_post = ? AND c.status_com = 'approved' 
                                   ORDER BY c.created_at_com DESC");
    $stmtComments->execute([$postId]);
    $comments = $stmtComments->fetchAll();

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
             <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-gray-600">BlogCMS</a>
            <nav class="flex space-x-4">
               <a href="index.php" class="text-gray-600 hover:text-gray-900">Retour à l'accueil</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8 flex-grow max-w-4xl">
        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
            <?php if ($post['image_url']): ?>
                <img src="assets/images/<?= htmlspecialchars($post['image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-64 object-cover">
            <?php endif; ?>
            
            <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-blue-500 bg-blue-100 px-3 py-1 rounded"><?= htmlspecialchars($post['category_name']) ?></span>
                    <span class="text-sm text-gray-500">
                        Par <?= htmlspecialchars($post['username']) ?> • <?= date('d/m/Y', strtotime($post['created_at_po'])) ?>
                        • <span class="text-gray-400"><?= $post['view_count'] ?> vues</span>
                    </span>
                </div>

                <h1 class="text-4xl font-bold text-gray-800 mb-6"><?= htmlspecialchars($post['title']) ?></h1>

                <div class="prose max-w-none text-gray-700 leading-relaxed mb-8">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <section class="mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Commentaires (<?= count($comments) ?>)</h3>
            
            <div class="space-y-6">
                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-800"><?= htmlspecialchars($comment['author_name'] ?: $comment['username']) ?></h4>
                                <span class="text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($comment['created_at_com'])) ?></span>
                            </div>
                            <p class="text-gray-600"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 italic">Soyez le premier à commenter cet article !</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="container mx-auto px-6 py-4">
             <p class="text-center text-gray-500 text-sm">© <?= date('Y') ?> BlogCMS. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
