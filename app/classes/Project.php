<?php
class Project {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function all() {
        return $this->pdo->query("SELECT * FROM projects")->fetchAll();
    }
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO projects (ad, aciklama, baslangic_tarihi, bitis_tarihi) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['ad'],
            $data['aciklama'],
            $data['baslangic_tarihi'],
            $data['bitis_tarihi']
        ]);
        return $this->pdo->lastInsertId();
    }
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE projects SET ad=?, aciklama=?, baslangic_tarihi=?, bitis_tarihi=? WHERE id=?");
        return $stmt->execute([
            $data['ad'],
            $data['aciklama'],
            $data['baslangic_tarihi'],
            $data['bitis_tarihi'],
            $id
        ]);
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM projects WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>
