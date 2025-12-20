# Feature Implementation Plan

## Overview

This document outlines the plan to implement three new features: Meeting Schedule Notifications, Converted Clients (with dynamic fields), and a Demo Section.

---

## Implementation Status

### TODO CHECKLIST

#### Step 1: Meeting Schedule Notification
- [x] 1.1 Create NotificationController with checkUpcomingMeetings method
- [x] 1.2 Add notification route to web.php
- [x] 1.3 Create notification sound file placeholder
- [x] 1.4 Add Alpine.js notification component to app.blade.php
- [x] 1.5 Create notification modal/toast UI
- [x] 1.6 Test notification with existing meeting data

#### Step 2: Converted Clients with Dynamic Fields
- [x] 2.1 Create field_definitions migration and model
- [x] 2.2 Create FieldDefinitionController for admin field management
- [x] 2.3 Create ClientController with dynamic field support
- [x] 2.4 Create admin field management UI
- [x] 2.5 Update ConversionController to auto-create ClientDetail
- [x] 2.6 Create clients index/show/edit views with dynamic fields
- [x] 2.7 Add Clients link to sidebar
- [x] 2.8 Test client creation from conversion flow

#### Step 3: Demo Section
- [x] 3.1 Create demos table migration and Demo model
- [x] 3.2 Create DemoController with full CRUD
- [x] 3.3 Create demo views (index, create, edit, show)
- [x] 3.4 Add Demo Section to sidebar
- [x] 3.5 Link dynamic fields to Demo section
- [x] 3.6 Test demo CRUD with dynamic fields

---

## 1. Meeting Schedule Notification

**Goal:** Show a popup reminder with sound upon login and 1 hour before a scheduled meeting.
**Context:** `Meeting` model exists (`meeting_date`, `meeting_time`). `app.blade.php` uses Alpine.js.

### Implementation Logic

1.  **Backend Endpoint**: Create `NotificationController` with a method `checkPending` that returns JSON:
    -   Meetings for the current user (or all for admin) scheduled for _today_.
    -   Filter conditions: `meeting_date == today` AND (`meeting_time` is upcoming within 1 hour OR "just logged in" check).
    -   Actually, simpler approach:
        -   Frontend polls every X minutes (e.g., 5 min).
        -   Backend returns list of upcoming meetings (e.g., in next 65 mins).
        -   Frontend logic checks if "already notified" (using local storage or session) to avoid spam.
        -   For "Login" reminder: check immediately on page load.
2.  **Frontend**:
    -   Add a script in `resources/views/layouts/app.blade.php`.
    -   Use Alpine.js or vanilla JS to call the endpoint.
    -   **Sound**: Use a simple MP3 file in `public/sounds/` and HTML5 `Audio()` API.
    -   **Popup**: Standard specialized Modal or Toast.
3.  **Calendar Sync**:
    -   Ensure `MeetingController` saves correctly to DB (already checking `Meeting` model).
    -   If "Sync with Calendar" means Google/Outlook, we need external APIs (e.g., Spatie Google Calendar).
    -   _Assumption_: User means "Internal Calendar View". We will ensure existing calendar views reflect these meetings.

### Files to Modify/Create

-   `[NEW] app/Http/Controllers/NotificationController.php`
-   `[MODIFY] routes/web.php` (Add `/notifications/check`)
-   `[MODIFY] resources/views/layouts/app.blade.php` (Add JS polling logic)
-   `[NEW] public/sounds/notification.mp3`

## 2. Converted Clients & Dynamic Fields

**Goal:** Import converted lead info. Admin can add dynamic fields (Text, Image, Link).
**Requirement:** "Create fields in that table".
**Constraint:** "No facade, use GD extension" for images.

### Architecture

-   **Data Storage**:
    -   We can use a JSON column `custom_data` in `client_details` to store dynamic values. This is safer than modifying the schema at runtime.
    -   OR we can use actual `Schema::table` if strictly required. _Recommendation: JSON column for stability, but we can simulate "table fields" in the UI._
    -   Let's stick to the user's phrasing "automatically it will create fields in that table" -> This implies `Schema::table(...)` calls. I will implement this since explicitly asked, but warn about risks (locking, limits).
    -   **Table**: `client_details` already exists. We might need a `client_field_definitions` table to track _which_ fields are custom and their types (text, image, link).
