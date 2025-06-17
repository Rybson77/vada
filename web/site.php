<?php
require_once __DIR__ . '/config.php';
include("htmlhead.php");
?>
<link rel="stylesheet" href="./styles/main.css">
  <!-- MAIN CONTENT -->
  <div class="container layout">
    <!-- SIDEBAR: ŽÁNRY -->
    <aside class="sidebar">
      <h2>Žánry</h2>
      <ul>
        <li><a href="index.php?">Vše</a></li>
        <?php
        // Načtení žánrů z DB
        $genres = $conn->query("SELECT id, name FROM genres ORDER BY name")->fetchAll();
        foreach ($genres as $g): ?>
          <li><a href="index.php?action=genres&genre_id=<?= $g['id'] ?>">
            <?= htmlspecialchars($g['name']) ?>
          </a></li>
        <?php endforeach; ?>
      </ul>
    </aside>

    <!-- CONTENT: Výpis filmů / vyhledávání -->
    <section class="content">
      <!-- Search bar -->
      <form method="get" action="index.php" class="search-bar">
        <input type="hidden" name="action" value="search">
        <input type="text" name="q" placeholder="Hledat film..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit">🔍</button>
      </form>

      <!-- Výpis filmů podle žánru nebo hledání -->
      <?php
      $movies = [];
      if (isset($_GET['genre_id'])) {
          $stmt = $conn->prepare("SELECT * FROM movies WHERE genre_id = ?");
          $stmt->execute([(int)$_GET['genre_id']]);
          $movies = $stmt->fetchAll();
      } elseif (!empty($_GET['q'])) {
          $stmt = $conn->prepare("SELECT * FROM movies WHERE title LIKE ?");
          $stmt->execute(['%' . $_GET['q'] . '%']);
          $movies = $stmt->fetchAll();
      } else {
          $movies = $conn->query("SELECT * FROM movies ORDER BY created_at DESC LIMIT 12")->fetchAll();
      }
      ?>

      <div class="movie-grid">
        <?php if (empty($movies)): ?>
          <p>Žádné filmy k zobrazení.</p>
        <?php else:
          foreach ($movies as $m): ?>
            <div class="movie-card">
              <div class="poster">
                <img src="posters/<?= $m['id'] ?>.jpg" alt="<?= htmlspecialchars($m['title']) ?>">
              </div>
              <h3><?= htmlspecialchars($m['title']) ?></h3>
              <p class="year"><?= htmlspecialchars($m['release_year']) ?></p>
            </div>
        <?php endforeach; endif; ?>
      </div>
    </section>
  </div>
<?php
include("htmlfooter.php");
?>

