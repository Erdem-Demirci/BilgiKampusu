document.addEventListener('DOMContentLoaded', function() {
    // Kayıt ol bağlantısına tıklandığında
    document.getElementById('registerLink').addEventListener('click', function(event) {
        event.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
        document.querySelector('.login').style.display = 'none'; // Giriş formunu gizle
        document.querySelector('.register').style.display = 'block'; // Kayıt formunu göster
    });

    // Giriş yap bağlantısına tıklandığında
    document.getElementById('loginLink').addEventListener('click', function(event) {
        event.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
        document.querySelector('.login').style.display = 'block'; // Giriş formunu göster
        document.querySelector('.register').style.display = 'none'; // Kayıt formunu gizle
    });

});


