<?php
require 'config.php';
include 'includes/header.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM books";
$params = [];
if($keyword !== ''){
    $sql .= " WHERE title LIKE :kw OR author LIKE :kw OR category LIKE :kw";
    $params[':kw'] = "%$keyword%";
}
$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>
<div class="card">
  <h2>قائمة الكتب</h2>
  <form class="searchbar" method="get">
    <input class="input" type="text" name="q" placeholder="ابحث بالعنوان أو المؤلف أو التصنيف..." value="<?php echo htmlspecialchars($keyword); ?>">
    <button class="btn" style="cursor:pointer" type="submit">بحث</button>
    <a class="btn" href="book_add.php">+ إضافة كتاب</a>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>الغلاف</th>
        <th>العنوان</th>
        <th>المؤلف</th>
        <th>السنة</th>
        <th>التصنيف</th>
        <th>إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($books as $b): ?>
      <tr>
        <td><?php echo $b['id']; ?></td>
        <td><?php if($b['cover']) echo '<img class="cover" src="'.htmlspecialchars($b['cover']).'" alt="غلاف">'; ?></td>
        <td><?php echo htmlspecialchars($b['title']); ?></td>
        <td><?php echo htmlspecialchars($b['author']); ?></td>
        <td><?php echo htmlspecialchars($b['publish_year']); ?></td>
        <td><?php echo htmlspecialchars($b['category']); ?></td>
        <td class="actions">
          <a class="btn warning" href="book_edit.php?id=<?php echo $b['id']; ?>">تعديل</a>
          <a class="btn danger" href="book_delete.php?id=<?php echo $b['id']; ?>" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(empty($books)): ?>
      <tr><td colspan="7">لا توجد بيانات.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
