<h2>Créer un défi</h2>

<form method="POST" action="index.php?action=store_challenge">
    <input type="text" name="title" placeholder="Titre" required><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <input type="text" name="category" placeholder="Catégorie" required><br><br>
    <input type="date" name="deadline" required><br><br>
    <input type="text" name="image" placeholder="Nom image (optionnel)"><br><br>

    <button type="submit">Créer</button>
</form>