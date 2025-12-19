<?php 
session_start();
include '../includes/db.php';
$current_page = 'post_add';
// Protection
if(empty($_SESSION['user_id'])){
    header('location:login.php');
    exit;
}

$name = $_SESSION['username'];
$role = $_SESSION['role'];
$errors = [];

// Récupérer catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Traitement formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];
    
    // Validation
    if(empty($title)){
        $errors[] = "Le titre est requis";
    }
    if(empty($content)){
        $errors[] = "Le contenu est requis";
    }
    if(empty($category_id)){
        $errors[] = "La catégorie est requise";
    }
    
    // Si pas d'erreurs
    if(empty($errors)){
        $stmt = $pdo->prepare("
            INSERT INTO post (title, content, user_id, category_id, status_Post, created_at_po, updated_at_po) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $title,
            $content,
            $_SESSION['user_id'],
            $category_id,
            $status
        ]);
        
        header('Location: posts.php?success=created');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ajouter Article - BlogCMS</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5" rel="stylesheet" />
</head>

<body class="m-0 font-sans antialiased font-normal bg-gray-50 text-slate-500">
        <?php include 'sidebar.php'; ?>

    <!-- Sidebar -->
    <aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
        <div class="h-19.5">
            <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
            <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="dashboard.php">
                <img src="../assets/img/logo-ct.png" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
                <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">BlogCMS Admin</span>
            </a>
        </div>

        <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

        <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
            <ul class="flex flex-col pl-0 mb-0">
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors" href="dashboard.php">
                        <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <i class="fas fa-chart-line text-slate-700"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-white px-4 font-semibold text-slate-700 transition-colors" href="posts.php">
                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Articles</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors" href="categories.php">
                        <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <i class="fas fa-folder text-slate-700"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Catégories</span>
                    </a>
                </li>

                <li class="w-full mt-4">
                    <h6 class="pl-6 ml-2 font-bold leading-tight uppercase text-xs opacity-60">Compte</h6>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors" href="logout.php">
                        <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <i class="fas fa-sign-out-alt text-slate-700"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        
        <!-- Navbar -->
        <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start">
            <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
                <nav>
                    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                        <li class="text-sm leading-normal">
                            <a class="opacity-50 text-slate-700" href="posts.php">Articles</a>
                        </li>
                        <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']">
                            Ajouter
                        </li>
                    </ol>
                    <h6 class="mb-0 font-bold capitalize">Nouvel Article</h6>
                </nav>
            </div>
        </nav>

        <!-- Content -->
        <div class="w-full px-6 py-6 mx-auto">
            
            <!-- Errors -->
            <?php if(!empty($errors)): ?>
            <div class="relative p-4 mb-4 text-white bg-gradient-to-tl from-red-600 to-rose-400 rounded-lg">
                <strong>Erreurs:</strong>
                <ul class="mt-2">
                    <?php foreach($errors as $error): ?>
                        <li>• <?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mx-auto flex-0">
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <h6 class="mb-0">Informations de l'Article</h6>
                        </div>
                        <div class="flex-auto p-6">
                            <form method="POST">
                                
                                <!-- Titre -->
                                <div class="mb-4">
                                    <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">Titre *</label>
                                    <input type="text" name="title" 
                                           value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none" 
                                           placeholder="Titre de l'article" required />
                                </div>

                                <!-- Contenu -->
                                <div class="mb-4">
                                    <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">Contenu *</label>
                                    <textarea name="content" rows="10" 
                                              class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none" 
                                              placeholder="Contenu de l'article" required><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
                                </div>

                                <!-- Catégorie -->
                                <div class="mb-4">
                                    <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">Catégorie *</label>
                                    <select name="category_id" 
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none" required>
                                        <option value="">Choisir une catégorie</option>
                                        <?php foreach($categories as $cat): ?>
                                            <option value="<?= $cat['category_id'] ?>" 
                                                    <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">Status</label>
                                    <select name="status" 
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none">
                                        <option value="draft" <?= (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                                        <option value="published" <?= (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : '' ?>>Publié</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-to-tl from-purple-700 to-pink-500 hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                        <i class="fas fa-save mr-2"></i> Enregistrer
                                    </button>
                                    
                                    <a href="posts.php" 
                                       class="inline-block px-6 py-3 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 border-slate-700 text-slate-700 hover:scale-102">
                                        Annuler
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="../assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5" async></script>
</body>
</html>