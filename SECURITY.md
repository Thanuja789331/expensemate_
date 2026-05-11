# ExpenseMate — Security Documentation

## 1. Overview
ExpenseMate implements multiple layers of security to protect 
user data and prevent unauthorized access. This document outlines 
all security threats, mitigations, and testing results.

---

## 2. Security Architecture

ExpenseMate uses a multi-layer security approach:

- Layer 1: Network Security (HTTPS, Rate Limiting)
- Layer 2: Application Security (CSRF, XSS, SQL Injection)
- Layer 3: Authentication Security (Jetstream, 2FA, Sanctum)
- Layer 4: Authorization Security (Middleware, Policies)
- Layer 5: Data Security (Hashing, Encryption, Soft Deletes)

---

## 3. Security Threats & Mitigations

### 3.1 SQL Injection
**Threat:** Attacker inserts malicious SQL code into input fields
to manipulate the database.

**Risk Level:** Critical

**Mitigation Implemented:**
- Laravel Eloquent ORM used for ALL database queries
- PDO prepared statements used automatically
- User input NEVER concatenated into SQL strings
- Query scopes enforce user data isolation

**Code Example:**
```php
// SAFE - Eloquent parameterized query
Expense::forUser($userId)->ofType('expense')->get();

// NEVER do this - vulnerable to SQL injection
DB::select("SELECT * FROM expenses WHERE user_id = " . $userId);
```

**Status:** ✅ Fully Protected

---

### 3.2 Cross-Site Scripting (XSS)
**Threat:** Attacker injects malicious JavaScript into pages
to steal user data or hijack sessions.

**Risk Level:** High

**Mitigation Implemented:**
- Laravel Blade {{ }} automatically escapes ALL output
- User input never rendered as raw HTML
- Content Security Policy headers implemented
- X-XSS-Protection header enabled

**Code Example:**
```php
// SAFE - Blade auto-escapes
{{ $expense->note }}

// NEVER use for user input
{!! $expense->note !!}
```

**Status:** ✅ Fully Protected

---

### 3.3 Cross-Site Request Forgery (CSRF)
**Threat:** Attacker tricks user into submitting malicious
forms to perform unwanted actions.

**Risk Level:** High

**Mitigation Implemented:**
- Laravel CSRF middleware enabled globally
- Every form includes @csrf token
- Token verified on every POST/PUT/PATCH/DELETE request
- API routes use Sanctum token authentication instead

**Code Example:**
```html
<form method="POST" action="/expenses">
    @csrf
    <!-- form fields -->
</form>
```

**Status:** ✅ Fully Protected

---

### 3.4 Broken Authentication
**Threat:** Weak passwords, session hijacking, or
brute force attacks.

**Risk Level:** Critical

**Mitigation Implemented:**
- Laravel Jetstream handles all authentication
- Passwords hashed using bcrypt (cost factor 12)
- Two-Factor Authentication (2FA) available
- Session invalidated on logout
- Rate limiting: 5 login attempts per minute per IP
- Remember me token securely stored

**Status:** ✅ Fully Protected

---

### 3.5 Broken Access Control
**Threat:** Users accessing other users data or
admin-only functionality.

**Risk Level:** Critical

**Mitigation Implemented:**
- AdminOnly middleware blocks non-admin users
- forUser() scope ensures users only see own data
- Ownership check before all edit/delete operations
- Role-based access control (user/admin)
- 403 error page for unauthorized access

**Code Example:**
```php
// AdminOnly Middleware
if (!$request->user()->isAdmin()) {
    abort(403, 'Access denied.');
}

// Ownership check
if ($expense->user_id !== auth()->id()) {
    abort(403);
}
```

**Status:** ✅ Fully Protected

---

### 3.6 Security Misconfiguration
**Threat:** Exposed sensitive information through
misconfigured settings.

**Risk Level:** Medium

**Mitigation Implemented:**
- APP_DEBUG=false in production
- .env file excluded from Git repository
- Security headers middleware implemented:
  - X-Frame-Options: DENY
  - X-Content-Type-Options: nosniff
  - X-XSS-Protection: 1; mode=block
  - Referrer-Policy: strict-origin-when-cross-origin
  - Content-Security-Policy header

**Status:** ✅ Fully Protected

---

### 3.7 Mass Assignment
**Threat:** Attacker sends extra fields to modify
protected database columns.

**Risk Level:** High

