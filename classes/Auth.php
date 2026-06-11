<?php
class Auth
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function login(string $username, string $password): bool
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM admins WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_login']    = time();

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
    }

    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/admin/login.php');
            exit;
        }
    }

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
