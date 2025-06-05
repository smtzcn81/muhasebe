<?php
class SalesIrsaliye {
    private $pdo;
    private $table = 'sales_irsaliyeler';
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
        $stmt=$this->pdo->prepare("INSERT INTO {$this->table} (baslik, tarih, aciklama) VALUES (?,?,?)");
        $stmt->execute([
            $data['baslik'],
            $data['tarih'],
            $data['aciklama']
        ]);
        return $this->pdo->lastInsertId();
    }
    public function update($id,$data){
        $stmt=$this->pdo->prepare("UPDATE {$this->table} SET baslik=?, tarih=?, aciklama=? WHERE id=?");
        return $stmt->execute([
            $data['baslik'],
            $data['tarih'],
            $data['aciklama'],
            $id
        ]);
    }
    public function delete($id){
        $stmt=$this->pdo->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>
