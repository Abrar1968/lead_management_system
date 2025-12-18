# Comprehensive 3-Step Feature Implementation Plan

This document details the technical implementation for the three requested features: Meeting Notifications, Converted Clients, and Demo Section.

---

## Step 1: Meeting Schedule Notification

**Objective:** Provide audio and visual notifications to users (Admin/Lead) for scheduled meetings upon login and 1 hour prior to the meeting.

### 1.1 Database & Models

-   **No Schema Changes Required**: utilizing existing `meetings` table.
-   **Model**: `App\Models\Meeting`.
-   **Attributes Used**: `meeting_date`, `meeting_time`, `lead_id`, `assigned_to` (via Lead relationship).

### 1.2 Backend Implementation

**Controller**: `App\Http\Controllers\NotificationController`

-   **Method**: `checkUpcomingMeetings()`
-   **Logic**:
    1.  Identify current user.
    2.  Query `meetings` table:
        -   `meeting_date` = Today.
        -   `outcome` = 'Pending'.
        -   For Sales Person: `lead.assigned_to` = `auth()->id()`.
        -   For Admin: All meetings.
    3.  **Alert Logic**:
        -   **Login Reminder**: If `last_login` was > X hours ago (or handled via session flag `login_alert_shown`), include all today's meetings.
        -   **1-Hour Warning**: Filter meetings where `meeting_time` is between `now()` and `now() + 60 minutes`.
    4.  **Return JSON**:
        ```json
        {
            "alert": true,
            "meetings": [
                {
                    "id": 1,
                    "client_name": "John Doe",
                    "time": "14:00",
                    "diff_minutes": 45
                }
            ]
        }
        ```

**Routes**:

-   `GET /notifications/check` -> `NotificationController@checkUpcomingMeetings`

### 1.3 Frontend Implementation

**File**: `resources/views/layouts/app.blade.php`

-   **Technology**: Alpine.js + HTML5 Audio.
-   **Audio**: Place `notification.mp3` in `public/assets/sounds/`.
-   **Polling Script**:
    ```javascript
    setInterval(() => {
        fetch("/notifications/check")
            .then((res) => res.json())
            .then((data) => {
                if (data.alert) {
                    playAudio();
                    showPopup(data.meetings);
                }
            });
    }, 300000); // Poll every 5 minutes
    ```
-   **UI**: A Tailwind CSS Modal or Toast appearing at top-right.

---

## Step 2: Converted Clients (with Dynamic Fields)

**Objective:** Import converted leads into a Client section where Admin can dynamically add fields (Text, Image, Link).

### 2.1 Database Schema

**Core Table**: `client_details` (Existing or Update)
**Dynamic Fields Table**: `field_definitions`

```php
Schema::create('field_definitions', function (Blueprint $table) {
    $table->id();
    $table->string('model_type'); // 'client' or 'demo'
    $table->string('name'); // e.g., 'passport_copy'
    $table->string('label'); // e.g., 'Passport Copy'
    $table->string('type'); // 'text', 'image', 'link'
    $table->timestamps();
});
```

**Storage for Values**:

-   **Option A (Requested)**: `Schema::table` modifications.
    -   Admin adds field -> System runs `Schema::table('client_details', fn($t) => $t->string('field_name')->nullable())`.
    -   _Note_: This requires careful handling of column naming to prevent SQL injection/errors.

### 2.2 Backend Implementation

**Controllers**:

-   `ClientFieldController`: Manages `FieldDefinitions`.
    -   `store`: Validates input, creates `FieldDefinition`, and runs `Schema::table` to add column to `client_details`.
    -   `destroy`: Drops column and deletes definition.
-   `ClientController`:
    -   `update`: Handles standard fields AND loops through `FieldDefinition::where('model_type', 'client')` to handle dynamic inputs.
    -   **Image Handling**:
        -   Use `Standard PHP GD` / Laravel Storage (without facade if strictly requested, specifically `request()->file('image')->store()`).
        -   Save path to the dynamic column.

**Conversion Logic**:

-   When Lead Status -> 'Converted':
    -   Auto-create `ClientDetail`.
    -   Copy `deal_value` / `price`.
    -   Copy `commission`.
    -   Populate `client_details` with Basic Info from Lead.

### 2.3 Frontend Implementation

-   **Admin Settings**: Interface to "Add New Field" (Name, Type [Select: Text, Image, Link]).
-   **Client View/Edit**:
    -   Loop `foreach($fieldDefinitions as $field)`
    -   If Type == Image: Show File Input + Preview.
    -   If Type == Link: Show URL Input.
    -   If Type == Text: Show Text Input.

---

## Step 3: Demo Section (Dynamic Fields)

**Objective:** A separate section for "Demo" entries with the same dynamic field capabilities as Converted Clients.

### 3.1 Database Schema

**Table**: `demos`

```php
Schema::create('demos', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
    // Dynamic columns added here via Schema::table
});
```

### 3.2 Backend Implementation

**Reusability**:

-   Use the same `ClientFieldController` logic but pass `model_type = 'demo'`.
-   Target table `demos` instead of `client_details`.

**Controller**: `DemoController`

-   Standard CRUD.
-   In `create`/`edit`, fetch `FieldDefinition::where('model_type', 'demo')->get()`.
-   Pass these definitions to View to render inputs.

### 3.3 Frontend Implementation

-   **Sidebar**: Add "Demo Section" link.
-   **Views**:
    -   `demos.index`: List demos.
    -   `demos.create/edit`: Render dynamic forms.

---

## Summary of Work

1.  **Notification System**: Logic driven by `NotificationController` and Alpine.js polling.
2.  **Dynamic Field Engine**: `FieldDefinition` model paired with runtime `Schema::table` alterations (as requested).
3.  **Client & Demo Integration**: Integrating the dynamic engine into these two specific modules.