-   **Image Upload**:
    -   Use Laravel's `Storage` (standard) or PHP native `move_uploaded_file` + `gd` for resizing if needed.
    -   Store path in the dynamic column.

### Implementation Steps

1.  **Field Definition System**:
    -   Create `FieldDefinition` model/migration (table: `field_definitions`) to store: `model_type` (Client/Demo), `field_name`, `field_type` (text, image, link), `label`.
2.  **Schema Manager**:
    -   When Admin adds a field, run `Schema::table('client_details', function...)` to add the column.
    -   Types: `string` (text/link), `string` (image path).
3.  **Data Import**:
    -   When Lead -> Converted, create `ClientDetail` and populate basic info.
4.  **UI**:
    -   Admin Settings page to manage fields.
    -   Client Edit page renders these fields dynamically.

## 3. Demo Section

**Goal:** Dynamic fields for "Demo" section.
**Implementation:** Identical to Converted Clients. Use the same `FieldDefinition` system with a different `model_type` or target table (e.g., `demos` or `demo_details`).

### Files to Modify/Create

-   `[NEW] app/Models/FieldDefinition.php`
-   `[NEW] database/migrations/xxxx_create_field_definitions_table.php`
-   `[NEW] app/Http/Controllers/FieldDefinitionController.php`
-   `[NEW] app/Http/Controllers/ClientController.php` (or modify `ConversionController`)
-   `[NEW] app/Models/Demo.php` + Migration
-   `[MODIFY] resources/views/layouts/app.blade.php` (Sidebar link)

## Verification Plan

### Automated Tests

-   **Notifications**: Unit test `NotificationController` to ensure it returns today's meetings.
-   **Dynamic Fields**: Feature test to add a field, verify it exists in DB Schema, add data, and retrieve it.

### Manual Verification

1.  **Notification**:
    -   Schedule a meeting 5 mins from now.
    -   Refresh page (simulating login) -> Expect Popup + Sound.
    -   Wait -> Expect Popup + Sound 1 hour before (simulate by time manipulation or creating meeting 1hr 1min away).
2.  **Fields**:
    -   Go to Admin > Field Settings.
    -   Add "Passport Photo" (Image).
    -   Go to Client. Upload image. Save.
    -   Verify image and field persist.

---

**Note:** For "No Facade", we will use standard Laravel `Storage` facade (which is a wrapper around filesystem/Flux/S3) or native PHP if strictly insisted. Laravel's `Request::file()->store()` is standard and uses GD internally for validation/handling if configured. We will avoid `Intervention\Image` facade.

---

## 4. Smart Suggestions System

**Goal:** Create an intelligent suggestion system with two subsections:
1. **Auto Follow-up** - Rule-based system to identify leads with high conversion potential for follow-up
2. **Smart Lead Assign** - Performance-based lead assignment recommendations

---

### 4.1 Auto Follow-up System

#### Concept
A rule engine that allows users to define custom rules based on lead/contact/follow-up attributes. When leads match these rules, they are suggested for auto follow-up. Both admins and sales persons can create rules - admin rules apply to all leads, sales person rules apply only to their assigned leads.

#### Available Fields for Rule Configuration

Based on codebase analysis, the following fields are available for rule creation:

**Lead Fields:**
| Field | Type | Values/Range | Description |
|-------|------|--------------|-------------|
| `status` | Enum | New, Contacted, Qualified, Negotiation, Converted, Lost | Current lead status |
| `priority` | Enum | High, Medium, Low | Lead priority level |
| `source` | Enum | WhatsApp, Messenger, Website | Lead acquisition source |
| `service_interested` / `service_id` | Relation | Dynamic from services table | Service the lead is interested in |
| `is_repeat_lead` | Boolean | true/false | Whether this is a returning lead |
| `lead_date` | Date | Date range | When the lead was created |
| `days_since_lead` | Computed | Number | Days since lead creation (calculated) |

