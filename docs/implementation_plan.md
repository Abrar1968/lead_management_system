# Fixing Critical Lead Management Issues

## Goal Description
Address 6 critical issues in the Lead Management System:
1.  **Lead Remarks Visibility**: Show initial remarks in Daily View.
2.  **Auto Follow-up Rules**: Implement actual automation (scheduled command) for rules created in "Smart AI".
3.  **Custom Fields**: Fix saving and display of Image/Document custom fields.
4.  **Meeting Reminders**: trigger reminders 1 hour before AND 5 minutes before.
5.  **Duplicate Lead Entry**: Redirect to existing lead instead of crashing with SQL error.
6.  **Header Search**: Enable global search by phone number from the header.

## User Review Required
> [!IMPORTANT]
> **Auto Follow-up**: Currently, "Smart AI" only provides *suggestions*. I will implement a **Scheduled Command** (`leads:process-followups`) to automatically create "Pending" follow-ups for matching leads. This changes the behavior from "Passive Suggestion" to "Active Automation".

> [!NOTE]
> **Custom Fields on Dashboard**: The user mentioned "main dashboard" for custom fields. I will ensure they work in **Lead Details** and **Lead Creation**. Displaying arbitrary custom fields on the main stats dashboard is disjointed; I will prioritize Lead Details first.

## Proposed Changes

### 1. Lead Remarks Visibility
#### [MODIFY] [daily.blade.php](file:///f:/projects/lead_ms/resources/views/leads/daily.blade.php)
- Add a tooltip or section to display `$lead->initial_remarks` in the leads table.

### 2. Auto Follow-up Rules
#### [NEW] [ProcessAutoFollowUps.php](file:///f:/projects/lead_ms/app/Console/Commands/ProcessAutoFollowUps.php)
- Create a new Artisan command `leads:process-auto-followups`.
- Use [AutoFollowUpService](file:///f:/projects/lead_ms/app/Services/AutoFollowUpService.php#12-323) to find matching leads.
- Create [FollowUp](file:///f:/projects/lead_ms/app/Services/AutoFollowUpService.php#12-323) records automatically for matches (avoiding duplicates).
#### [MODIFY] [routes/console.php](file:///f:/projects/lead_ms/routes/console.php)
- Schedule the command to run hourly.

### 3. Custom Fields (Image/Document)
#### [MODIFY] [LeadController.php](file:///f:/projects/lead_ms/app/Http/Controllers/LeadController.php)
- Update [store](file:///f:/projects/lead_ms/app/Http/Controllers/FollowUpRuleController.php#57-90) and [update](file:///f:/projects/lead_ms/app/Http/Controllers/LeadController.php#98-117) methods to handle [FieldValue](file:///f:/projects/lead_ms/app/Services/AutoFollowUpService.php#131-161) saving.
- Handle file uploads for fields of type `file` or `image`.
#### [MODIFY] [create.blade.php](file:///f:/projects/lead_ms/resources/views/leads/create.blade.php)
- Add loop to render active [FieldDefinition](file:///f:/projects/lead_ms/app/Models/FieldDefinition.php#8-58) inputs.
#### [MODIFY] [show.blade.php](file:///f:/projects/lead_ms/resources/views/leads/show.blade.php)
- Display custom field values (render images/download links for docs).

### 4. Meeting Popup Reminder
#### [MODIFY] [app.blade.php](file:///f:/projects/lead_ms/resources/views/layouts/app.blade.php)
- Update `meetingNotifications` Alpine component.
- Track notified meetings by type (`1hr`, `5min`) in `localStorage`.
- Support multiple notifications per meeting.
- Fix audio playback.
#### [MODIFY] [NotificationController.php](file:///f:/projects/lead_ms/app/Http/Controllers/NotificationController.php)
- Ensure it returns meetings in a wide enough window (e.g., 0-70 mins) to catch both triggers.

### 5. Duplicate Lead Handling
#### [MODIFY] [LeadController.php](file:///f:/projects/lead_ms/app/Http/Controllers/LeadController.php)
- In [store](file:///f:/projects/lead_ms/app/Http/Controllers/FollowUpRuleController.php#57-90) method, check for existing lead by `phone_number`.
- If exists, redirect to `leads.show` with a flash message.
#### [MODIFY] [LeadService.php](file:///f:/projects/lead_ms/app/Services/LeadService.php)
- Add retry logic to [generateLeadNumber](file:///f:/projects/lead_ms/app/Services/LeadService.php#65-90) to prevent unique constraint violations on the random number part.

### 6. Header Search
#### [MODIFY] [app.blade.php](file:///f:/projects/lead_ms/resources/views/layouts/app.blade.php)
- Wrap header search input in a `<form>` pointing to `leads.index`.
- Add `name="search"`.
#### [MODIFY] [LeadController.php](file:///f:/projects/lead_ms/app/Http/Controllers/LeadController.php)
- Ensure [index](file:///f:/projects/lead_ms/app/Http/Controllers/SmartSuggestionsController.php#17-54) method filters search by `phone_number`.

## Verification Plan

### Automated Tests
- None (User environment restricted).

### Manual Verification
1.  **Duplicate Lead**:
    - Try to create a lead with an existing phone number.
    - Verify redirection to the existing lead page.
2.  **Lead Remarks**:
    - Create a lead with "Test Remarks".
    - Check "Daily Leads" view to see if remarks are visible.
3.  **Custom Fields**:
    - Create a Custom Field (Image type).
    - Create a Lead and upload an image.
    - View Lead Details and verify the image is shown.
4.  **Meeting Reminder**:
    - Create a meeting for 55 mins from now. Wait 5 mins (modify poll time to 1 min for test). Verify "1 Hour" warning.
    - Create a meeting for 6 mins from now. Wait. Verify "5 Minute" warning.
5.  **Auto Follow-up**:
    - Create a Rule (e.g., "Status is New").
    - Run `php artisan leads:process-auto-followups`.
    - Verify a new Follow-up is created for matching leads.
6.  **Header Search**:
    - Enter a phone number in the top header search.
    - Verify it filters to that lead.
