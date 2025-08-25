# Coding Standards

این پروژه از یکسری استاندارد کدنویسی مشخص استفاده می‌کند تا کدها یکدست، خوانا و امن باشند.

---

## 🏗 نام‌گذاری

- **Classes** → PascalCase  
  مثال: `UserController`, `AdminBookingController`, `VisitStatus`

- **Methods** → camelCase  
  مثال: `updateUser()`, `checkCsrf()`, `getRoleTitle()`

- **Variables** → camelCase  
  مثال: `$userId`, `$firstName`, `$isActive`

- **Helper Functions (Global)** → snake_case  
  مثال: `csrf_field()`, `check_csrf()`, `flash()`, `lang()`

- **Constants** → UPPER_CASE  
  مثال: `BASE_URL`, `APP_LANG`, `APP_DIRECTION`

---

## 📂 ساختار پوشه‌ها

- Controllers → `app/Controllers/*`
- Models → `app/Models/*`
- Views → `app/Views/*`
- Middlewares → `app/Middlewares/*`
- Helpers → `app/Helpers/*`

---

## 📝 کدنویسی PHP

- در صورت امکان از strict typing استفاده شود:
  ```php
  declare(strict_types=1);
