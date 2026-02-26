<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php?action=login");
    exit;
}
?>

<h2>Modifier le profil</h2>

<?php if (isset($_GET['profile_success'])): ?>
    <p style="color:green;">✅ Profil mis à jour</p>
<?php endif; ?>

<form method="POST" action="index.php?action=update_profile">
    <input type="text" name="name"
           value="<?= htmlspecialchars($_SESSION['user']['name']); ?>" required>

    <input type="email" name="email"
           value="<?= htmlspecialchars($_SESSION['user']['email']); ?>" required>

    <button type="submit">Modifier le profil</button>
</form>

<hr>

<h2>Changer le mot de passe</h2>

<?php if (isset($_GET['pwd_success'])): ?>
    <p style="color:green;">✅ Mot de passe modifié</p>
<?php endif; ?>

<?php if (isset($_GET['pwd_error'])): ?>
    <p style="color:red;">❌ Ancien mot de passe incorrect</p>
<?php endif; ?>

<form method="POST" action="index.php?action=change_password">
    <input type="password" name="old_password" placeholder="Ancien mot de passe" required>
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>

    <button type="submit">Changer le mot de passe</button>
</form>