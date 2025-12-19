<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireRole(['admin', 'editor']);

$error = '';
$success = '';

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            if (!empty($name)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
                    $stmt->execute([$name, $description]);
                    $success = "Catégorie ajoutée.";
                } catch (PDOException $e) {
                    $error = "Erreur : " . $e->getMessage();
                }
            } else {
                $error = "Le nom est obligatoire.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)$_POST['id'];
            try {
                // Check if used in posts? (Usually restricted or set null, assuming validation here)
                $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
                $stmt->execute([$id]);
                $success = "Catégorie supprimée.";
            } catch (PDOException $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = (int)$_POST['id'];
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            try {
                 $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE category_id = ?");
                 $stmt->execute([$name, $description, $id]);
                 $success = "Catégorie mise à jour.";
            } catch (PDOException $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    }
}

// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Catégories - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <?php include 'header.php'; // Simplified, assuming we might extract header later, but for now inline nav or duplicate ?>
    <nav class="bg-gray-800 p-4 fixed w-full z-10 top-0">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex items-center flex-shrink-0 text-white mr-6">
                <span class="font-semibold text-xl tracking-tight">BlogCMS Admin</span>
            </div>
             <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block pt-6 lg:pt-0">
                <div class="text-sm lg:flex-grow">
                    <a href="dashboard.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Dashboard</a>
                    <a href="users.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Utilisateurs</a>
                    <a href="articles.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Articles</a>
                    <a href="categories.php" class="block mt-4 lg:inline-block lg:mt-0 text-white mr-4 font-bold">Catégories</a>
                    <a href="comments.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Commentaires</a>
                </div>
                 <div>
                    <a href="../logout.php" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0">Deconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-24 px-4">
        <h1 class="text-3xl font-bold mb-6">Gestion des Catégories</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>

        <!-- Add Form -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-bold mb-4">Ajouter une catégorie</h2>
            <form method="POST" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="action" value="add">
                <div class="w-full md:w-1/3">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                    <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="w-full md:w-1/3">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <input type="text" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                     <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Ajouter</button>
                </div>
            </form>
        </div>

        <!-- List -->
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= $cat['category_id'] ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold"><?= htmlspecialchars($cat['name']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= htmlspecialchars($cat['description']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <form method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $cat['category_id'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                                <!-- Edit could be a modal or separate page, keeping simple for now -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
