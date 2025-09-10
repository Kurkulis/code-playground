<?php
class Db {
  public static function pdo(array $cfg): PDO {
    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['name']};charset={$cfg['charset']}";
    $opt = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    return new PDO($dsn, $cfg['user'], $cfg['pass'], $opt);
  }
}


// apibrėžia klasę Db, kurioje yra statinis metodas pdo().

// iškvietus Db::pdo($cfg), paima konfigūracijos masyvą ($cfg), 
// iš jo sudaro DSN eilutę (su visa info kad PHP galetu prisijungt prie DB per PDO)
// su host, port, duomenų bazės pavadinimu ir koduote,
// paruošia parinktis ($opt) kaip tvarkyti klaidas ir kokiu formatu grąžinti 
// duomenis, ir galiausiai sukuria bei grąžina PDO objektą – aktyvų 
// prisijungimą prie MySQL duomenų bazės.

