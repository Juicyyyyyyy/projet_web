<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background: #f0f2f5; }
        nav { background: #004880; padding: 1rem; color: white; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; margin-left: 1rem; }
        nav a:hover { text-decoration: underline; }
        .container { padding: 2rem; }
        
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 400px; margin: 2rem auto; }
        input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.5rem; background: #004880; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #003366; }
        .error { color: red; margin-bottom: 1rem; font-size: 0.9rem; }
        .link { text-align: center; margin-top: 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <nav>
        <a href="/" style="font-weight: bold; font-size: 1.2rem;">MyApp</a>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span style="margin-right: 10px;">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
