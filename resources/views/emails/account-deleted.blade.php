<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار بحذف الحساب</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; direction: rtl; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #0d1b4b; }
        .content { color: #333333; line-height: 1.6; text-align: right; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eeeeee; font-size: 12px; color: #777777; text-align: center; }
        .highlight { color: #d93025; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">منصة محلي</div>
        </div>
        <div class="content">
            <p>عزيزي {{ $name }}،</p>
            <p>نحيطك علماً بأنه قد تم <span class="highlight">حذف حسابك</span> على منصة محلي.</p>
            <p>يأتي هذا الإجراء نتيجة مخالفة شروط الاستخدام والسياسات الخاصة بالمنصة.</p>
            <p>بناءً على هذا القرار، لن تتمكن من التسجيل مجدداً باستخدام هذا البريد الإلكتروني.</p>
            <p>إذا كان لديك أي استفسار، يرجى التواصل مع الدعم الفني.</p>
        </div>
        <div class="footer">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} منصة محلي للتجارة الإلكترونية
        </div>
    </div>
</body>
</html>
