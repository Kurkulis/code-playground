<?php declare(strict_types=1);

//prisijungimai prie kitu .php failų
$config = require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/Db.php';
require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/validate.php';

//Front ir Back bendravimo taisyklės
cors($config['allowed_origins']);

//tikrina ar POST metodas
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  json_response(405, ['ok' => false, 'error' => 'Method Not Allowed']);
}

//validuoja ir valo įvestus duomenis
[$errors, $clean] = validate_input($_POST);
if ($errors) {
  json_response(422, ['ok' => false, 'errors' => $errors]);
}

//paimami extra duomenis
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

try {
  //sukuria PDO prisijungimą prie DB
  $pdo = Db::pdo($config['db']);
  //paruošia SQL užklausą
  $stmt = $pdo->prepare(
    'INSERT INTO registrations (name, phone, email, rooms, ip, user_agent, created_at)
     VALUES (?,?,?,?,?,?,NOW())'
  );
  //įvykdo SQL užklausą 
  $stmt->execute([
    $clean['name'], $clean['phone'], $clean['email'], $clean['rooms'], $ip, $ua
  ]);

  json_response(200, ['ok' => true]);
} catch (Throwable $e) {
  // laikinai parodyk tikslią priežastį, kad galėtum pataisyti
  json_response(500, ['ok' => false, 'error' => $e->getMessage()]);
}
