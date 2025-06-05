<?php
class Customer {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function all() {
        return $this->pdo->query("SELECT * FROM customers")->fetchAll();
    }
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO customers (ad, soyad, email, telefon, adres) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['ad'],
            $data['soyad'],
            $data['email'],
            $data['telefon'],
            $data['adres']
        ]);
        return $this->pdo->lastInsertId();
    }
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE customers SET ad=?, soyad=?, email=?, telefon=?, adres=? WHERE id=?");
        return $stmt->execute([
            $data['ad'],
            $data['soyad'],
            $data['email'],
            $data['telefon'],
            $data['adres'],
            $id
        ]);
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM customers WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>
