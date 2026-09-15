<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(APP_URL) ?>/assets/css/app.css" rel="stylesheet">
</head>
<body class="login-page">
<main class="login-shell">
    <section class="login-brand">
        <div class="brand-mark"><i class="fa-solid fa-feather-pointed"></i></div>
        <p class="eyebrow">Stationery · Services · Finance</p>
        <h1>Run the counter<br><em>with clarity.</em></h1>
        <p class="brand-copy">One calm command center for stock, sales, operators and every digital transaction.</p>
        <div class="brand-stats"><span>01 <small>ERP workspace</small></span><span>24/7 <small>visibility</small></span></div>
    </section>
    <section class="login-card">
        <div class="login-card-header"><span class="status-dot"></span><span>Secure workspace</span></div>
        <h2>Welcome back</h2><p class="text-muted mb-4">Sign in to your business dashboard.</p>
        <?php if ($loginError): ?><div class="alert alert-danger small"><?= e($loginError) ?></div><?php endif; ?>
        <form method="post" action="?route=login">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <label class="form-label" for="email">Work email</label>
            <div class="input-icon mb-3"><i class="fa-regular fa-envelope"></i><input class="form-control" id="email" name="email" type="email" placeholder="you@iqrastationary.com" required autofocus></div>
            <div class="d-flex justify-content-between"><label class="form-label" for="password">Password</label><a href="#" class="form-link">Forgot password?</a></div>
            <div class="input-icon mb-4"><i class="fa-solid fa-lock"></i><input class="form-control" id="password" name="password" type="password" placeholder="Enter your password" required></div>
            <button class="btn btn-primary w-100 py-3" type="submit">Enter dashboard <i class="fa-solid fa-arrow-right ms-2"></i></button>
        </form>
        <p class="login-foot">Need access? Contact your administrator.</p>
    </section>
</main>
</body>
</html>