**Contact/Response Fields (from `lead_contacts`):**
| Field | Type | Values/Range | Description |
|-------|------|--------------|-------------|
| `response_status` | Enum | Yes, No, No Res., 50%, Call Later, Phone off, Interested, Demo Delivered, 80% | Latest call response |
| `total_calls` | Computed | Number | Total calls made to this lead |
| `days_since_last_call` | Computed | Number | Days since last contact |
| `last_call_date` | Date | Date | When the lead was last called |

**Follow-up Fields (from `follow_ups`):**
| Field | Type | Values/Range | Description |
|-------|------|--------------|-------------|
| `interest` | Enum | Yes, No, No Response, Call Later, 50% | Follow-up interest level |
| `pending_follow_ups` | Computed | Number | Count of pending follow-ups |
| `last_follow_up_date` | Date | Date | Most recent follow-up date |
| `days_since_follow_up` | Computed | Number | Days since last follow-up |
| `price` | Decimal | Amount | Quoted price during follow-up |

**Meeting Fields (from `meetings`):**
| Field | Type | Values/Range | Description |
|-------|------|--------------|-------------|
| `has_meeting` | Boolean | true/false | Whether lead has any meetings |
| `meeting_outcome` | Enum | Pending, Completed, Cancelled, Rescheduled | Latest meeting status |

#### Rule Operators
- `equals` - Exact match
- `not_equals` - Not equal to
- `greater_than` - Numeric comparison
- `less_than` - Numeric comparison
- `between` - Range (for dates/numbers)
- `in` - One of multiple values
- `not_in` - Not one of multiple values
- `is_null` - Field is empty
- `is_not_null` - Field has value

#### Rule Logic
- Multiple conditions can be combined with AND/OR logic
- Rules have priority/weight for scoring
- Leads matching more rules get higher suggestion scores

#### Example Rules

1. **Hot Lead Rule**: Status = 'Qualified' AND Priority = 'High' AND Days Since Last Call > 2
2. **Re-engage Rule**: Status = 'Contacted' AND Response = '50%' AND Days Since Follow-up > 7
3. **Callback Required**: Response = 'Call Later' AND Days Since Last Call > 3
4. **Demo Follow-up**: Has Meeting = true AND Meeting Outcome = 'Completed' AND Status != 'Converted'

#### Database Schema

**Table: `follow_up_rules`**
```sql
id (primary key)
user_id (foreign key -> users.id) -- NULL for admin global rules
name (string) -- Rule name
description (text, nullable)
priority (integer, default 0) -- Higher = more important
is_active (boolean, default true)
logic_type (enum: 'AND', 'OR') -- How conditions combine
created_at, updated_at
```

**Table: `follow_up_rule_conditions`**
```sql
id (primary key)
rule_id (foreign key -> follow_up_rules.id, cascade delete)
field (string) -- e.g., 'lead.status', 'contact.response_status'
operator (enum: 'equals', 'not_equals', 'greater_than', 'less_than', 'between', 'in', 'not_in', 'is_null', 'is_not_null')
value (json) -- Stores value(s) for comparison
created_at, updated_at
```

#### Implementation Files

- `[NEW] app/Models/FollowUpRule.php`
- `[NEW] app/Models/FollowUpRuleCondition.php`
- `[NEW] database/migrations/xxxx_create_follow_up_rules_table.php`
- `[NEW] database/migrations/xxxx_create_follow_up_rule_conditions_table.php`
- `[NEW] app/Http/Controllers/FollowUpRuleController.php`
- `[NEW] app/Services/AutoFollowUpService.php` -- Rule matching engine
- `[NEW] resources/views/follow-up-rules/index.blade.php`
- `[NEW] resources/views/follow-up-rules/create.blade.php`
- `[NEW] resources/views/follow-up-rules/edit.blade.php`
- `[NEW] resources/views/smart-suggestions/index.blade.php` -- Dashboard view
- `[MODIFY] resources/views/layouts/navigation.blade.php` -- Add Smart Suggestions link

---

### 4.2 Smart Lead Assign System

#### Concept
An intelligent system that analyzes sales person performance metrics and suggests optimal lead assignment. When new leads come in or need reassignment, the system recommends high-performing sales persons based on multiple performance indicators.

