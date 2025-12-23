<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligue 1 Pronostics</title>
    <style>
        :root {
            --primary-color: #0f172a;
            --accent-color: #1e293b;
            --text-color: #ffffff;
            --highlight-color: #3b82f6;
            --hover-color: #2563eb;
            --bg-color: #f8fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: var(--bg-color);
            color: #334155;
        }

        nav {
            background: #ffffff;
            background-color: #ffffff;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e2e8f0;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.25rem;
            color: #0f172a;
            text-decoration: none;
            gap: 0.5rem;
        }

        .nav-brand svg {
            width: 24px;
            height: 24px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #0f172a;
        }

        .nav-link.active-btn {
            color: #64748b;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .logout-btn {
            text-decoration: none;
            color: #64748b;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
    </style>
    <style>
        .auth-wrapper {
            min-height: calc(100vh - 140px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 420px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            display: block;
            object-fit: contain;
        }

        .auth-title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            color: #334155;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            box-sizing: border-box;
            /* Fix width issues */
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-full {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.75rem;
            background-color: #0f172a;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 1rem;
        }

        .btn-full:hover {
            background-color: #1e293b;
        }

        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .auth-link {
            color: #0f172a;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: #3b82f6;
        }

        .auth-footer-text {
            border-top: 1px solid #e2e8f0;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            color: #64748b;
            font-size: 0.85rem;
            text-align: center;
            line-height: 1.5;
        }


        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>
    <nav>
        <a href="/" class="nav-brand">
            Ligue 1 Pronostics
        </a>

        <div class="nav-links">
            <a href="/" class="nav-link">Accueil</a>
            <a href="/groups" class="nav-link active-btn">Mes Groupes</a>
            <a href="/rankings" class="nav-link">Classements</a>
        </div>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/logout" class="logout-btn">Déconnexion</a>
            <?php else: ?>
                <a href="/login" class="nav-link">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">