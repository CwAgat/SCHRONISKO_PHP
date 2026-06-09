<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */

// oznaczenie przeczytanej
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg_id'])) {
    $id = (int)$_POST['msg_id'];
    $conn->query("UPDATE contact_messages SET is_read = 1 WHERE id = $id");
    header("Location: admin-kontakt");
    exit();
}

// usun wiadomość
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    header("Location: admin-kontakt");
    exit();
}

$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $result->fetch_all(MYSQLI_ASSOC);

$unread = array_filter($messages, fn($m) => !$m['is_read']);
?>

<section class="admin-kontakt">
    <h1>Wiadomości kontaktowe
        <?php if (count($unread) > 0): ?>
            <span class="badge-unread"><?= count($unread) ?> nowych</span>
        <?php endif; ?>
    </h1>

    <?php if (empty($messages)): ?>
        <p class="msg-empty">Brak wiadomości.</p>
    <?php else: ?>
        <div class="messages-list">
            <?php foreach ($messages as $msg): ?>
                <div class="message-card <?= $msg['is_read'] ? 'read' : 'unread' ?>">
                    <div class="message-header">
                        <div class="message-meta">
                            <strong><?= htmlspecialchars($msg['name']) ?></strong>
                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>">
                                <?= htmlspecialchars($msg['email']) ?>
                            </a>
                            <span class="message-date">
                                <?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?>
                            </span>
                        </div>
                        <div class="message-actions">
                            <?php if (!$msg['is_read']): ?>
                                <form method="POST" action="admin-kontakt">
                                    <input type="hidden" name="msg_id" value="<?= (int)$msg['id'] ?>">
                                    <button type="submit" class="btn-read">Oznacz jako przeczytane</button>
                                </form>
                            <?php else: ?>
                                <span class="badge-read">Przeczytana</span>
                            <?php endif; ?>
                            <a href="admin-kontakt?delete=<?= (int)$msg['id'] ?>"
                               class="btn-delete"
                               onclick="return confirm('Usunąć tę wiadomość?')">Usuń</a>
                        </div>
                    </div>
                    <div class="message-subject">
                        <strong>Temat:</strong> <?= htmlspecialchars($msg['subject']) ?>
                    </div>
                    <div class="message-body">
                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
