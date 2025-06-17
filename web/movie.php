<?php
// movie.php – detail filmu a možnost půjčení s výběrem data a kopie
require_once __DIR__ . '/config.php';

$movieId = isset($_GET['movie_id']) ? (int) $_GET['movie_id'] : 0;
if (!$movieId) {
    header('Location: index.php');
    exit;
}

// Načtení detailu filmu
$stmt = $conn->prepare(
    "SELECT m.*, g.name AS genre_name
     FROM movies m
     LEFT JOIN genres g ON m.genre_id = g.id
     WHERE m.id = ?"
);
$stmt->execute([$movieId]);
$movie = $stmt->fetch();
if (!$movie) {
    echo "Film nenalezen.";
    exit;
}

// Načtení kopií pro tento film
$stmt2 = $conn->prepare(
    "SELECT id, copy_number, status
     FROM copies
     WHERE movie_id = ?
     ORDER BY copy_number"
);
$stmt2->execute([$movieId]);
$copies = $stmt2->fetchAll();

require __DIR__ . '/htmlhead.php';
?>
<link rel="stylesheet" href="./styles/movie.css">
<div class="container">
  <div class="movie-detail">
    <div class="poster-large">
      <img src="posters/<?= $movie['id'] ?>.jpg" alt="<?= htmlspecialchars($movie['title']) ?>">
    </div>
    <div class="info">
      <h2><?= htmlspecialchars($movie['title']) ?> (<?= htmlspecialchars($movie['release_year']) ?>)</h2>
      <p><strong>Žánr:</strong> <?= htmlspecialchars($movie['genre_name']) ?></p>
      <p><strong>Popis:</strong> <?= nl2br(htmlspecialchars($movie['description'])) ?></p>
      <p><strong>Cena za den:</strong> <?= htmlspecialchars($movie['rental_rate']) ?> Kč</p>

      <?php if (empty($_SESSION['user_id'])): ?>
        <p>Pro půjčení se prosím <a href="index.php?action=login">přihlaste</a>.</p>
      <?php else: ?>
        <form method="post" action="index.php?action=rent" class="rent-form">
          <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
          <label for="copy_id">Vyberte kopii:</label>
          <select name="copy_id" id="copy_id" required>
            <?php foreach ($copies as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $c['status'] !== 'dostupné' ? 'disabled' : '' ?>>
                <?= htmlspecialchars($c['copy_number']) ?>
                (<?= htmlspecialchars($c['status']) ?>)
              </option>
            <?php endforeach; ?>
          </select>

          <label for="due_date">Datum vrácení:</label>
          <input type="date" id="due_date" name="due_date"
                 min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                 required>
         <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
               
          <button type="submit" class="btn btn-primary">Půjčit film</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/htmlfooter.php';
