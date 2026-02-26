<h2>Modifier le défi</h2>

<form method="POST" action="index.php?action=update_challenge">
    <input type="hidden" name="id" value="<?= $challenge['id'] ?>">

    <input type="text" name="title" value="<?= htmlspecialchars($challenge['title']) ?>" required><br><br>

    <textarea name="description" required><?= htmlspecialchars($challenge['description']) ?></textarea><br><br>

    <input type="text" name="category" value="<?= htmlspecialchars($challenge['category']) ?>" required><br><br>

    <input type="date" name="deadline" value="<?= $challenge['deadline'] ?>" required><br><br>

    <input type="text" name="image" value="<?= htmlspecialchars($challenge['image']) ?>"><br><br>

    <button type="submit">Mettre à jour</button>
</form>