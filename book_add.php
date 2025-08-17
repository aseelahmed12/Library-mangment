<?php
require 'config.php';
require 'includes/upload_helper.php';
$message = null;
$err = null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year = intval($_POST['publish_year'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $coverPath = handle_upload('cover');

    if($title === '' || $author === ''){
        $err = "الرجاء إدخال العنوان والمؤلف.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, publish_year, category, cover, created_at) VALUES (:t,:a,:y,:c,:cv,NOW())");
        $stmt->execute([
            ':t'=>$title, ':a'=>$author, ':y'=>$year, ':c'=>$category, ':cv'=>$coverPath
        ]);
        $message = "تمت إضافة الكتاب بنجاح.";
    }
}

include 'includes/header.php';
?>
<div class="card">
  <h2>إضافة كتاب</h2>
  <?php if($message): ?><div class="alert"><?php echo $message; ?></div><?php endif; ?>
  <?php if($err): ?><div class="alert error"><?php echo $err; ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>العنوان</label>
    <input class="input" type="text" name="title" required>
    <label>المؤلف</label>
    <input class="input" type="text" name="author" required>
    <label>سنة النشر</label>
    <input class="input" type="number" name="publish_year" min="0" max="2100">
    <label>التصنيف</label>
    <input class="input" type="text" name="category" placeholder="رواية، تاريخ، علم...">
    <label>غلاف الكتاب (اختياري)</label>
    <input class="input" type="file" name="cover" accept="image/*">
    <div class="actions" style="margin-top:12px">
      <button class="btn primary" type="submit" style="cursor:pointer">حفظ</button>
      <a class="btn" href="books_list.php">رجوع</a>
    </div>
  </form>
</div>
