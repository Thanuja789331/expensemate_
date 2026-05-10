# ExpenseMate — Security Documentation

## 1. Overview
ExpenseMate implements multiple layers of security to protect user data and prevent unauthorized access.

---

## 2. Security Threats & Mitigations

### 2.1 SQL Injection
**Threat:** Attacker inserts malicious SQL code into input fields.

**Mitigation:**
- Laravel Eloquent ORM used for ALL database queries
- PDO prepared statements used automatically
- User input NEVER concatenated into SQL strings

**Example:**
```php
// SAFE - Eloquent parameterized query
Expense::where('user_id', $userId)->get();
```

---

### 2.2 Cross-Site Scripting (XSS)
**Threat:** Attacker injects malicious JavaScript into pages.

**Mitigation:**
- Laravel Blade {{ }} automatically escapes all output
- User input is never rendered as raw HTML
- Content Security Policy headers implemented

**Example:**
```php
// SAFE - Blade auto-escapes
{{ $expense->note }}
```

---

### 2.3 Cross-Site Request Forgery (CSRF)
**Threat:** Attacker tricks user into submitting malicious forms.

**Mitigation:**
- Laravel CSRF middleware enabled globally
- Every form includes @csrf token
- Token verified on every POST/PUT/DELETE request

**Example:**
```html
<form method="POST">
    @csrf
</form>
```

---

### 2.4 Broken Authentication
**Threat:** Weak passwords or session hijacking.

**Mitigation:**
- Laravel Jetstream handles authentication
- Passwords hashed using bcrypt (cost factor 12)
- Two-Factor Authentication (2FA) available
- Session invalidated on logout
- Rate limiting on login (5 attempts per minute)

---

### 2.5 Broken Access Control
**Threat:** Users accessing other users' data.

**Mitigation:**
- AdminOnly middleware blocks non-admin users
- forUser() scope ensures users only see own data
- Ownership check before edit/delete operations
- Role-based access control (user/admin)

**Example:**
```php
// Ownership check
if ($expense->user_id !== auth()->id()) {
    abort(403);
}
```

---

### 2.6 Mass Assignment
**Threat:** Attacker sends extra fields to modify protected data.

**Mitigation:**
- $fillable defined on ALL models
- Only whitelisted fields can be mass assigned

**Example:**
```php
protected $fillable = [
    'user_id', 'category_id', 'type', 'amount', 'note', 'expense_date'
];
```

---

### 2.7 API Security
**Threat:** Unauthorized API access.

**Mitigation:**
- Laravel Sanctum token-based authentication
- Token required for all protected API routes
- Token revoked on logout
- Invalid tokens return 401 Unauthorized

---

### 2.8 Sensitive Data Exposure
**Threat:** Passwords or tokens exposed.

**Mitigation:**
- Passwords stored as bcrypt hashes
- .env file excluded from Git
- API tokens hashed in database
- HTTPS enforced in production

---

## 3. Security Testing

| Test | Expected Result | Status |
|------|----------------|--------|
| Access dashboard without login | Redirect to login |  Pass |
| Normal user access admin page | 403 Forbidden |  Pass |
| Access another user's expense | 403 Forbidden |  Pass |
| API without token | 401 Unauthorized |  Pass |
| Submit form without CSRF token | 419 Error |  Pass |
| Login with wrong password | Error message |  Pass |

---

## 4. Security Checklist

-  SQL Injection Prevention (Eloquent ORM)
-  XSS Prevention (Blade escaping)
-  CSRF Protection (Laravel middleware)
-  Password Hashing (bcrypt)
-  Role-Based Access Control
-  API Token Authentication (Sanctum)
-  Input Validation (Form Requests)
-  Session Security
-  Mass Assignment Protection
-  Ownership Verification
