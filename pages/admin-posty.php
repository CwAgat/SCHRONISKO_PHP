<?php
if (! isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */

$sql = "
SELECT
    id,
    title,
    short_text,
    full_text,
    image, 
    created_at
FROM posts
ORDER BY posts.created_at DESC
";
$result = $conn->query($sql);

?>


<section class="admin-posts">

    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] === 'post_added'): ?>
        <p class="msg success">Post został dodany.</p>
    <?php endif; ?>
    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] === 'post_updated'): ?>
        <p class="msg success">Post został zaktualizowany.</p>
    <?php endif; ?>
    <?php unset($_SESSION['msg']); ?>

    <div class="actions">
        <a href="admin-posty-dodaj" class="add-post">
            + Dodaj post
        </a>
    </div>

    <table>
        <tr>
            <th>Zdjęcie</th>
            <th>Tytuł</th>
            <th>Akcja</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>

                <td>
                    <img src="img/posts/<?= $row['image'] ?>">
                </td>

                <td><?= htmlspecialchars($row['title']) ?></td>


                <td>
                    <div class="actions-cell">
                        <a href="admin-posty-edytuj?id=<?= $row['id'] ?>" class="edit">
                            Edytuj
                        </a>
                        <form method="post" action="admin-posty-usun"
                            onsubmit="return confirm('Na pewno usunąć post?');">
                            <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="usun">Usuń</button>
                        </form>
                    </div>
                </td>

            </tr>
        <?php endwhile; ?>
    </table>

</section>