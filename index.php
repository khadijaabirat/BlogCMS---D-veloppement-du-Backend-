<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

// Fetch articles
try {
    $stmt = $pdo->query("SELECT p.*, u.username, c.name as category_name 
                         FROM post p 
                         LEFT JOIN users u ON p.user_id = u.user_id 
                         LEFT JOIN categories c ON p.category_id = c.category_id 
                         WHERE p.status_Post = 'published' 
                         ORDER BY p.created_at_po DESC");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur de récupération des articles : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-gray-600">BlogCMS</a>
            <nav class="flex space-x-4">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin/dashboard.php" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <?php endif; ?>
                    <span class="text-gray-600">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-600 hover:text-gray-900">Connexion</a>
                    <a href="register.php" class="text-blue-500 hover:text-blue-700">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8 flex-grow">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Derniers Articles</h1>

        <?php if (count($posts) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <?php if ($post['image_url']): ?>
                            <img src="assets/images/<?= htmlspecialchars($post['image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">Pas d'image</div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-blue-500 bg-blue-100 px-2 py-1 rounded"><?= htmlspecialchars($post['category_name']) ?></span>
                                <span class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($post['created_at_po'])) ?></span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2 hover:text-blue-600">
                                <a href="article.php?id=<?= $post['id_post'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                            </h2>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                <?= substr(strip_tags($post['content']), 0, 100) ?>...
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Par <span class="font-semibold"><?= htmlspecialchars($post['username']) ?></span></span>
                                <a href="article.php?id=<?= $post['id_post'] ?>" class="text-blue-500 hover:text-blue-700 font-medium">Lire la suite &rarr;</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600 text-center text-lg">Aucun article publié pour le moment.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="container mx-auto px-6 py-4">
            <p class="text-center text-gray-500 text-sm">© <?= date('Y') ?> BlogCMS. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
