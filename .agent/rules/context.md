---
trigger: always_on
---

# ERP System - Product Requirements Document (PRD)

## Core Loop
The user manages business operations through a multi-tenant ERP system that orchestrates financial transactions, inventory control, client proposals/contracts, and service management within isolated client environments.

## Screen Map (API Endpoints Structure)

### 1. Authentication & User Management
- **POST /api/v1/auth/login** - User authentication
- **POST /api/v1/auth/register** - User registration (admin only)
- **POST /api/v1/auth/logout** - User logout
- **GET /api/v1/auth/me** - Current user data
- **POST /api/v1/auth/refresh** - Refresh token
- **GET /api/v1/users** - List users
- **POST /api/v1/users** - Create user
- **GET /api/v1/users/{id}** - Get user details
- **PUT/PATCH /api/v1/users/{id}** - Update user
- **DELETE /api/v1/users/{id}** - Delete user
- **POST /api/v1/users/{id}/restore** - Restore user

### 2. Client/Tenant Management
- **GET /api/v1/clients** - List clients/tenants
- **POST /api/v1/clients** - Create client (super admin only)
- **GET /api/v1/clients/{id}** - Get client details
- **PUT/PATCH /api/v1/clients/{id}** - Update client
- **DELETE /api/v1/clients/{id}** - Archive client
- **POST /api/v1/clients/{id}/restore** - Restore client
- **GET /api/v1/clients/{id}/branches** - List client branches
- **POST /api/v1/clients/{id}/branches** - Create branch

### 3. Inventory Management
- **GET /api/v1/products** - List products/services
- **POST /api/v1/products** - Create product/service
- **GET /api/v1/products/{id}** - Get product details
- **PUT/PATCH /api/v1/products/{id}** - Update product
- **DELETE /api/v1/products/{id}** - Archive product
- **POST /api/v1/products/{id}/restore** - Restore product
- **GET /api/v1/products/low-stock** - List low stock items
- **GET /api/v1/measurement-units** - List measurement units
- **POST /api/v1/measurement-units** - Create measurement unit
- **GET /api/v1/inventory-movements** - List inventory movements
- **POST /api/v1/inventory-movements** - Register movement

### 4. Proposal & Contract Management
- **GET /api/v1/proposals** - List proposals
- **POST /api/v1/proposals** - Create proposal
- **GET /api/v1/proposals/{id}** - Get proposal details
- **PUT/PATCH /api/v1/proposals/{id}** - Update proposal
- **DELETE /api/v1/proposals/{id}** - Cancel proposal
- **POST /api/v1/proposals/{id}/approve** - Approve proposal
- **POST /api/v1/proposals/{id}/reject** - Reject proposal
- **POST /api/v1/proposals/{id}/convert-to-order** - Convert to order
- **GET /api/v1/orders** - List orders
- **POST /api/v1/orders** - Create order
- **GET /api/v1/orders/{id}** - Get order details
- **POST /api/v1/orders/{id}/convert-to-contract** - Convert to contract
- **GET /api/v1/contracts** - List contracts
- **POST /api/v1/contracts** - Create contract
- **GET /api/v1/contracts/{id}** - Get contract details
- **PUT/PATCH /api/v1/contracts/{id}** - Update contract
- **GET /api/v1/contracts/expiring-soon** - List expiring contracts

### 5. Service Management
- **GET /api/v1/service-orders** - List service orders
- **POST /api/v1/service-orders** - Create service order
- **GET /api/v1/service-orders/{id}** - Get service order details
- **PUT/PATCH /api/v1/service-orders/{id}** - Update service order
- **POST /api/v1/service-orders/{id}/complete** - Mark as completed
- **GET /api/v1/service-tickets** - List service tickets
- **POST /api/v1/service-tickets** - Create service ticket
- **GET /api/v1/service-tickets/{id}** - Get ticket details
- **PUT/PATCH /api/v1/service-tickets/{id}** - Update ticket
- **POST /api/v1/service-tickets/{id}/close** - Close ticket

