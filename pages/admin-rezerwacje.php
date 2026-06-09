<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-logowanie");
    exit();
}

/** @var mysqli $conn */

// obsługa akcji
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['reservation_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id && in_array($action, ['confirmed', 'cancelled'], true)) {
        $stmt = mysqli_prepare($conn, "UPDATE reservations SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $action, $id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: admin-rezerwacje");
    exit();
}

// pobieranie rezerwacji
$result = $conn->query("
    SELECT r.id, r.guest_name, r.guest_email, r.guest_phone,
           r.num_guests, r.date_from, r.date_to, r.status, r.created_at,
           ro.name AS room_name, ro.capacity
    FROM reservations r
    JOIN rooms ro ON ro.id = r.room_id
    ORDER BY
        FIELD(r.status, 'pending', 'confirmed', 'cancelled'),
        r.date_from ASC
");

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

$statusLabels = [
    'pending'   => 'Oczekująca',
    'confirmed' => 'Potwierdzona',
    'cancelled' => 'Anulowana',
];

function formatDatePL(string $date): string {
    [$y, $m, $d] = explode('-', $date);
    return "$d.$m.$y";
}
?>

<section class="admin-rezerwacje">
    <h1>Rezerwacje</h1>

    <?php if (empty($reservations)): ?>
        <p class="msg-empty">Brak rezerwacji w systemie.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Pokój</th>
                    <th>Gość</th>
                    <th>Kontakt</th>
                    <th>Osoby</th>
                    <th>Przyjazd</th>
                    <th>Wyjazd</th>
                    <th>Status</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                    <tr class="row-<?= htmlspecialchars($res['status']) ?>">
                        <td><?= htmlspecialchars($res['room_name']) ?><br>
                            <small>(<?= (int)$res['capacity'] ?>-os.)</small>
                        </td>
                        <td><?= htmlspecialchars($res['guest_name']) ?></td>
                        <td>
                            <?= htmlspecialchars($res['guest_email']) ?><br>
                            <?= htmlspecialchars($res['guest_phone']) ?>
                        </td>
                        <td><?= (int)$res['num_guests'] ?></td>
                        <td><?= formatDatePL($res['date_from']) ?></td>
                        <td><?= formatDatePL($res['date_to']) ?></td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars($res['status']) ?>">
                                <?= htmlspecialchars($statusLabels[$res['status']] ?? $res['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <?php if ($res['status'] === 'pending'): ?>
                                    <form method="POST" action="admin-rezerwacje">
                                        <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                        <input type="hidden" name="action" value="confirmed">
                                        <button type="submit" class="btn-confirm">Potwierdź</button>
                                    </form>
                                    <form method="POST" action="admin-rezerwacje"
                                          onsubmit="return confirm('Odrzucić tę rezerwację?');">
                                        <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                        <input type="hidden" name="action" value="cancelled">
                                        <button type="submit" class="btn-cancel">Odrzuć</button>
                                    </form>
                                <?php elseif ($res['status'] === 'confirmed'): ?>
                                    <form method="POST" action="admin-rezerwacje"
                                          onsubmit="return confirm('Anulować potwierdzoną rezerwację?');">
                                        <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                        <input type="hidden" name="action" value="cancelled">
                                        <button type="submit" class="btn-cancel">Anuluj</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
