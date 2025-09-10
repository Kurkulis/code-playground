<?php
function validate_input(array $in): array {
  $errors = [];

  $name = sanitize($in['name'] ?? '');
  if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Neteisingas vardas.';
  }

  $email = sanitize($in['email'] ?? '');
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Neteisingas el. paštas.';
  }

  $phone = sanitize($in['phone'] ?? '');
  $phoneDigits = preg_replace('/[^\d+]/', '', $phone);
  if ($phoneDigits === '' || !preg_match('/^\+?\d{6,15}$/', $phoneDigits)) {
    $errors['phone'] = 'Neteisingas telefonas.';
  }

  $rooms = $in['rooms'] ?? [];
  if (is_string($rooms)) $rooms = [$rooms];
  $rooms = array_values(array_intersect($rooms, ['1','2','3','4']));
  if (!$rooms) {
    $errors['rooms'] = 'Pasirinkite bent vieną.';
  }

  return [$errors, [
    'name'  => $name,
    'email' => $email,
    'phone' => $phoneDigits,
    'rooms' => implode(',', $rooms),
  ]];
}
