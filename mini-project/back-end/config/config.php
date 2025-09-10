<?php
// DB konfigūracija - useris sukurtas workbench'e su visomis teisėmis

return [
  'db' => [
    'host'    => '127.0.0.1',
    'port'    => 3306,      
    'name'    => 'giluzio',
    'user'    => 'giluzio_user',
    'pass'    => 'giluzio321darbas',
    'charset' => 'utf8mb4',
  ],
  'allowed_origins' => [
    '*'
  ],
];
