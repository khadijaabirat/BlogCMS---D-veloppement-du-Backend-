<?php 
session_start();
include '../includes/db.php';
$current_page = 'users';
if(empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header('location:login.php');
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Users Management - Soft UI</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/gh/creativetimofficial/soft-ui-dashboard-tailwind@main/build/assets/css/soft-ui-dashboard-tailwind.css" rel="stylesheet" />
</head>

<body class="m-0 font-sans text-base antialiased font-normal bg-gray-50 text-slate-500">
        <?php include 'sidebar.php'; ?>

<main class="xl:ml-68.5 relative h-full max-h-screen transition-all duration-200">
        <div class="w-full px-6 py-6 mx-auto">
            
            <div class="flex flex-wrap -mx-3">
                <div class="w-full px-3 mb-6">
                    <div class="relative flex flex-col min-w-0 bg-white border-0 shadow-soft-xl rounded-2xl">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6>Users List (Total: <?php echo count($users); ?>)</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase text-xxs opacity-70">User</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase text-xxs opacity-70">Email</th>
                                            <th class="px-6 py-3 font-bold text-center uppercase text-xxs opacity-70">Role</th>
                                            <th class="px-6 py-3 font-bold text-center uppercase text-xxs opacity-70">Joined Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($users as $user): ?>
                                        <tr>
                                            <td class="p-2 px-6 align-middle bg-transparent border-b">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm leading-normal"><?php echo $user['username']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b text-sm">
                                                <?php echo $user['email']; ?>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b text-sm">
                                                <span class="px-3 py-1 font-bold text-white rounded-lg text-xxs 
                                                    <?php echo ($user['role'] == 'admin') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-gray-400'; ?>">
                                                    <?php echo strtoupper($user['role']); ?>
                                                </span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b text-sm">
                                                <?php echo date('Y-m-d', strtotime($user['created_at'])); ?>
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