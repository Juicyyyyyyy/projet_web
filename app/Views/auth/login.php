<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f2f5; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.5rem; background: #004880; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #003366; }
        .error { color: red; margin-bottom: 1rem; font-size: 0.9rem; }
        .link { text-align: center; margin-top: 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align: center; color: #004880;">Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <div class="link">
            <a href="/register">Create an account</a>
        </div>
    </div>
</body>
</html>
