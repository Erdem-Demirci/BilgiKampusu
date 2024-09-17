<?php
session_start();
require_once "baglanti.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $postId = $_POST['postId'];
    $type = $_POST['type'];
    $username = $_SESSION['username'];

    // Kullanıcının daha önce bu öğeye oy verip vermediğini kontrol et
    $query = "SELECT * FROM user_votes WHERE user_id = (SELECT id FROM kullanicilar WHERE kullanici_adi = ?) AND post_id = ?";
    $stmt = $baglanti->prepare($query);
    $stmt->execute([$username, $postId]);
    $existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingVote) {
        // Kullanıcı daha önce oy kullanmamışsa, oy kullanmaya izin ver
        $insertQuery = "INSERT INTO user_votes (user_id, post_id) VALUES ((SELECT id FROM kullanicilar WHERE kullanici_adi = ?), ?)";
        $stmt = $baglanti->prepare($insertQuery);
        $stmt->execute([$username, $postId]);

        // İlgili tablodaki oy sayısını güncelle
        $updateQuery = "UPDATE uni_sorular SET " . ($type === 'up' ? 'upvotes' : 'downvotes') . " = " . ($type === 'up' ? 'upvotes' : 'downvotes') . " + 1 WHERE soru_id = ?";
        $stmt = $baglanti->prepare($updateQuery);
        $stmt->execute([$postId]);

        // Güncellenmiş oy sayısını geri döndür
        $query = "SELECT upvotes, downvotes FROM uni_sorular WHERE soru_id = ?";
        $stmt = $baglanti->prepare($query);
        $stmt->execute([$postId]);
        $votes = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success',
            'upvotes' => $votes['upvotes'],
            'downvotes' => $votes['downvotes']
        ]);

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Zaten oy verdiniz.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Oy kullanmak için giriş yapmalısınız.'
    ]);
}
?>
