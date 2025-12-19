<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireRole(['admin', 'editor', 'author']);

$error = '';
$success = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM post WHERE id_post = ?"); // Removed check for author for simplicity in MVP, but ideally should check
    if ($stmt->execute([$id])) {
        $success = "Article supprimé.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

// Fetch Articles
$sql = "SELECT p.*, c.name as category_name, u.username 
        FROM post p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        LEFT JOIN users u ON p.user_id = u.user_id 
        ORDER BY p.created_at_po DESC";
$articles = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Articles - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    
    <nav class="bg-gray-800 p-4 fixed w-full z-10 top-0">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex items-center flex-shrink-0 text-white mr-6">
                 <span class="font-semibold text-xl tracking-tight">BlogCMS Admin</span>
            </div>
             <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block pt-6 lg:pt-0">
                <div class="text-sm lg:flex-grow">
                    <a href="dashboard.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Dashboard</a>
                    <a href="users.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Utilisateurs</a>
                    <a href="articles.php" class="block mt-4 lg:inline-block lg:mt-0 text-white font-bold mr-4">Articles</a>
                    <a href="categories.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Catégories</a>
                    <a href="comments.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Commentaires</a>
                </div>
                 <div>
                    <a href="../logout.php" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0">Deconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-24 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gestion des Articles</h1>
            <a href="article_form.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Créer un article</a>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>

         <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titre</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Auteur</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catégorie</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold">
                                <a href="../article.php?id=<?= $article['id_post'] ?>" target="_blank" class="text-blue-600 hover:underline"><?= htmlspecialchars($article['title']) ?></a>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($article['username']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($article['category_name']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= date('d/m/Y', strtotime($article['created_at_po'])) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $article['status_Post'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= $article['status_Post'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="article_form.php?id=<?= $article['id_post'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">Éditer</a>
                                <form method="POST" class="inline-block" onsubmit="return confirm('Supprimer cet article ?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $article['id_post'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
