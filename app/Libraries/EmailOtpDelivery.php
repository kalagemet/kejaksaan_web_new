<?php
namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;

class EmailOtpDelivery implements OtpDeliveryInterface
{
    public function send(string $channel, string $destination, string $code): void
    {
        if ($channel !== 'email' || !getenv('email.SMTPHost') || !getenv('email.SMTPUser')) {
            throw new \RuntimeException('Pengiriman OTP email belum dikonfigurasi.');
        }
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = (string) getenv('email.SMTPHost');
        $mail->Port = (int) (getenv('email.SMTPPort') ?: 587);
        $mail->SMTPSecure = (string) (getenv('email.SMTPCrypto') ?: 'tls');
        $mail->SMTPAuth = true;
        $mail->Username = (string) getenv('email.SMTPUser');
        $mail->Password = (string) getenv('email.SMTPPass');
        $mail->Timeout = 15;
        $mail->setFrom($mail->Username, 'Layanan Masyarakat Kejaksaan');
        $mail->addAddress($destination);
        $mail->Subject = 'Kode OTP Layanan Masyarakat';
        $mail->Body = "Kode OTP Anda: {$code}\n\nBerlaku 10 menit. Jangan berikan kode ini kepada siapa pun. Abaikan email ini jika Anda tidak mengajukan permintaan.";
        $mail->send();
    }
}
