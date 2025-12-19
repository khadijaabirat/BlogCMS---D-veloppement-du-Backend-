<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireRole(['admin']);

// Fetch basic stats
$stmtUser = $pdo->query("SELECT COUNT(*) FROM users");
$userCount = $stmtUser->fetchColumn();

$stmtPost = $pdo->query("SELECT COUNT(*) FROM post");
$postCount = $stmtPost->fetchColumn();

$stmtComment = $pdo->query("SELECT COUNT(*) FROM comments");
$commentCount = $stmtComment->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-gray-800 p-4 fixed w-full z-10 top-0">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex items-center flex-shrink-0 text-white mr-6">
                <span class="font-semibold text-xl tracking-tight">BlogCMS Admin</span>
            </div>
            <div class="block lg:hidden">
                <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
                    <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                </button>
            </div>
            <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block pt-6 lg:pt-0">
                <div class="text-sm lg:flex-grow">
                    <a href="dashboard.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Dashboard</a>
                    <a href="users.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Utilisateurs</a>
                    <a href="articles.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Articles</a>
                    <a href="categories.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Catégories</a>
                    <a href="comments.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">Commentaires</a>
                </div>
                <div>
                     <span class="text-white mr-4">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="../logout.php" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0">Deconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-24 px-4">
        <h1 class="text-3xl font-bold mb-6">Tableau de bord</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stats Cards -->
            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                         <!-- Icon User -->
                         <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-medium">Utilisateurs</h2>
                        <p class="text-2xl font-bold text-gray-800"><?= $userCount ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                         <!-- Icon Article -->
                         <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-medium">Articles</h2>
                        <p class="text-2xl font-bold text-gray-800"><?= $postCount ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-500">
                 <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-full">
                         <!-- Icon Comment -->
                         <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-medium">Commentaires</h2>
                        <p class="text-2xl font-bold text-gray-800"><?= $commentCount ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
