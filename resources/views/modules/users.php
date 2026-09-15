<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management | <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/app.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/modules.css" rel="stylesheet">
</head>
<body class="app-body">
<div style="display: flex; min-height: 100vh;">

<?php if (!empty($error)): ?>
    <div style="background-color: #f8d7da; color: #842029; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7; font-weight: 500;"><?= e($error) ?></div>
<?php elseif (!empty($flash)): ?>
    <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc; font-weight: 500;"><?= e($flash) ?></div>
<?php endif; ?>
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" style="flex: 1; padding: 30px; box-sizing: border-box;">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.5px;">Administration</span>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 2px 0;"><i class="fa-solid fa-users" style="margin-right: 8px; color: #2563eb;"></i>User Management</h1>
                <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Manage system users and permissions</p>
            </div>
            <a href="<?= e(APP_URL) ?>" style="background: #f1f5f9; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
        </header>

        <!-- Add/Edit User Section -->
        <section style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 30px; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">Administration</span>
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;"><?= !empty($editUser) ? 'Edit user' : 'Add new user' ?></h2>
                </div>
                <?php if (!empty($editUser)): ?>
                    <a href="?route=users" style="font-size: 0.85rem; color: #64748b; text-decoration: none; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 6px;"><i class="fa-solid fa-times"></i> Cancel</a>
                <?php endif; ?>
            </div>

            <form method="post" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <?php if (!empty($editUser)): ?>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" value="<?= $editUser['id'] ?>">
                <?php endif; ?>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Full name</label>
                    <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="full_name" required placeholder="e.g. John Doe" value="<?= e($editUser['full_name'] ?? '') ?>">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Email</label>
                    <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="email" type="email" required placeholder="e.g. john@example.com" value="<?= e($editUser['email'] ?? '') ?>">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Phone</label>
                    <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="phone" placeholder="e.g. 01700000000" value="<?= e($editUser['phone'] ?? '') ?>">
                </div>
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Role</label>
                    <select style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; background: white; box-sizing: border-box;" name="role_id" required>
                        <?php foreach ($roles ?? [] as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= (($editUser['role_id'] ?? '') == $role['id']) ? 'selected' : '' ?>>
                                <?= e($role['name']) ?> - <?= e($role['description'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Password <?= !empty($editUser) ? '<span style="color: #64748b; font-weight: normal; font-size: 0.8rem;">(Leave blank to keep current)</span>' : '' ?></label>
                    <input style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box;" name="password" type="password" <?= !empty($editUser) ? '' : 'required' ?> placeholder="<?= !empty($editUser) ? '••••••••' : 'Enter password' ?>">
                </div>
                <div style="grid-column: span 2; margin-top: 10px;">
                    <button style="width: 100%; background: #2563eb; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                        <i class="fa-solid fa-<?= !empty($editUser) ? 'save' : 'user-plus' ?>" style="margin-right: 6px;"></i> 
                        <?= !empty($editUser) ? 'Update user' : 'Add user' ?>
                    </button>
                </div>
            </form>
        </section>

        <!-- User List Section (Scrollable for all users) -->
        <section style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <span style="font-size: 0.75rem; font-weight: 600; color: #2563eb; text-transform: uppercase;">System users</span>
                <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">All users</h2>
            </div>
            
            <!-- Added max-height and overflow-y so all users are accessible via scrolling -->
            <div style="max-height: 450px; overflow-y: auto; overflow-x: auto; border: 1px solid #f1f5f9; border-radius: 8px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                    <thead style="position: sticky; top: 0; background: #f8fafc; z-index: 10;">
                        <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                            <th style="padding: 12px; font-weight: 600;">User</th>
                            <th style="padding: 12px; font-weight: 600;">Email</th>
                            <th style="padding: 12px; font-weight: 600;">Phone</th>
                            <th style="padding: 12px; font-weight: 600;">Role</th>
                            <th style="padding: 12px; font-weight: 600;">Status</th>
                            <th style="padding: 12px; font-weight: 600;">Last login</th>
                            <th style="padding: 12px; font-weight: 600; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">No users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                    <td style="padding: 12px; font-weight: 600; color: #1e293b;"><?= e($user['full_name']) ?></td>
                                    <td style="padding: 12px; color: #475569;"><?= e($user['email']) ?></td>
                                    <td style="padding: 12px; color: #475569;"><?= e($user['phone'] ?? 'N/A') ?></td>
                                    <td style="padding: 12px;"><span style="background: #e2e8f0; color: #334155; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;"><?= e($user['role_name']) ?></span></td>
                                    <td style="padding: 12px;">
                                        <span style="background: <?= !empty($user['is_active']) ? '#d1e7dd; color: #0f5132' : '#f8d7da; color: #842029' ?>; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                            <?= !empty($user['is_active']) ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; color: #64748b;"><?= !empty($user['last_login_at']) ? date('d M Y', strtotime($user['last_login_at'])) : 'Never' ?></td>
                                    <td style="padding: 12px; text-align: right;">
                                        <div style="display: inline-flex; gap: 6px;">
                                            <a href="?route=users&edit=<?= $user['id'] ?>" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 6px 10px; border-radius: 6px; text-decoration: none;" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form method="post" style="display: inline; margin: 0;">
                                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" style="background: <?= !empty($user['is_active']) ? '#fffbeb; color: #d97706; border-color: #fde68a' : '#ecfdf5; color: #059669; border-color: #a7f3d0' ?>; border: 1px solid; padding: 6px 10px; border-radius: 6px; cursor: pointer;" title="<?= !empty($user['is_active']) ? 'Deactivate' : 'Activate' ?>">
                                                    <i class="fa-solid fa-<?= !empty($user['is_active']) ? 'ban' : 'check' ?>"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer style="text-align: center; padding: 30px 0; color: #64748b; font-size: 0.85rem;">
            <span>© <?= date('Y') ?> Iqra Stationary Solutions</span> &bull; 
            <span>System status <i class="fa-solid fa-circle" style="color: #10b981; font-size: 0.5rem; vertical-align: middle;"></i> All systems operational</span>
        </footer>
    </main>
</div>

</body>
</html>