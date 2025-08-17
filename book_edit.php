<?php
require 'config.php';
require 'includes/upload_helper.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id=:id");
$stmt->execute([':id'=>$id]);
$book = $stmt->fetch();
if(!$book){ die("لم يتم العثور على الكتاب."); }

$message = null; $err = null;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year = intval($_POST['publish_year'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $newCover = handle_upload('cover');
    $coverPath = $newCover ? $newCover : $book['cover'];

    if($title === '' || $author === ''){
        $err = "الرجاء إدخال العنوان والمؤلف.";
    } else {
        $stmt = $pdo->prepare("UPDATE books SET title=:t, author=:a, publish_year=:y, category=:c, cover=:cv WHERE id=:id");
        $stmt->execute([':t'=>$title, ':a'=>$author, ':y'=>$year, ':c'=>$category, ':cv'=>$coverPath, ':id'=>$id]);
        $message = "تم تحديث بيانات الكتاب.";
        // تحديث $book للعرض
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        $book = $stmt->fetch();
    }
}

include 'includes/header.php';
?>
<div class="card">
  <h2>تعديل كتاب</h2>
  <?php if($message): ?><div class="alert"><?php echo $message; ?></div><?php endif; ?>
  <?php if($err): ?><div class="alert error"><?php echo $err; ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>العنوان</label>
    <input class="input" type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
    <label>المؤلف</label>
    <input class="input" type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
    <label>سنة النشر</label>
    <input class="input" type="number" name="publish_year" value="<?php echo htmlspecialchars($book['publish_year']); ?>" min="0" max="2100">
    <label>التصنيف</label>
    <input class="input" type="text" name="category" value="<?php echo htmlspecialchars($book['category']); ?>">
    <label>غلاف الكتاب (اختياري)</label>
    <?php if($book['cover']): ?><p><img class="cover" src="<?php echo htmlspecialchars($book['cover']); ?>" alt="غلاف"></p><?php endif; ?>
    <input class="input" type="file" name="cover" accept="image/*">
    <div class="actions" style="margin-top:12px">
      <button class="btn primary" style="cursor:pointer" type="submit">تحديث</button>
      <a class="btn" href="books_list.php">رجوع</a>
    </div>
  </form>
</div>
