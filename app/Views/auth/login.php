<?php include __DIR__ . '/../components/header.php'; ?>

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

<?php include __DIR__ . '/../components/footer.php'; ?>
