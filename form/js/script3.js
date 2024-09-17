document.addEventListener('DOMContentLoaded', function() {
    // PHP kodunu buraya taşıyın ve JavaScript içinde bir değişken olarak ayarlayın
    var eslesmiyor = <?php echo isset($_GET['eslesmiyor']) && $_GET['eslesmiyor'] == 1 ? 'true' : 'false'; ?>;

    // Şimdi, PHP ile ayarlanmış olan "eslesmiyor" değişkenini kullanarak HTML öğelerini ayarlayabilirsiniz
    if (eslesmiyor) {
        document.querySelector('.login').style.display = 'none'; // Giriş formunu gizle
        document.querySelector('.register').style.display = 'block'; // Kayıt formunu göster
    }
});
