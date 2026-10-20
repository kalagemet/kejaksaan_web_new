<?php
function waktu_layanan(?string $value): string
{
    if (!$value) { return '-'; }
    return (new DateTimeImmutable($value, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('d/m/Y H:i') . ' WIB';
}
