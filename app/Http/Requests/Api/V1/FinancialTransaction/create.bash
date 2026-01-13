# Criar uma transação financeira
curl -X POST 'http://localhost:8000/api/v1/financial-transactions' \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer SEU_TOKEN' \
  -d '{
    "type_id": 2,
    "description": "Pagamento de fornecedor - Serviços de TI",
    "person_type": 2,
    "company_id": 1,
    "due_date": "2024-01-31",
    "amount": 1500.75,
    "category_id": 1,
    "subcategory_id": 2,
    "fiscal_document": "NF-e 123456",
    "cost_center": "TI/Infraestrutura",
    "payment_method_id": 1,
    "installment": 1,
    "total_installments": 3,
    "notes": "Pagamento referente a manutenção mensal"
  }'

# Atualizar uma transação (marcar como paga)
curl -X PATCH 'http://localhost:8000/api/v1/financial-transactions/1' \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer SEU_TOKEN' \
  -d '{
    "paid_amount": 1500.75,
    "paid_at": "2024-01-28",
    "payment_method_id": 3,
    "bank_id": 1,
    "receipt_url": "https://example.com/receipt.pdf"
  }'

# Marcar como paga via endpoint específico
curl -X POST 'http://localhost:8000/api/v1/financial-transactions/1/pay' \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer SEU_TOKEN' \
  -d '{
    "paid_amount": 1500.75,
    "payment_method_id": 3
  }'

# Obter resumo
curl -X GET 'http://localhost:8000/api/v1/financial-transactions/summary?start_date=2024-01-01&end_date=2024-01-31' \
  -H 'Authorization: Bearer SEU_TOKEN'