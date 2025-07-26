<?php

    require_once 'config.php';

    function create($data){
        $pdo = getPDO();
    
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);
    
        $sql = "INSERT INTO orders (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute($data);
    }

    function read(){
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM orders");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function update($data){
        $pdo = getPDO();

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => $col . ' = :' . $col, $columns);

        $sql = "UPDATE orders SET " . implode(', ', $placeholders) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->execute($data);
    }

    function delete($id){
        $pdo = getPDO();
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

?>