### 6. Contact & Interaction Management
- **GET /api/v1/interactions** - List interactions
- **POST /api/v1/interactions** - Create interaction
- **GET /api/v1/interactions/{id}** - Get interaction details
- **PUT/PATCH /api/v1/interactions/{id}** - Update interaction
- **DELETE /api/v1/interactions/{id}** - Delete interaction
- **GET /api/v1/interactions/reminders** - List pending reminders
- **POST /api/v1/interactions/{id}/add-attachment** - Add attachment
- **GET /api/v1/attachments** - List all attachments
- **DELETE /api/v1/attachments/{id}** - Delete attachment

### 7. Financial Management
- **GET /api/v1/financial-transactions** - List transactions (existing)
- **POST /api/v1/financial-transactions** - Create transaction (existing)
- **GET /api/v1/financial-transactions/summary** - Financial summary (existing)
- **GET /api/v1/financial-transactions/{id}/receipt** - Download receipt (existing)
- **POST /api/v1/financial-transactions/{id}/pay** - Mark as paid (existing)
- **GET /api/v1/bank-reconciliation** - List reconciliations
- **POST /api/v1/bank-reconciliation** - Create reconciliation
- **GET /api/v1/financial-reports/cash-flow** - Cash flow report
- **GET /api/v1/financial-reports/income-statement** - DRE report
- **GET /api/v1/financial-reports/receivables** - Receivables forecast

### 8. Company & Person Management
- **GET /api/v1/companies** - List companies (existing)
- **POST /api/v1/companies** - Create company (existing)
- **GET /api/v1/people** - List people (existing)
- **POST /api/v1/people** - Create person (existing)

### 9. Document Management
- **GET /api/v1/invoices** - List invoices
- **POST /api/v1/invoices** - Create invoice
- **GET /api/v1/invoices/{id}** - Get invoice details
- **POST /api/v1/invoices/{id}/issue** - Issue invoice
- **POST /api/v1/invoices/{id}/cancel** - Cancel invoice
- **GET /api/v1/documents/series** - Manage document series
- **POST /api/v1/documents/series** - Create document series

### 10. Configuration & System
- **GET /api/v1/categories** - List categories (existing)
- **GET /api/v1/categories/tree/hierarchical** - Category tree (existing)
- **GET /api/v1/types** - List types
- **GET /api/v1/statuses** - List statuses
- **GET /api/v1/roles** - List roles (existing)
- **GET /api/v1/banks** - List banks
- **GET /api/v1/payment-methods** - List payment methods
- **GET /api/v1/countries** - List countries
- **GET /api/v1/states** - List states
- **GET /api/v1/cities** - List cities

## User Flow (API Sequence)

### 1. User Registration & Onboarding
```
POST /api/v1/auth/register (super admin) → Creates user
POST /api/v1/auth/login → Gets access token
GET /api/v1/auth/me → Retrieves user data
POST /api/v1/clients → Creates new tenant (if super admin)
```

### 2. Inventory Management Flow
```
POST /api/v1/measurement-units → Creates unit of measurement
POST /api/v1/products → Creates product/service
POST /api/v1/inventory-movements → Records stock movement
GET /api/v1/products/low-stock → Checks critical inventory
```

### 3. Sales Process Flow
```
POST /api/v1/proposals → Creates proposal for client
PUT /api/v1/proposals/{id} → Updates proposal items
POST /api/v1/proposals/{id}/approve → Client approves
POST /api/v1/proposals/{id}/convert-to-order → Creates order
POST /api/v1/orders/{id}/convert-to-contract → Creates contract
POST /api/v1/invoices → Generates invoice from contract
POST /api/v1/financial-transactions → Records payment
```

