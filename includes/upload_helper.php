<?php
function handle_upload($field = 'cover'){
    // لا يوجد ملف مرفوع
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    // خطأ أثناء الرفع
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        die('خطأ في رفع الملف، كود: ' . $_FILES[$field]['error']);
    }

    // مسار مجلد الرفع الصحيح باستخدام _DIR_
    $uploadDir = _DIR_ . '/../assets/uploads/';

    // إنشاء المجلد إن لم يكن موجودًا
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die('تعذر إنشاء مجلد الرفع: ' . $uploadDir);
        }
    }

    // التأكد من قابلية الكتابة
    if (!is_writable($uploadDir)) {
        die('مجلد الرفع غير قابل للكتابة: ' . $uploadDir);
    }

    // السماح بالأنواع التالية فقط
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($_FILES[$field]['type'], $allowed)) {
        die('نوع الملف غير مسموح. المسموح: JPG, PNG, WEBP');
    }

    // اسم ملف فريد
    $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
    $newName = 'cover_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;

    // مسار الحفظ الفعلي على القرص
    $destFs = $uploadDir . $newName;

    // نقل الملف من المؤقت إلى مجلد الرفع
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $destFs)) {
        die('فشل في نقل الملف إلى: ' . $destFs);
    }

    // المسار الذي نخزّنه في قاعدة البيانات
    return 'assets/uploads/' . $newName;
}
?>