# Financial Transaction Import API

This documentation describes how to use the API to import financial transactions from a CSV file.

## Endpoint

**POST** `/api/v1/financial-transactions/import`

## Request Format

- **Method**: `POST`
- **Authentication**: Bearer Token (Sanctum)
- **Content-Type**: `multipart/form-data`

### Body Parameters

| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `file` | `file` | Yes | The CSV file to import. Must be semicolon (`;`) or comma (`,`) delimited. |

## CSV Mapping Details

The import logic is designed to handle the following column structure (based on the provided example):

1. **ID**: Original ID (ignored during creation)
2. **COMPETÊNCIA**: Competency date (supports standard formats and Excel serial dates)
3. **DOC FISCAL**: Fiscal document number
4. **CENTRO DE CUSTO**: Cost center identifier
5. **DESCRIÇÃO**: Description of the transaction
6. **TP_PGTO**: Payment type (e.g., EMPRÉSTIMO, PRESTAÇÃO DE SERVIÇO)
7. **BENEFICIÁRIO**: Beneficiary name. The system first checks for a matching `Company` or `Person`. If neither is found, the name is stored in the `beneficiary` column and the `person_type` is set to `3` (Unknown). No new person records are created automatically.
8. **VALOR**: Transaction amount
9. **VENCIMENTO**: Due date (supports Excel serial dates)
10. **PAGO**: Boolean (1 for paid, 0 for unpaid)
11. **ESTADO**: Payment status string
12. **VALOR PAGO**: Actual amount paid
13. **DATA DE PAGTO**: Date of payment
14. **Nº DA PARCELA**: Current installment number
15. **PARCELAS**: Total number of installments
16. **CHAVE DE PAGAMENTO**: Transaction key (PIX, bank info, etc.)
17. **FORMA DE PAGAMENTO**: Payment method (PIX, Cash, etc.)
18. **OBS**: Notes/Observações

## Important Logic Features

- **Approval Status**: All imported transactions are automatically set to `pending_review`.
- **Excel Dates**: The system automatically converts Excel serial dates (e.g., `44979` -> `2023-02-22`).
- **Payment Handling**: If `VALOR PAGO` > 0 and `DATA DE PAGTO` is present, a `FinancialPayment` is automatically recorded against the transaction.
- **Client Scope**: Data is automatically scoped to the `client_id` of the authenticated user.

## CURL Example

```bash
curl -X POST "http://localhost/api/v1/financial-transactions/import" \
     -H "Authorization: Bearer {YOUR_TOKEN}" \
     -H "Accept: application/json" \
     -F "file=@/path/to/your/example_import_financial_transactions.csv"
```

## Response Format

### Success (200 OK)
```json
{
    "message": "Import completed",
    "imported": 17,
    "errors": []
}
```

### Errors (422 Unprocessable Entity)
If the file is missing or invalid.
```json
{
    "message": "The file field is required.",
    "errors": {
        "file": ["The file field is required."]
    }
}
```