### 4. Service Delivery Flow
```
POST /api/v1/service-orders → Creates service order
POST /api/v1/service-tickets → Creates related tickets
POST /api/v1/interactions → Records service updates
POST /api/v1/service-orders/{id}/complete → Marks as completed
```

### 5. Financial Management Flow
```
GET /api/v1/financial-transactions → Views transactions
POST /api/v1/bank-reconciliation → Reconciles bank statement
GET /api/v1/financial-reports/cash-flow → Views cash flow
GET /api/v1/financial-reports/receivables → Views receivables forecast
```

## Data Structure

### Core Entities
1. **User** - System users with roles/permissions
2. **Client/Tenant** - Companies using the ERP system
3. **Person** - Individuals (PF) with client association
4. **Company** - Legal entities (PJ) with client association
5. **Product** - Items for sale/services with inventory control
6. **Proposal** - Client quotations with JSON items
7. **Order** - Approved proposals converted to orders
8. **Contract** - Formal agreements with terms and conditions
9. **ServiceOrder** - Work orders for service delivery
10. **ServiceTicket** - Support tickets within service orders
11. **FinancialTransaction** - Monetary movements
12. **Invoice** - Fiscal documents with series control
13. **Interaction** - Client communication history
14. **Attachment** - File storage for all entities
15. **InventoryMovement** - Stock level changes
16. **BankReconciliation** - Bank statement matching

### Supporting Entities
1. **Category** - Multilevel categorization
2. **Type** - Entity type classification
3. **Status** - Customizable status per entity type
4. **Role** - User roles with JSON permissions
5. **MeasurementUnit** - Units of measure (UN, KG, M, etc.)
6. **PaymentMethod** - Payment options
7. **Bank** - Financial institutions
8. **Country/State/City** - Geographic data
9. **DocumentSeries** - Invoice number sequences
10. **Reminder** - Follow-up alerts

## Golden Rules

1. **Multi-tenancy First**: All data must be scoped by `client_id`, complete data isolation between tenants
2. **Branch Hierarchy**: Branch clients inherit configurations from parent, parent can access branch data but not vice-versa
3. **Permission Granularity**: Role-based access with JSON permissions for fine-grained control per module/function
4. **Financial Traceability**: All financial transactions must be linked to source documents (invoices, contracts, proposals)
5. **Inventory Integrity**: Stock levels must be updated only through inventory movements, never directly
6. **Document Series Control**: Sequential numbering for fiscal documents with no gaps or duplicates
7. **Contract Lifecycle**: Proposals → Orders → Contracts with full audit trail
8. **Service Linkage**: Service tickets must be linked to service orders, which may be linked to contracts
9. **Archival System**: Soft deletes for reversible removal, separate archival system for historical preservation
10. **Field Customization**: Support for custom fields on major entities via JSON/separate tables
11. **Attachment Centralization**: All file attachments stored in unified system with entity relationships
12. **Reminder System**: Automated alerts for contract renewals, follow-ups, and deadlines
13. **Brazilian Compliance**: NCM, EAN, tax fields for products; support for Brazilian fiscal documents
14. **JSON Flexibility**: Use JSON columns for dynamic data (proposal items, permissions, custom fields)
15. **API-First Design**: All business logic exposed via RESTful API, no direct database access

## Technical Constraints

1. **Authentication**: Laravel Sanctum for API token authentication
2. **Database**: MySQL/PostgreSQL with proper indexing for multi-tenant queries
3. **File Storage**: Configurable (local, S3) with access control per tenant
4. **Queue System**: For background jobs (reminders, document generation)
5. **Caching**: Redis for frequently accessed data (categories, statuses, types)
6. **Search**: Full-text search on key entities (products, people, companies)
7. **Reporting**: Separate read-optimized views for complex reports
8. **Backup**: Per-tenant backup capabilities
9. **Logging**: Comprehensive audit logs for all data modifications
10. **Validation**: Request validation with custom rules per tenant configuration