#### Performance Metrics to Track

Based on codebase analysis (DashboardController, ReportController), the following metrics are available:

**Conversion Metrics:**
| Metric | Calculation | Weight |
|--------|-------------|--------|
| `conversion_rate` | (Conversions / Assigned Leads) × 100 | High |
| `total_conversions` | Count of conversions in period | Medium |
| `avg_deal_value` | Average deal value of conversions | Medium |
| `total_revenue` | Sum of deal values | High |

**Activity Metrics:**
| Metric | Calculation | Weight |
|--------|-------------|--------|
| `calls_per_lead` | Total calls / Assigned leads | Medium |
| `response_rate` | Positive responses / Total calls × 100 | High |
| `follow_up_completion_rate` | Completed / Total follow-ups × 100 | Medium |

**Efficiency Metrics:**
| Metric | Calculation | Weight |
|--------|-------------|--------|
| `avg_days_to_conversion` | Avg days from lead creation to conversion | High |
| `workload_capacity` | Current assigned active leads | Medium |
| `lead_aging` | Avg age of active leads | Low |

**Source/Service Specialization:**
| Metric | Description |
|--------|-------------|
| `source_performance` | Conversion rate by lead source (WhatsApp, Messenger, Website) |
| `service_performance` | Conversion rate by service type |

#### Scoring Algorithm

```
Performance Score = (
    (conversion_rate × 0.25) +
    (response_rate × 0.20) +
    (follow_up_completion_rate × 0.15) +
    (normalized_revenue × 0.15) +
    (inverse_workload × 0.15) +
    (source_match_bonus × 0.05) +
    (service_match_bonus × 0.05)
) × 100
```

**Workload Balancing:**
- Inverse workload = 1 - (current_leads / max_capacity)
- Prevents overloading high performers
- Admin can set max capacity per user

**Source/Service Matching:**
- If sales person excels at WhatsApp leads, prioritize WhatsApp leads to them
- Historical analysis of which sources/services each person converts best

#### Assignment Modes

1. **Suggestion Mode** (Default): Shows ranked list of sales persons with scores, admin makes final decision
2. **Auto-Assign Mode**: Automatically assigns to highest scorer (requires admin toggle)
3. **Round-Robin with Performance**: Cycles through top performers only

#### Database Schema

**Table: `sales_performance_cache`** (for faster queries)
```sql
id (primary key)
user_id (foreign key -> users.id, unique)
period_start (date) -- Usually start of month
period_end (date)
total_leads (integer)
total_conversions (integer)
conversion_rate (decimal)
total_calls (integer)
positive_responses (integer)
response_rate (decimal)
total_follow_ups (integer)
completed_follow_ups (integer)
follow_up_rate (decimal)
total_revenue (decimal)
avg_deal_value (decimal)
avg_days_to_conversion (decimal, nullable)
performance_score (decimal) -- Calculated composite score
source_stats (json) -- {WhatsApp: {leads: x, conversions: y}, ...}
service_stats (json) -- {Website: {leads: x, conversions: y}, ...}
calculated_at (timestamp)
created_at, updated_at
```

**Table: `lead_assignment_settings`**
```sql
id (primary key)
mode (enum: 'suggestion', 'auto', 'round_robin')
max_leads_per_person (integer, default 50)
include_inactive_users (boolean, default false)
weight_conversion_rate (decimal, default 0.25)
weight_response_rate (decimal, default 0.20)
weight_follow_up_rate (decimal, default 0.15)
weight_revenue (decimal, default 0.15)
weight_workload (decimal, default 0.15)
weight_source_match (decimal, default 0.05)
weight_service_match (decimal, default 0.05)
updated_by (foreign key -> users.id)
created_at, updated_at
```

#### Implementation Files

