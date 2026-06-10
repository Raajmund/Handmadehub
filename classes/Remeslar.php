<?php
// ============================================
// classes/Remeslar.php
// OOP trieda pre entitu Remeselník – CRUD operácie
// ============================================

class Remeslar
{
    private PDO $db;

    // Konštruktor prijme databázové spojenie (Dependency Injection)
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // -------------------------------------------------------
    // READ – čítanie záznamov
    // -------------------------------------------------------

    /**
     * Vráti všetkých remeselníkov (zoradených podľa mena).
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM remeslari ORDER BY meno ASC');
        return $stmt->fetchAll();
    }

    /**
     * Vráti remeselníkov podľa odboru.
     * Použitie: $model->getByOdbor('Hrnčiarstvo')
     */
    public function getByOdbor(string $odbor): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM remeslari WHERE odbor = :odbor ORDER BY meno ASC'
        );
        $stmt->execute([':odbor' => $odbor]);
        return $stmt->fetchAll();
    }

    /**
     * Vráti jedného remeselníka podľa jeho ID.
     * Vráti false ak neexistuje.
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM remeslari WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Vyhľadávanie – hľadá v mene, meste alebo odbore.
     */
    public function search(string $query): array
    {
      if (trim($query) === '') {
      return $this->getAll();
    }
    
      $like = '%' . $query . '%';
      $stmt = $this->db->prepare(
        'SELECT * FROM remeslari
         WHERE meno LIKE :q1 OR mesto LIKE :q2 OR odbor LIKE :q3
         ORDER BY meno ASC'
    );
    $stmt->execute([':q1' => $like, ':q2' => $like, ':q3' => $like]);
    return $stmt->fetchAll() ?: [];
    }

    /**
     * Vráti zoznam unikátnych odborov (pre filter/dropdown).
     */
    public function getOdbory(): array
    {
        $stmt = $this->db->query(
            'SELECT DISTINCT odbor FROM remeslari ORDER BY odbor ASC'
        );
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // -------------------------------------------------------
    // CREATE – pridanie záznamu
    // -------------------------------------------------------

    /**
     * Pridá nového remeselníka.
     * $data = asociatívne pole z formulára.
     * Vráti ID nového záznamu.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO remeslari (meno, email, telefon, mesto, odbor, popis, fotka)
             VALUES (:meno, :email, :telefon, :mesto, :odbor, :popis, :fotka)'
        );
        $stmt->execute([
            ':meno'    => $this->sanitize($data['meno']),
            ':email'   => $this->sanitize($data['email']),
            ':telefon' => $this->sanitize($data['telefon'] ?? ''),
            ':mesto'   => $this->sanitize($data['mesto']),
            ':odbor'   => $this->sanitize($data['odbor']),
            ':popis'   => $this->sanitize($data['popis'] ?? ''),
            ':fotka'   => $data['fotka'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // -------------------------------------------------------
    // UPDATE – úprava záznamu
    // -------------------------------------------------------

    /**
     * Aktualizuje existujúceho remeselníka.
     * Vráti počet ovplyvnených riadkov (1 = úspech, 0 = nič sa nezmenilo).
     */
    public function update(int $id, array $data): int
    {
        // Ak je nahraná nová fotka, aktualizuj aj stĺpec fotka
        $fotkaSQL = isset($data['fotka']) ? ', fotka = :fotka' : '';

        $stmt = $this->db->prepare(
            "UPDATE remeslari
             SET meno    = :meno,
                 email   = :email,
                 telefon = :telefon,
                 mesto   = :mesto,
                 odbor   = :odbor,
                 popis   = :popis
                 {$fotkaSQL}
             WHERE id = :id"
        );

        $params = [
            ':meno'    => $this->sanitize($data['meno']),
            ':email'   => $this->sanitize($data['email']),
            ':telefon' => $this->sanitize($data['telefon'] ?? ''),
            ':mesto'   => $this->sanitize($data['mesto']),
            ':odbor'   => $this->sanitize($data['odbor']),
            ':popis'   => $this->sanitize($data['popis'] ?? ''),
            ':id'      => $id,
        ];

        if (isset($data['fotka'])) {
            $params[':fotka'] = $data['fotka'];
        }

        $stmt->execute($params);
        return $stmt->rowCount();
    }

    // -------------------------------------------------------
    // DELETE – zmazanie záznamu
    // -------------------------------------------------------

    /**
     * Zmaže remeselníka podľa ID.
     * Vráti jeho fotku (názov súboru) pre prípadné zmazanie z disku.
     */
    public function delete(int $id): string|null
    {
        // Najprv načítaj fotku, aby sme ju mohli zmazať z disku
        $remeslar = $this->getById($id);
        $fotka    = $remeslar ? $remeslar['fotka'] : null;

        $stmt = $this->db->prepare('DELETE FROM remeslari WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $fotka;
    }

    // -------------------------------------------------------
    // Pomocná metóda
    // -------------------------------------------------------

    /**
     * Základné čistenie vstupu – odstráni prebytočné medzery.
     * PDO prepared statements chránia pred SQL injection automaticky.
     */
    private function sanitize(string $value): string
    {
        return trim(strip_tags($value));
    }
}
