<?php

function getHari($date)
{
	$hari = array(
		1 => 'Senin',
		'Selasa',
		'Rabu',
		'Kamis',
		'Jumat',
		'Sabtu',
		'Minggu'
	);

	$num = date('N', strtotime($date));
	return $hari[$num];
}

function encode_url($string, $key = "", $url_safe = TRUE)
{
	return strtr(base64_encode($string), '+=/', '.-~');
}

function number_format_short($n, $precision = 1)
{
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'Jt';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'M';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}

	if ($precision > 0) {
		$dotzero = '.' . str_repeat('0', $precision);
		$n_format = str_replace($dotzero, '', $n_format);
	}

	return $n_format . ' ' . $suffix;
}

function viewDate($date)
{
	$date = str_replace('-', '/', $date);
	$namaBulan = array("0", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	$day = date('d', strtotime($date));
	$month = date('n', strtotime($date));
	$year = date('Y', strtotime($date));
	$bulan = $namaBulan[$month];
	return $day . ' ' . $bulan . ' ' . $year;
}

function get_random_string($length = 8)
{
	$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
	$string = '';

	for ($i = 0; $i < $length; $i++) {
		$string .= $characters[mt_rand(0, strlen($characters) - 1)];
	}

	return $string;
}

function viewNowDate()
{
	$date = date("Y-m-d H:i:s");
	$namaBulan = array("0", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	$namaHari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
	$day = date('d', strtotime($date));
	$month = date('n', strtotime($date));
	$year = date('Y', strtotime($date));
	$dayN = date('N', strtotime($date));
	$bulan = $namaBulan[$month];
	$dayName = $namaHari[$dayN];
	return $dayName . ', ' . $day . ' ' . $bulan . ' ' . $year;
}

function pr($data)
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

function getRandomStr($n = 5)
{
	return substr(bin2hex(random_bytes($n * 2)), 0, $n);
}

function slugs($text, $limit = 64)
{
	// Strip html tags
	$text = substr($text, 0, $limit);
	$text = strip_tags($text);
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);
	setlocale(LC_ALL, 'en_US.utf8');
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	$text = preg_replace('~[^-\w]+~', '', $text);
	$text = trim($text, '-');
	$text = preg_replace('~-+~', '-', $text);
	$text = strtolower($text);

	if (empty($text)) {
		return '';
	}

	return $text;
}

function formatDate($rss_date)
{
	$timestamp = strtotime($rss_date);

	// Mengonversi ke format tanggal biasa
	$formatted_date = date("d F Y", $timestamp);
	return $formatted_date;
}

function limitString($string, $limit = 100)
{
	// Remove any HTML tags
	$string = strip_tags($string);

	// Check if the string length exceeds the limit
	if (strlen($string) > $limit) {
		// Limit the string and add ellipsis
		$string = substr($string, 0, $limit) . "...";
	}

	return $string;
}

function namaWeb()
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

	// Ambil nama host (domain atau IP)
	$host = $_SERVER['HTTP_HOST'];


	// Gabungkan untuk mendapatkan URL lengkap
	$currentURL = $protocol . $host;

	return $currentURL;
}