# ERD — MBG Project

```mermaid
erDiagram
    users ||--o{ schools : "belongs_to"
    kitchens ||--o{ schools : "has"
    users ||--o{ complaints : "creates"
    kitchens ||--o{ complaints : "receives"
    complaint_categories ||--o{ complaints : "categorizes"
    complaints ||--o{ complaint_attachments : "has"
    complaints ||--o{ complaint_responses : "has"
    users ||--o{ complaint_responses : "writes"
    users ||--o{ suggestions : "submits"
    users ||--o{ audit_logs : "triggers"
    users ||--o{ sessions : "has"

    users {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        enum role "super_admin | admin | user"
        string phone
        string avatar
        boolean is_active
        bigint school_id FK "nullable"
        timestamp last_login_at
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    schools {
        bigint id PK
        string name
        string npsn UK
        text address
        string phone
        bigint kitchen_id FK "nullable"
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    kitchens {
        bigint id PK
        string name
        string person_in_charge
        text address
        string phone
        unsignedInteger production_capacity
        enum operational_status "active | inactive | maintenance"
        timestamp created_at
        timestamp updated_at
    }

    complaint_categories {
        bigint id PK
        string name
        text description
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    complaints {
        bigint id PK
        string complaint_number UK
        bigint user_id FK
        bigint kitchen_id FK
        bigint category_id FK
        string title
        text description
        enum status "pending | received | in_progress | resolved | rejected"
        enum priority "low | medium | high"
        timestamp resolved_at
        timestamp created_at
        timestamp updated_at
    }

    complaint_attachments {
        bigint id PK
        bigint complaint_id FK
        string file_path
        string file_name
        string file_type
        unsignedInteger file_size
        timestamp created_at
        timestamp updated_at
    }

    complaint_responses {
        bigint id PK
        bigint complaint_id FK
        bigint user_id FK
        text message
        timestamp created_at
        timestamp updated_at
    }

    suggestions {
        bigint id PK
        bigint user_id FK
        text message
        boolean is_read
        timestamp created_at
        timestamp updated_at
    }

    audit_logs {
        bigint id PK
        bigint user_id FK "nullable"
        string action
        string model_type
        bigint model_id
        json old_values
        json new_values
        string ip_address
        text user_agent
        timestamp created_at
        timestamp updated_at
    }

    sessions {
        string id PK
        bigint user_id FK "nullable"
        string ip_address
        text user_agent
        longText payload
        integer last_activity
    }

    password_reset_tokens {
        string email PK
        string token
        timestamp created_at
    }
```

## Catatan Relasi

| Parent | Child | Tipe | Kunci |
|--------|-------|------|-------|
| `users` | `schools` | Many-to-One | `users.school_id` → `schools.id` |
| `kitchens` | `schools` | Many-to-One | `schools.kitchen_id` → `kitchens.id` |
| `users` | `complaints` | Many-to-One | `complaints.user_id` → `users.id` |
| `kitchens` | `complaints` | Many-to-One | `complaints.kitchen_id` → `kitchens.id` |
| `complaint_categories` | `complaints` | Many-to-One | `complaints.category_id` → `complaint_categories.id` |
| `complaints` | `complaint_attachments` | One-to-Many | `complaint_attachments.complaint_id` → `complaints.id` |
| `complaints` | `complaint_responses` | One-to-Many | `complaint_responses.complaint_id` → `complaints.id` |
| `users` | `complaint_responses` | Many-to-One | `complaint_responses.user_id` → `users.id` |
| `users` | `suggestions` | Many-to-One | `suggestions.user_id` → `users.id` |
| `users` | `audit_logs` | Many-to-One (nullable) | `audit_logs.user_id` → `users.id` |
| `users` | `sessions` | Many-to-One (nullable) | `sessions.user_id` → `users.id` |
