<?php
// ============================================
// classes/Auth.php
// OOP trieda pre autentifikáciu admina
// ============================================

class Auth
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Pokúsi sa prihlásiť admina.
     * Vráti true ak úspešné, false ak zlé meno/heslo.
     */
    public function login(string $username, string $password): bool
    {
        // Načítaj admina z databázy
        $stmt = $this->db->prepare(
            'SELECT * FROM admins WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        // Skontroluj či existuje a či sa heslo zhoduje s bcrypt hashom
        if ($admin && password_verify($password, $admin['password'])) {
            // Obnov session ID – ochrana pred session fixation útokom
            session_regenerate_id(true);

            // Ulož info do session
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_login']    = time();

            return true;
        }

        return false;
    }

    /**
     * Odhlási admina – zruší session.
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Skontroluje či je admin prihlásený.
     * Použitie: if (!$auth->isLoggedIn()) { redirect na login }
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
    }

    /**
     * Presmeruje na login stránku ak nie je prihlásený.
     * Volaj na začiatku každej admin stránky.
     */
    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/admin/login.php');
            exit;
        }
    }

    /**
     * Zmení heslo admina (bcrypt hash).
     */
    public function changePassword(int $adminId, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'UPDATE admins SET password = :pass WHERE id = :id'
        );
        $stmt->execute([':pass' => $hash, ':id' => $adminId]);
        return $stmt->rowCount() > 0;
    }
}
