<?php
require 'config.php';
$id = intval($_GET['id'] ?? 0);
if($id>0){
    $stmt = $pdo->prepare("DELETE FROM books WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}
header("Location: books_list.php");
exit;
