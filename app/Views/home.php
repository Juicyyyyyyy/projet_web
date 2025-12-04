<?php include __DIR__ . '/components/header.php'; ?>

<h1><?= $title ?></h1>
<p>Welcome to the application!</p>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>.</p>
<?php else: ?>
    <p>Please <a href="/login">login</a> or <a href="/register">register</a>.</p>
<?php endif; ?>

<?php include __DIR__ . '/components/footer.php'; ?>
