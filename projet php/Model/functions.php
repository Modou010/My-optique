<?php

function getGenreById($pdo, $id){
    $req = "SELECT genre FROM lunettes WHERE id_lunettes = :id";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getLunetteByid($pdo, $id){
    $req = "SELECT * FROM lunettes WHERE id_lunettes = :id";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