- `[NEW] app/Models/SalesPerformanceCache.php`
- `[NEW] app/Models/LeadAssignmentSetting.php`
- `[NEW] database/migrations/xxxx_create_sales_performance_cache_table.php`
- `[NEW] database/migrations/xxxx_create_lead_assignment_settings_table.php`
- `[NEW] app/Services/SalesPerformanceService.php` -- Calculate & cache metrics
- `[NEW] app/Services/SmartLeadAssignService.php` -- Assignment logic
- `[NEW] app/Http/Controllers/SmartAssignController.php`
- `[NEW] app/Console/Commands/CalculateSalesPerformance.php` -- Scheduled job
- `[NEW] resources/views/smart-assign/index.blade.php` -- Assignment dashboard
- `[NEW] resources/views/smart-assign/settings.blade.php` -- Weight configuration
- `[MODIFY] app/Http/Controllers/LeadController.php` -- Add suggestion endpoint

---

### 4.3 Smart Suggestions Dashboard

A unified dashboard showing both Auto Follow-up suggestions and Smart Lead Assignment recommendations.

#### UI Components

1. **Auto Follow-up Section:**
   - List of leads matching active rules with scores
   - Quick actions: Schedule Follow-up, Make Call, View Lead
   - Filter by rule, priority, date range
   - Bulk action: Schedule follow-ups for selected leads

2. **Smart Lead Assign Section:**
   - Unassigned leads list (or leads to reassign)
   - Sales person cards with performance scores
   - Drag-and-drop or click-to-assign interface
   - Performance comparison view

3. **Rule Management (Tab/Modal):**
   - Create/Edit/Delete rules
   - Enable/Disable rules
   - Preview matching leads

4. **Settings (Admin Only):**
   - Assignment mode configuration
   - Weight adjustments
   - Performance calculation schedule

---

### TODO CHECKLIST - Step 4: Smart Suggestions

#### Step 4.1: Auto Follow-up Rules
- [ ] 4.1.1 Create follow_up_rules and follow_up_rule_conditions migrations
- [ ] 4.1.2 Create FollowUpRule and FollowUpRuleCondition models
- [ ] 4.1.3 Create FollowUpRuleController with CRUD
- [ ] 4.1.4 Create AutoFollowUpService for rule matching engine
- [ ] 4.1.5 Create rule management views (index, create, edit)
- [ ] 4.1.6 Add available fields configuration (dropdown options)
- [ ] 4.1.7 Create rule preview/test functionality
- [ ] 4.1.8 Test rule matching with sample data

#### Step 4.2: Smart Lead Assignment
- [ ] 4.2.1 Create sales_performance_cache migration
- [ ] 4.2.2 Create lead_assignment_settings migration
- [ ] 4.2.3 Create SalesPerformanceCache and LeadAssignmentSetting models
- [ ] 4.2.4 Create SalesPerformanceService for metrics calculation
- [ ] 4.2.5 Create SmartLeadAssignService for assignment logic
- [ ] 4.2.6 Create SmartAssignController
- [ ] 4.2.7 Create Artisan command for performance calculation
- [ ] 4.2.8 Create smart-assign views (index, settings)
- [ ] 4.2.9 Add suggestion endpoint to LeadController
- [ ] 4.2.10 Test assignment suggestions

#### Step 4.3: Smart Suggestions Dashboard
- [ ] 4.3.1 Create unified smart-suggestions dashboard view
- [ ] 4.3.2 Add Smart Suggestions link to navigation
- [ ] 4.3.3 Implement quick actions (schedule follow-up, assign lead)
- [ ] 4.3.4 Add bulk action support
- [ ] 4.3.5 Create admin settings panel
- [ ] 4.3.6 Write feature tests for entire system

---

### Research References

**Industry Best Practices for Lead Scoring & Assignment:**

1. **HubSpot Lead Scoring**: Uses predictive lead scoring based on behavior and demographics
2. **Salesforce Einstein**: AI-powered lead scoring with customizable rules
3. **Zoho CRM Assignment Rules**: Round-robin, load balancing, territory-based assignment
4. **Pipedrive Lead Distribution**: Performance-based with workload consideration

**Key Insights Applied:**
- Combine rule-based (explicit criteria) with performance-based (implicit learning)
- Allow users to customize weights for their specific business needs
- Cache performance metrics to avoid expensive real-time calculations
- Provide transparency in scoring (show why a lead is suggested)
- Balance workload to prevent burnout of top performers
- Consider source/service specialization for better matching