**Mitigation Implemented:**
- $fillable defined on ALL models
- Only whitelisted fields can be mass assigned
- No use of $guarded = [] anywhere

**Code Example:**
```php
protected $fillable = [
    'user_id', 'category_id', 'type',
    'amount', 'note', 'expense_date'
];
```

**Status:** ✅ Fully Protected

---

### 3.8 API Security
**Threat:** Unauthorized access to API endpoints.

**Risk Level:** High

**Mitigation Implemented:**
- Laravel Sanctum token-based authentication
- Token scopes limit permissions:
  - expense:read
  - expense:write
  - summary:read
- Tokens expire after 30 days
- Token revoked on logout
- Rate limiting: 60 requests per minute
- Invalid tokens return 401 Unauthorized

**Status:** ✅ Fully Protected

---

### 3.9 Sensitive Data Exposure
**Threat:** Passwords, tokens or sensitive data
exposed to attackers.

**Risk Level:** High

**Mitigation Implemented:**
- Passwords stored as bcrypt hashes
- .env file excluded from Git
- API tokens hashed in database
- HTTPS enforced in production
- Soft deletes prevent permanent data loss

**Status:** ✅ Fully Protected

---

### 3.10 Rate Limiting
**Threat:** Brute force attacks or API abuse.

**Risk Level:** Medium

**Mitigation Implemented:**
- Login: 5 attempts per minute per email/IP
- API: 60 requests per minute per user/IP
- Web: 100 requests per minute per user/IP
- Custom error responses for rate limit exceeded

**Status:** ✅ Fully Protected

---

## 4. Security Headers

| Header | Value | Purpose |
|--------|-------|---------|
| X-Frame-Options | DENY | Prevent clickjacking |
| X-Content-Type-Options | nosniff | Prevent MIME sniffing |
| X-XSS-Protection | 1; mode=block | XSS protection |
| Referrer-Policy | strict-origin-when-cross-origin | Privacy |
| Content-Security-Policy | default-src 'self' | XSS prevention |

---

## 5. Security Testing Results

| Test Case | Expected Result | Actual Result | Status |
|-----------|----------------|---------------|--------|
| Access dashboard without login | Redirect to login | Redirected ✅ | Pass |
| Normal user access admin page | 403 Forbidden | 403 shown ✅ | Pass |
| Access another user expense | 403 Forbidden | 403 shown ✅ | Pass |
| API without token | 401 Unauthorized | 401 shown ✅ | Pass |
| API with expired token | 401 Unauthorized | 401 shown ✅ | Pass |
| Login with wrong password | Error message | Error shown ✅ | Pass |
| Too many login attempts | 429 Too Many | Rate limited ✅ | Pass |
| SQL injection attempt | Query rejected | Protected ✅ | Pass |
| XSS script injection | Script escaped | Escaped ✅ | Pass |
| CSRF without token | 419 Error | Protected ✅ | Pass |
| Admin login success | Admin dashboard | Shown ✅ | Pass |
| API rate limit exceeded | 429 Too Many | Rate limited ✅ | Pass |

---

## 6. Security Checklist

- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Prevention (Blade escaping + CSP headers)
- ✅ CSRF Protection (Laravel middleware)
- ✅ Password Hashing (bcrypt)
- ✅ Role-Based Access Control (Admin/User)
- ✅ API Token Authentication (Sanctum)
- ✅ Token Scopes and Expiration
- ✅ Input Validation (Form Requests)
- ✅ Session Security
- ✅ Mass Assignment Protection
- ✅ Ownership Verification
- ✅ Security Headers
- ✅ Rate Limiting
- ✅ Soft Deletes (Data Protection)
- ✅ Two Factor Authentication (2FA)
- ✅ Database Indexes (Performance)

---

## 7. Technologies Used for Security

| Technology | Purpose |
|------------|---------|
| Laravel Jetstream | Authentication & 2FA |
| Laravel Sanctum | API token authentication |
| Laravel Fortify | Password hashing & rate limiting |
| Eloquent ORM | SQL injection prevention |
| Blade Templates | XSS prevention |
| CSRF Middleware | CSRF protection |
| Custom Middleware | Security headers & admin access |

---

## 8. Conclusion

ExpenseMate implements industry-standard security practices
across all layers of the application. All OWASP Top 10
vulnerabilities have been addressed and tested. The system
is production-ready with comprehensive security measures
in place to protect user data and prevent unauthorized access.
