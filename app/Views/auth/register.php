<?php include __DIR__ . '/../components/header.php'; ?>

<div class="card">
    <h2 style="text-align: center; color: #004880;">Register</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="/register">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
    </form>
    <div class="link">
        <a href="/login">Already have an account?</a>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
