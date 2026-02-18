# Financial Payment API Documentation

This API allows managing payments associated with financial transactions.

## Endpoints

### 1. List Payments
Returns a list of payments for the authenticated client.

**Request:**
```bash
curl -X GET "http://localhost/api/v1/financial-payments" \
     -H "Authorization: Bearer {YOUR_TOKEN}" \
     -H "Accept: application/json"
```

**Optional Query Parameters:**
- `financial_transaction_id`: Filter payments by transaction ID.

---

### 2. Create Payment
Registers a new payment for a transaction. This will automatically update the transaction's `paid_total` and `is_fully_paid` status.

**Request:**
```bash
curl -X POST "http://localhost/api/v1/financial-payments" \
     -H "Authorization: Bearer {YOUR_TOKEN}" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "financial_transaction_id": 1,
           "payment_date": "2024-02-18",
           "amount": 150.00,
           "payment_method_id": 1,
           "bank_id": 1,
           "notes": "Payment via Pix",
           "is_manual": true
         }'
```

---

### 3. Get Payment Details
Returns detailed information about a specific payment.

**Request:**
```bash
curl -X GET "http://localhost/api/v1/financial-payments/{id}" \
     -H "Authorization: Bearer {YOUR_TOKEN}" \
     -H "Accept: application/json"
```

---

### 4. Update Payment
Updates an existing payment. If the amount is changed, the associated transaction's `paid_total` will be adjusted.

**Request:**
```bash
curl -X PUT "http://localhost/api/v1/financial-payments/{id}" \
     -H "Authorization: Bearer {YOUR_TOKEN}" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "amount": 160.00,
           "notes": "Updated payment amount"
         }'
```

---

### 5. Delete Payment
Removes a payment and reverts the associated transaction's `paid_total` and `is_fully_paid` status.

**Request:**
```bash
curl -X DELETE "http://localhost/api/v1/financial-payments/{id}" \
     -H "Authorization: Bearer {YOUR_TOKEN}" \
     -H "Accept: application/json"
```
