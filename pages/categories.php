<?php 
session_start();
include '../includes/db.php';
$current_page = 'categories';
// 1. Page Protection
if(empty($_SESSION['user_id'])){
    header('location:login.php');
    exit;
}
$role = $_SESSION['role'] ?? 'guest';
if($role !== 'admin') {
    header('location:../index.php');
    exit;
}

$username = $_SESSION['username'] ?? 'Admin';

// 2. Add Category Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $cat_name = $_POST['cat_name'];
    $cat_desc = $_POST['cat_desc'];
    
    if(!empty($cat_name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$cat_name, $cat_desc]);
        header("Location: categories.php?success=1");
        exit();
    }
}

// 3. Delete Category Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php?deleted=1");
    exit();
}
if(isset($_GET['success'])) showMessage('success', 'Category added successfully!');
if(isset($_GET['deleted'])) showMessage('success', 'Category deleted successfully!');

// 4. Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY category_id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <title>Manage Categories - Soft UI Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5" rel="stylesheet" />
</head>

<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <?php include 'sidebar.php'; ?>
    <aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
        <div class="h-19.5 px-8 py-6">
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Soft UI Dashboard</span>
        </div>
        <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
        <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
            <ul class="flex flex-col pl-0 mb-0">
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors" href="dashboard.php">
                        <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5 text-slate-700">
                           <i class="fas fa-home"></i>
                        </div>
                        <span class="ml-1">Dashboard</span>
                    </a>
                </li>
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-white px-4 font-semibold text-slate-700 transition-colors" href="categories.php">
                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5 text-white">
                            <i class="fas fa-list"></i>
                        </div>
                        <span class="ml-1">Categories</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 mt-4 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main>
            <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
                <nav>
                    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                        <li class="text-sm leading-normal opacity-50 text-slate-700">Pages</li>
                        <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:content-['/']">Categories</li>
                    </ol>
                    <h6 class="mb-0 font-bold capitalize">Manage Categories</h6>
                </nav>
                <div class="flex items-center mt-2 grow sm:mt-0 md:mr-0 lg:flex lg:basis-auto">
                    <div class="flex items-center md:ml-auto md:pr-4">
                        <span class="px-4 font-semibold text-sm">Welcome, <?php echo htmlspecialchars($username); ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="w-full px-6 py-6 mx-auto">
            
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6">
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <h6>Add New Category</h6>
                        </div>
                        <div class="flex-auto p-6">
                            <form role="form" method="POST" class="flex flex-wrap gap-4 items-end">
                                <div class="w-full md:w-1/4">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Category Name</label>
                                    <input type="text" name="cat_name" class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="e.g. Technology" required />
                                </div>
                                <div class="w-full md:w-1/3">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Description (Optional)</label>
                                    <input type="text" name="cat_desc" class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Short description..." />
                                </div>
                                <div class="w-full md:w-auto">
                                    <button type="submit" name="add_category" class="inline-block px-6 py-2.5 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-purple-700 to-pink-500 hover:scale-102 active:opacity-85">Add Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3">
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <h6>Categories Table</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">ID</th>
                                            <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Name</th>
                                            <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($categories)): ?>
                                            <tr><td colspan="4" class="text-center p-4 text-sm text-slate-400">No categories found.</td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent px-6 text-xs font-semibold"><?php echo $cat['category_id']; ?></td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <h6 class="mb-0 text-sm leading-normal"><?php echo htmlspecialchars($cat['name']); ?></h6>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent text-xs"><?php echo htmlspecialchars($cat['description']); ?></td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <a href="edit_category.php?id=<?php echo $cat['category_id']; ?>" class="text-xs font-semibold leading-tight text-slate-400 px-2 hover:text-purple-700"> Edit </a>
                                                <a href="categories.php?delete=<?php echo $cat['category_id']; ?>" class="text-xs font-semibold leading-tight text-red-500 px-2" onclick="return confirm('Are you sure you want to delete this category?')"> Delete </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
