<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireRole(['admin', 'editor', 'author']);

$error = '';
$success = '';
$article = [
    'title' => '',
    'content' => '',
    'category_id' => '',
    'status_Post' => 'draft',
    'id_post' => ''
];
$isEdit = false;

// If Edit Mode
if (isset($_GET['id'])) {
    $isEdit = true;
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM post WHERE id_post = ?");
    $stmt->execute([$id]);
    $fetchedSub = $stmt->fetch();
    if ($fetchedSub) {
        $article = $fetchedSub;
    } else {
        redirect('articles.php');
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = $_POST['content']; // Allow HTML? For now sanitize but maybe allow basic tags via separate logic or just raw. Project says "HTML5/CSS3", doesn't specify editor. Using sanitize for safety but stripping tags might break formatting. Let's use simple trim for now and assume admin is trusted or implement HTML Purifier later.
    // Actually project says "Protection XSS (htmlspecialchars)", so we should sanitize on OUTPUT, and store raw or sanitized. 
    // Best practice: Store raw, sanitize on output. 
    // But `sanitize` function does htmlspecialchars.
    // Let's use raw for content saving, sanitize output.
    $content = $_POST['content'];
    
    $category_id = (int)$_POST['category_id'];
    $status = $_POST['status'];
    $image_url = $article['image_url'] ?? '';

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../assets/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        // Simple check for image type
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_url = $fileName;
            } else {
                $error = "Erreur lors de l'upload de l'image.";
            }
        } else {
            $error = "Format d'image non supporté.";
        }
    }

    if (!$error) {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE post SET title=?, content=?, category_id=?, status_Post=?, image_url=? WHERE id_post=?");
            try {
                $stmt->execute([$title, $content, $category_id, $status, $image_url, $article['id_post']]);
                $success = "Article mis à jour.";
                // refresh data
                $article['title'] = $title;
                $article['content'] = $content;
                $article['category_id'] = $category_id;
                $article['status_Post'] = $status;
                $article['image_url'] = $image_url;
            } catch (PDOException $e) {
                $error = "Erreur: " . $e->getMessage();
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO post (title, content, category_id, status_Post, image_url, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$title, $content, $category_id, $status, $image_url, $_SESSION['user_id']]);
                $success = "Article créé.";
                $isEdit = true; // Switch to edit mode
                $article['id_post'] = $pdo->lastInsertId();
                 $article['title'] = $title;
                $article['content'] = $content;
                $article['category_id'] = $category_id;
                $article['status_Post'] = $status;
                $article['image_url'] = $image_url;
            } catch (PDOException $e) {
                $error = "Erreur: " . $e->getMessage();
            }
        }
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Éditer' : 'Créer' ?> Article - Admin</title>
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
                    <a href="articles.php" class="block mt-4 lg:inline-block lg:mt-0 text-gray-200 hover:text-white mr-4">&larr; Retour aux articles</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-24 px-4 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6"><?= $isEdit ? 'Éditer l\'article' : 'Nouvel Article' ?></h1>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $article['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Image</label>
                <?php if (!empty($article['image_url'])): ?>
                    <img src="../assets/images/<?= htmlspecialchars($article['image_url']) ?>" class="h-32 mb-2 object-cover rounded">
                <?php endif; ?>
                <input type="file" name="image" class="block w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700
                  hover:file:bg-blue-100
                "/>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Contenu</label>
                <textarea name="content" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?= htmlspecialchars($article['content']) ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Statut</label>
                <select name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="draft" <?= $article['status_Post'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $article['status_Post'] === 'published' ? 'selected' : '' ?>>Publié</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    <?= $isEdit ? 'Mettre à jour' : 'Publier' ?>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
