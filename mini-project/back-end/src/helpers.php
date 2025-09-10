<?php

// cors() - nustato taisykeles, leidžiančias kito domeno puslapiams siųsti užklausas
function cors(array $allowedOrigins): void {
  $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
  if ($origin && (in_array($origin, $allowedOrigins, true)
      || preg_match('~^http://localhost(:\d+)?$~', $origin)
      || preg_match('~^http://127\.0\.0\.1(:\d+)?$~', $origin))) {
    header("Access-Control-Allow-Origin: $origin");
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
  }
  if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
  }
}

// json_response() - nusiunčia JSON atsakymą su antrašte ir statuso kodu
function json_response(int $status, array $payload): void {
  header('Content-Type: application/json; charset=utf-8');
  http_response_code($status);
  echo json_encode($payload, JSON_UNESCAPED_UNICODE);
  exit;
}

// sanitize() - pašalina nereikalingus tarpus iš pradžios, pabaigos ir vidurio
function sanitize(string $s): string {
  $s = trim($s);
  return preg_replace('/\s+/', ' ', $s);
}
