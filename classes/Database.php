<?php
// ============================================
// classes/Database.php
// OOP trieda pre pripojenie k databáze (Singleton vzor)
// ============================================

class Database
{
    // Statická premenná – uchováva jedno spojenie počas celého požiadavku
    private static ?PDO $connection = null;

    // Súkromný konštruktor – zabraňuje priamemu vytvoreniu objektu
    private function __construct() {}

    /**
     * Vráti existujúce PDO spojenie, alebo vytvorí nové.
     * Použitie: $pdo = Database::getConnection();
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // DSN = Data Source Name – opisuje kam sa pripojiť
            $dsn = 'mysql:host=' . DB_HOST
                 . ';dbname='   . DB_NAME
                 . ';charset='  . DB_CHARSET;

            try {
                self::$connection = new PDO($dsn, DB_USER, DB_PASS, [
                    // Vyhodiť výnimku pri chybe – nie tichá smrť
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    // Vrátiť riadky ako asociatívne pole (napr. $row['meno'])
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Vypnúť emuláciu – bezpečnejšie prepared statements
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // V produkcii nezobrazuj chybovú správu návštevníkovi!
                die('Chyba pripojenia k databáze: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
