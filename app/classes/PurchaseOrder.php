<?php
class PurchaseOrder {
    private $pdo;
    private $table = 'purchase_orders';
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function all(){
        return $this->pdo->query("SELECT * FROM {$this->table}")->fetchAll();
    }
    public function find($id){
        $stmt=$this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function create($data){
        $stmt=$this->pdo->prepare("INSERT INTO {$this->table} (baslik, tarih, durum, tutar) VALUES (?,?,?,?)");
        $stmt->execute([
            $data['baslik'],
            $data['tarih'],
            $data['durum'],
            $data['tutar']
        ]);
        return $this->pdo->lastInsertId();
    }
    public function update($id,$data){
        $stmt=$this->pdo->prepare("UPDATE {$this->table} SET baslik=?, tarih=?, durum=?, tutar=? WHERE id=?");
        return $stmt->execute([
            $data['baslik'],
            $data['tarih'],
            $data['durum'],
            $data['tutar'],
            $id
        ]);
    }
    public function delete($id){
        $stmt=$this->pdo->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>
