<h2>Liste des défis</h2>

<a href="index.php?action=create_challenge">➕ Créer un nouveau défi</a>
<hr>

<?php if (!empty($challenges)): ?>

    <?php foreach ($challenges as $c): ?>
        
        <h3><?= htmlspecialchars($c['title']) ?></h3>
        <p><?= htmlspecialchars($c['description']) ?></p>
        <small>
            Catégorie: <?= htmlspecialchars($c['category']) ?> |
            Date limite: <?= $c['deadline'] ?> |
            Par: <?= htmlspecialchars($c['name']) ?>
        </small>
        <br>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $c['user_id']): ?>

            <a href="index.php?action=edit_challenge&id=<?= $c['id'] ?>">✏️ Modifier</a>

            <a href="index.php?action=delete_challenge&id=<?= $c['id'] ?>" 
               onclick="return confirm('Supprimer ce défi ?')">
               🗑️ Supprimer
            </a>

        <?php endif; ?>

        <hr>

    <?php endforeach; ?>

<?php else: ?>
    <p>Aucun défi pour le moment.</p>
<?php endif; ?>