# ExpenseMate API Documentation

## Base URL
http://localhost:8000/api

## Authentication
All protected endpoints require Bearer token in header:

Authorization: Bearer {your_token}
Accept: application/json
Content-Type: application/json

---

## 1. Authentication Endpoints

### 1.1 Login
POST /api/auth/login

Request Body:
{
    "email": "user@expensemate.com",
    "password": "User@12345",
    "device_name": "MyDevice"
}

Success Response (200):
{
    "token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer",
    "expires_at": "2026-06-01 10:00:00",
    "scopes": [
        "expense:read",
        "expense:write",
        "summary:read"
    ],
    "user": {
        "id": 1,
        "name": "Demo User",
        "email": "user@expensemate.com",
        "role": "user"
    }
}

Error Response (422):
{
    "message": "Invalid credentials.",
    "errors": {
        "email": ["Invalid credentials."]
    }
}

---

### 1.2 Logout
POST /api/auth/logout
Auth Required: Yes

Success Response (200):
{
    "message": "Logged out successfully."
}

---

### 1.3 Get Current User
GET /api/auth/me
Auth Required: Yes

Success Response (200):
{
    "id": 1,
    "name": "Demo User",
    "email": "user@expensemate.com",
    "role": "user",
    "is_active": true,
    "created_at": "2026-01-01 10:00:00"
}

---

## 2. Expense Endpoints

### 2.1 Get All Expenses
GET /api/expenses
Auth Required: Yes

Query Parameters:
- type        : Filter by income or expense
- from        : Date from YYYY-MM-DD
- to          : Date to YYYY-MM-DD
- category_id : Filter by category ID
- per_page    : Results per page (default 20)

Success Response (200):
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "type": "expense",
                "amount": 150.00,
                "note": "Grocery shopping",
                "expense_date": "2026-05-01",
                "category": {
                    "id": 1,
                    "name": "Food"
                },
                "created_at": "2026-05-01T10:00:00Z"
            }
        ],
        "current_page": 1,
        "total": 45,
        "per_page": 20
    },
    "message": "Expenses retrieved successfully."
}

---

### 2.2 Create Expense
POST /api/expenses
Auth Required: Yes

Request Body:
{
    "type": "expense",
    "category_id": 1,
    "amount": 150.00,
    "note": "Grocery shopping",
    "expense_date": "2026-05-01"
}

Success Response (201):
{
    "success": true,
    "message": "Expense created successfully.",
    "expense": {
        "id": 1,
        "type": "expense",
        "amount": 150.00,
        "note": "Grocery shopping",
        "expense_date": "2026-05-01",
        "category": {
            "id": 1,
            "name": "Food"
        }
    }
}

Validation Error (422):
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "amount": ["The amount must be at least 0.01."]
    }
}

---

### 2.3 Get Single Expense
GET /api/expenses/{id}
Auth Required: Yes

Success Response (200):
{
    "success": true,
    "expense": {
        "id": 1,
        "type": "expense",
        "amount": 150.00,
        "note": "Grocery shopping",
        "expense_date": "2026-05-01",
        "category": {
            "id": 1,
            "name": "Food"
        }
    },
    "message": "Expense retrieved successfully."
}

Not Found (404):
{
    "success": false,
    "message": "Expense not found.",
    "error": "The requested expense does not exist."
}

---

### 2.4 Update Expense
PUT /api/expenses/{id}
Auth Required: Yes

Request Body (all fields optional):
{
    "type": "expense",
    "category_id": 1,
    "amount": 200.00,
    "note": "Updated note",
    "expense_date": "2026-05-01"
}

Success Response (200):
{
    "success": true,
    "message": "Expense updated successfully.",
    "expense": {
        "id": 1,
        "type": "expense",
        "amount": 200.00,
        "note": "Updated note",
        "expense_date": "2026-05-01",
        "category": {
            "id": 1,
            "name": "Food"
        }
    }
}

---

### 2.5 Delete Expense
DELETE /api/expenses/{id}
Auth Required: Yes

Success Response (200):
{
    "success": true,
    "message": "Expense deleted successfully."
}

---

## 3. Summary Endpoint

### 3.1 Get Financial Summary
GET /api/summary
Auth Required: Yes

Success Response (200):
{
    "success": true,
    "data": {
        "total_income": 5000.00,
        "total_expense": 2300.00,
        "balance": 2700.00,
        "category_breakdown": [
            {
                "category": "Food",
                "total": 1200.00
            },
            {
                "category": "Transport",
                "total": 500.00
            }
        ],
        "monthly_trend": [
            {
                "month": 1,
                "year": 2026,
                "type": "income",
                "total": 4000.00
            }
        ]
    },
    "generated_at": "2026-05-01 10:00:00"
}

---

## 4. HTTP Status Codes

200 - Success
201 - Created
401 - Unauthorized - invalid or missing token
403 - Forbidden - insufficient permissions
404 - Not Found
422 - Validation Error
429 - Too Many Requests
500 - Server Error

---

## 5. Token Scopes

expense:read  - View expenses
expense:write - Create, update, delete expenses
summary:read  - View financial summary

---

## 6. Rate Limiting

Login : 5 requests per minute
API   : 60 requests per minute

---

## 7. Testing with Postman

Step 1: POST /api/auth/login to get token
Step 2: Copy token from response
Step 3: Add header Authorization Bearer your token
Step 4: Test other endpoints
