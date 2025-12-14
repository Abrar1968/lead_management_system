# Software Requirements Specification (SRS)
## WhatsApp CRM Lead Management System

### Project Overview
**Project Name:** WhatsApp CRM Lead Management System  
**Version:** 1.1 (Updated)  
**Development Timeline:** 1 Day (8 working hours)  
**Grade Level:** Intermediate  
**Primary Language:** Bangla/English Mixed Interface

---

## 1. Technical Architecture

### 1.1 Technology Stack
- **Backend Framework:** Laravel 12 with Service Pattern
- **Frontend Framework:** Alpine.js for reactivity
- **Styling:** Tailwind CSS v4
- **Template Engine:** Blade (HTML in Blade files)
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Breeze (simple, fast setup)

### 1.2 Architecture Pattern
The application follows a **Service-Repository Pattern** with clear separation of concerns:

**Layer Structure:**
- **Controllers:** Handle HTTP requests and responses
- **Services:** Contain business logic and orchestrate operations
- **Repositories:** Handle database queries and data access
- **Models:** Eloquent ORM models
- **Views:** Blade templates with Alpine.js for interactivity

**Directory Structure:**
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── LeadController.php
│   │   ├── DailyLeadController.php
│   │   ├── UserManagementController.php
│   │   ├── CommissionController.php
│   │   ├── ReportController.php
│   │   └── DashboardController.php
│   └── Requests/
│       ├── StoreLeadRequest.php
│       └── UpdateLeadRequest.php
├── Services/
│   ├── LeadService.php
│   ├── CommissionService.php
│   ├── ReportService.php
│   └── SearchService.php
├── Repositories/
│   ├── LeadRepository.php
│   ├── CommissionRepository.php
│   └── UserRepository.php
└── Models/
    ├── Lead.php
    ├── FollowUp.php
    ├── Meeting.php
    ├── Conversion.php
    ├── ExtraCommission.php
    └── User.php
```

---

## 2. Database Design

### 2.1 Core Tables

**users table:**
```sql
id (primary key)
name (varchar 255)
email (varchar 255, unique)
phone (varchar 20)
password (varchar)
role (enum: 'admin', 'sales_person')
default_commission_rate (decimal 10,2, default 500.00)
commission_type (enum: 'fixed', 'percentage', default 'fixed')
is_active (boolean)
created_at, updated_at
```

**leads table:**
```sql
id (primary key)
lead_number (varchar, auto-generated, unique)
source (enum: 'WhatsApp', 'Messenger', 'Website')
client_name (varchar 255)
phone_number (varchar 20, indexed)
email (varchar 255, nullable)
company_name (varchar 255, nullable)
service_interested (enum: 'Website', 'Software', 'CRM', 'Marketing')
lead_date (date, indexed)
lead_time (time)
is_repeat_lead (boolean, default false)
previous_lead_ids (json, nullable)
priority (enum: 'High', 'Medium', 'Low', default 'Medium')
assigned_to (foreign key -> users.id)
created_at, updated_at
```

**lead_contacts table:**
```sql
id (primary key)
lead_id (foreign key -> leads.id, cascades on delete)
daily_call_made (boolean)
call_date (date, indexed)
call_time (time)
caller_id (foreign key -> users.id)
response_status (enum: 'Interested', '50%', 'Yes', 'Call Later', 'No Response', 'No', 'Phone off')
notes (text, nullable)
created_at, updated_at
```

**follow_ups table:**
```sql
id (primary key)
lead_id (foreign key -> leads.id, cascades on delete)
follow_up_date (date, indexed)
follow_up_time (time)
notes (text)
status (enum: 'Pending', 'Completed', 'Cancelled', default 'Pending')
created_by (foreign key -> users.id)
created_at, updated_at
```

**meetings table:**
```sql
id (primary key)
lead_id (foreign key -> leads.id, cascades on delete)
meeting_date (date, indexed)
meeting_time (time)
meeting_type (enum: 'Online', 'Physical')
outcome (enum: 'Positive', 'Neutral', 'Negative', nullable)
notes (text, nullable)
created_at, updated_at
```

**conversions table:**
```sql
id (primary key)
lead_id (foreign key -> leads.id)
converted_by (foreign key -> users.id)
conversion_date (date, indexed)
deal_value (decimal 10,2)
commission_rate_used (decimal 10,2) -- Stores the rate at time of conversion
commission_type_used (enum: 'fixed', 'percentage') -- Stores type at time of conversion
commission_amount (decimal 10,2) -- Calculated and stored
package_plan (varchar 255)
advance_paid (boolean)
payment_method (varchar 100, nullable)
signing_date (date, nullable)
signing_time (time, nullable)
delivery_deadline (date, nullable)
expected_delivery_date (date, nullable)
actual_delivery_date (date, nullable)
project_status (enum: 'In Progress', 'Delivered', 'On Hold')
commission_paid (boolean, default false)
created_at, updated_at
```

**extra_commissions table:**
```sql
id (primary key)
user_id (foreign key -> users.id, cascades on delete)
commission_type (varchar 100) -- e.g., 'Bonus', 'Incentive', 'Target Achievement'
amount (decimal 10,2)
description (text)
date_earned (date, indexed)
related_conversion_id (foreign key -> conversions.id, nullable) -- If related to specific conversion
status (enum: 'Pending', 'Approved', 'Paid', default 'Pending')
approved_by (foreign key -> users.id, nullable)
created_at, updated_at
```

**client_details table:**
```sql
id (primary key)
conversion_id (foreign key -> conversions.id)
address (text, nullable)
billing_info (text, nullable)
support_contact_person (varchar 255, nullable)
whatsapp_group_created (boolean, default false)
feedback (text, nullable)
remarketing_eligible (boolean, default false)
created_at, updated_at
```

### 2.2 Database Indexes for Performance
```sql
INDEX idx_phone_number ON leads(phone_number)
INDEX idx_lead_date ON leads(lead_date) -- Critical for daily views
INDEX idx_assigned_to ON leads(assigned_to)
INDEX idx_call_date ON lead_contacts(call_date)
INDEX idx_follow_up_date ON follow_ups(follow_up_date, status)
INDEX idx_meeting_date ON meetings(meeting_date)
INDEX idx_conversion_date ON conversions(conversion_date)
INDEX idx_converted_by ON conversions(converted_by)
INDEX idx_extra_commission_date ON extra_commissions(date_earned)
INDEX idx_extra_commission_user ON extra_commissions(user_id, status)
```

---

## 3. Functional Requirements

### 3.1 User Authentication & Authorization

**Features:**
- Simple login/logout system using Laravel Breeze
- Two user roles: Admin and Sales Person
- Admin can manage all users and see all data
- Sales Person can only see assigned leads and their own conversions
- Users can update their own commission settings

**User Stories:**
- As an admin, I can create new sales person accounts
- As an admin, I can view all leads and reports
- As a sales person, I can view my assigned leads
- As a sales person, I can set my own commission rate
- As a sales person, I can update lead status and add follow-ups

### 3.2 Lead Management with Daily/Monthly Views

**Core Features:**

**Add New Lead:**
- Form to capture all lead basic information
- Automatic lead number generation (format: LEAD-YYYYMMDD-XXX)
- Lead date defaults to today but can be changed
- Automatic repeat lead detection by phone number
- If repeat lead detected, show previous lead history
- Assign lead to sales person (admin can assign, or auto-assign to logged-in user)

**Daily Lead View (New Primary View):**
This becomes the default landing page for leads, organized by date for maximum clarity and tracking effectiveness.

The daily view presents leads in a date-focused interface where users can easily see all leads for a specific day. Think of it like a daily planner where each day's leads are grouped together. You can navigate through dates using previous/next day buttons or jump to a specific date using a date picker.

**Interface Components:**
- Date selector at the top with previous/next day navigation arrows
- Quick jump buttons for "Today", "Yesterday", "This Week"
- All leads for the selected date displayed in a clean list or card format
- Summary statistics for that day: total leads, calls made, interested count
- Filter options: source, service type, assigned person, priority
- Each lead card shows: lead number, client name, phone, source, status badge, quick action buttons

**Technical Implementation:**
The controller queries leads where lead_date equals the selected date, with efficient eager loading of relationships. Pagination is applied if a single day has many leads. The interface uses Alpine.js to handle date navigation without page reloads, and URL parameters maintain the selected date so users can bookmark or share specific day views.

**Monthly Lead View:**
This view provides an aggregated overview of all leads across a month, perfect for management review and monthly planning.

**Interface Components:**
- Month/Year selector at the top
- Calendar visualization showing lead count per day (like a heatmap)
- Summary cards: total monthly leads, conversion rate, total revenue
- Tabbed sections: "All Leads", "By Source", "By Sales Person", "By Status"
- Exportable monthly report
- Comparison with previous month

**Technical Implementation:**
The system queries all leads within the selected month using whereMonth and whereYear conditions. Data is grouped by relevant dimensions for the tabbed views. A simple calendar grid is generated showing each day's lead count, with color intensity indicating volume. This uses aggregate queries for performance rather than loading all individual leads.

**Lead Detail View:**
- Comprehensive view showing all lead information
- Timeline view of all interactions (calls, follow-ups, meetings)
- Quick status indicators with color coding
- Edit functionality with form validation

**Search Functionality:**
- Global search bar in navigation
- Search by phone number (primary)
- Search by client name, company name, lead number
- Instant results with highlighting
- Click result to view full lead details

**Lead List View (All Leads):**
- Paginated table with sorting capabilities
- Filter by: date range, source, status, assigned person
- Color-coded priority indicators
- Quick action buttons (view, edit, add follow-up)
- Bulk actions (assign multiple leads, export)
- Switch between daily view and this comprehensive list

### 3.3 Contact & Follow-Up Tracking

**Daily Call Tracking:**
- Quick form to log daily calls
- Capture call date, time, caller, response status
- Add notes for each call
- View call history in timeline format

**Follow-Up Management:**
- Schedule follow-ups with date and time
- Add detailed notes
- Status tracking (Pending/Completed/Cancelled)
- Dashboard widget showing today's follow-ups
- Notifications for pending follow-ups (visual indicators)

**Meeting Management:**
- Schedule meetings with type (Online/Physical)
- Record meeting outcomes
- Link meetings to specific leads
- Calendar view of all scheduled meetings

### 3.4 Conversion Management

**Conversion Process:**
- Convert lead button on lead details page
- Comprehensive conversion form capturing all deal details
- Commission calculation based on user's current rate and type (fixed or percentage)
- Display calculated commission before confirming
- Automatic assignment to converting user
- Change lead status to "Converted"

**Commission Calculation Logic:**
When a lead is converted, the system looks at the converting user's current commission settings and performs the calculation based on their commission type.

For fixed commission type, the system simply uses the amount specified in the user's default commission rate field. For percentage type, it calculates the commission as a percentage of the deal value. For example, if a user has a ten percent commission rate and converts a deal worth 50,000 BDT, they earn 5,000 BDT.

The critical design decision here is that we store both the rate used and the calculated amount at the time of conversion. This creates a permanent record that doesn't change even if the user later updates their commission settings. This protects both the business and the sales person by maintaining accurate historical records.

**Deal Tracking:**
- Track package/plan details
- Monitor payment status
- Delivery timeline management
- Project status updates

**Client Details (Post-Conversion):**
- Capture detailed client information
- Billing and support contact details
- Track WhatsApp group creation status
- Client feedback collection

### 3.5 Enhanced Commission System

**User Commission Settings:**
Each user has a dedicated commission settings page where they can configure their own commission structure. This gives sales people control over their earnings while maintaining system oversight.

**Commission Configuration Options:**
- Commission Type: Fixed Amount or Percentage
- Default Rate: The standard commission they earn per conversion
- Minimum Commission: Optional floor amount they should always earn
- Maximum Commission: Optional ceiling for percentage-based commissions

**User Interface for Commission Settings:**
Sales persons access this through their profile menu with a dedicated "Commission Settings" option. The interface clearly shows their current settings and a simple form to update them. When they change settings, the system shows a confirmation explaining that this only affects future conversions, not past earnings.

Admins can view all users' commission settings but the design philosophy is that users manage their own rates. However, admins have an override capability for special circumstances.

**Automatic Commission Calculation:**
- Commission calculated automatically during conversion based on user's current settings
- System stores: commission_rate_used, commission_type_used, and calculated commission_amount
- This creates an immutable record of the commission earned
- Historical conversions are never recalculated if settings change

**Extra Commission System:**
Beyond the standard per-conversion commission, the system supports additional commission types for flexibility and incentives.

**Extra Commission Types:**
- Performance Bonuses: Monthly or quarterly targets achievement
- Special Project Incentives: Extra payment for complex or rush projects
- Referral Bonuses: Commission for bringing in new clients
- Milestone Bonuses: Rewards for reaching conversion count milestones
- Seasonal Incentives: Holiday or campaign-specific bonuses
- Custom: Any other type the business needs

**Adding Extra Commission:**
Admins can add extra commissions for users through a dedicated interface. The form includes fields for the user, commission type (dropdown or custom), amount, description, date earned, and optionally linking it to a specific conversion. Extra commissions go through an approval workflow where they start as "Pending", can be "Approved" by admins, and eventually marked as "Paid" when disbursed.

Sales persons can see their extra commissions in their profile but cannot add them directly, maintaining proper financial controls. The system shows pending, approved, and paid extra commissions separately for transparency.

**Commission Report:**
- View all commissions by user (standard + extra)
- Filter by date range (daily, monthly, yearly)
- Separate totals for standard conversion commissions and extra commissions
- Grand total of all earnings
- Paid/unpaid status tracking
- Export capability for accounting purposes

### 3.6 Reporting & Analytics

**Dashboard Metrics:**
- Today's summary: leads received, calls made, conversions
- Weekly performance graph
- Conversion ratio calculation and display
- Top performers leaderboard
- Pending follow-ups count
- Today's revenue and commission

**Daily Reports:**
The daily reporting system provides granular day-by-day insights that are essential for tracking daily operations and identifying trends.

**Date selector interface:**
Users select a specific date using a date picker, with quick access buttons for today, yesterday, and the past week. When a date is selected, the system instantly generates a comprehensive report for that day.

**Report Contents:**
- Total leads received on that date
- Total calls made on that date
- Interested leads count (positive response statuses)
- Converted leads count
- Conversion percentage for that day
- Total revenue from conversions on that date
- Total commission paid out for that day
- List of all leads with their current status
- Breakdown by source (WhatsApp, Messenger, Website)
- Breakdown by assigned sales person

**Monthly Reports:**
Similar structure but aggregated across the entire month, with week-by-week breakdowns and comparison to previous month.

**User Performance Reports:**
- Individual user statistics
- Comparison with previous month
- Leads assigned vs converted ratio
- Total standard commission earned
- Total extra commission earned
- Combined commission total
- Average response time
- Meeting conversion rate
- Daily activity calendar

**Export Functionality:**
- Export reports to Excel/CSV
- Date range selection
- Filter by user, source, status

### 3.7 User Management (Admin Only)

**User List:**
- View all sales persons
- Status (Active/Inactive)
- Current commission rate display
- Performance summary preview

**User Profile Page:**
- Personal information
- Commission settings (view only for admin, editable for user)
- Total leads assigned
- Total conversions
- Standard commission earned (monthly, yearly, total)
- Extra commission earned (monthly, yearly, total)
- Combined commission total
- Performance analytics with graphs
- Month-over-month comparison
- Recent activity timeline

**Add/Edit User:**
- Create new sales person accounts
- Edit user information
- Set initial commission rate and type
- Activate/deactivate users
- Reset passwords

**Extra Commission Management (Admin):**
- Add extra commission for any user
- Approve pending extra commissions
- Mark extra commissions as paid
- View history of all extra commissions
- Export commission reports for accounting

---

## 4. Non-Functional Requirements

### 4.1 Performance Requirements

**Page Load Time:**
- Dashboard: under 1 second
- Daily lead view: under 0.8 seconds (optimized query)
- Monthly lead view: under 1.5 seconds
- Lead list with 100 records: under 1.5 seconds
- Search results: under 0.5 seconds (with proper indexing)

**Database Optimization:**
- Eager loading relationships to prevent N+1 queries
- Database indexes on frequently queried columns (especially lead_date)
- Query result caching for reports (5-minute cache for daily stats)
- Separate queries for daily aggregations vs detail views

**Frontend Performance:**
- Alpine.js for minimal JavaScript overhead
- Lazy loading for data tables
- Debounced search input
- Optimized Tailwind CSS (purge unused classes)
- Date navigation without full page reloads

### 4.2 Usability Requirements

**Responsive Design:**
- Mobile-first approach
- Breakpoints: 640px (sm), 768px (md), 1024px (lg), 1280px (xl)
- Touch-friendly buttons (minimum 44x44px)
- Collapsible sidebar on mobile

**User Interface:**
- Clean, modern design with professional color scheme
- Consistent spacing and typography
- Clear visual hierarchy
- Intuitive navigation
- Date-focused interface for leads
- Helpful error messages and validation feedback
- Loading states for async operations
- Clear commission calculations displayed

**Accessibility:**
- Proper color contrast ratios
- Keyboard navigation support
- Screen reader friendly labels
- Focus indicators on interactive elements

### 4.3 Security Requirements

**Authentication:**
- Secure password hashing (Laravel's bcrypt)
- CSRF protection on all forms
- Session timeout after 2 hours of inactivity

**Authorization:**
- Role-based access control
- Admin-only routes protected by middleware
- Users can only access their assigned leads
- Users can only edit their own commission settings
- Only admins can add/approve extra commissions

**Data Validation:**
- Server-side validation on all inputs
- Phone number format validation
- Email format validation
- Commission rate validation (positive numbers only)
- Date validation (lead_date cannot be future date)
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade auto-escaping)

### 4.4 Reliability Requirements

**Data Integrity:**
- Database transactions for critical operations (conversions)
- Foreign key constraints
- Cascade deletes for related records
- Soft deletes for important records (leads, conversions)
- Immutable commission records (stored at conversion time)

**Error Handling:**
- Graceful error messages for users
- Detailed error logging for developers
- Database rollback on failed transactions
- Commission calculation validation before save

---

## 5. User Interface Design Guidelines

### 5.1 Design Principles

**Color Scheme:**
- Primary: Blue (#3B82F6) - represents trust and professionalism
- Success: Green (#10B981) - for conversions and positive actions
- Warning: Amber (#F59E0B) - for pending items
- Danger: Red (#EF4444) - for negative status
- Neutral: Gray shades for text and backgrounds
- Commission: Purple (#8B5CF6) - for earnings and financial data

**Typography:**
- Font Family: Inter (web-safe fallback: system fonts)
- Headings: 600-700 font weight
- Body: 400 font weight
- Clear hierarchy with size differences

**Spacing:**
- Consistent use of Tailwind spacing scale
- Card padding: p-6
- Section gaps: gap-6
- Form field spacing: space-y-4

### 5.2 Component Design

**Dashboard Layout:**
- Sidebar navigation (collapsible on mobile)
- Top navigation bar with date selector and search
- Main content area with cards for metrics
- Responsive grid layout for stat cards (1 column mobile, 2-4 columns desktop)

**Daily Lead View:**
- Date navigation bar at top (prev/next buttons + date picker)
- Summary cards for selected day's statistics
- Lead list below in card format (mobile) or table (desktop)
- Each lead card shows essential info with status badge
- Quick action buttons on each lead

**Commission Settings Interface:**
- Clear two-column form layout
- Toggle between Fixed/Percentage commission type
- Real-time calculation preview
- Warning message explaining settings only affect future conversions
- Save button with confirmation

**Forms:**
- Single column on mobile, 2 columns on desktop for wide forms
- Floating labels or clear label positioning
- Inline validation messages
- Visual feedback on input focus
- Disabled state styling for form submission

**Tables:**
- Striped rows for readability
- Hover effects on rows
- Sticky header on scroll
- Mobile: card-based layout instead of table
- Action buttons in last column

**Modal/Drawer:**
- Use for quick forms (add follow-up, quick call log, add extra commission)
- Overlay with backdrop blur
- Smooth slide-in animation
- Close on outside click or ESC key

**Search Bar:**
- Prominent position in top navigation
- Dropdown results with keyboard navigation
- Recent searches memory
- Clear search button

### 5.3 Interaction Patterns

**Loading States:**
- Skeleton screens for initial page load
- Spinner or progress bar for form submission
- Disabled buttons during async operations

**Success/Error Feedback:**
- Toast notifications (top-right corner)
- Auto-dismiss after 3-5 seconds
- Dismiss button for user control
- Special success message for commission updates

**Confirmation Dialogs:**
- For destructive actions (delete, cancel)
- For commission setting changes
- Clear action buttons (Confirm/Cancel)
- Explain consequences

**Date Navigation:**
- Smooth transitions between dates
- Loading indicator during data fetch
- URL updates to maintain browsable history
- Keyboard shortcuts (arrow keys for prev/next day)

---

## 6. Implementation Priority

### Phase 1 (MVP - First 6 Hours)
**Critical features for basic functionality:**
1. Authentication system (Breeze installation)
2. Database migrations with updated commission structure
3. Lead creation with lead_date focus
4. Daily lead view (primary view)
5. Basic monthly view
6. Basic search by phone number
7. Daily call logging
8. Commission settings page for users
9. Simple dashboard with key metrics
10. Basic user management

### Phase 2 (Hours 6-8)
**Enhanced features:**
1. Follow-up management
2. Conversion tracking with commission calculation
3. Extra commission management (admin)
4. User performance page with commission breakdown
5. Responsive design refinements
6. Daily and monthly reporting

### Phase 3 (Future Enhancements - Post Day 1)
**Nice-to-have features:**
1. Advanced analytics and charts
2. Commission payment tracking system
3. Email notifications
4. WhatsApp integration (actual messaging)
5. Calendar view for meetings and follow-ups
6. Mobile app (PWA)
7. Automated commission reports for accounting

---

## 7. Testing Strategy

### 7.1 Manual Testing Checklist

**Authentication:**
- Login with valid credentials
- Login with invalid credentials
- Logout functionality
- Session persistence

**Lead Management:**
- Create new lead with today's date
- Create lead with past date
- View daily leads for specific date
- Navigate between days (prev/next)
- View monthly leads
- Search lead by phone number
- Edit lead details
- Repeat lead detection

**Commission System:**
- User sets fixed commission rate
- User sets percentage commission rate
- Convert lead with fixed commission
- Convert lead with percentage commission
- Verify commission_rate_used is stored
- Change commission rate and verify new conversions use new rate
- Admin adds extra commission
- View commission breakdown in user profile

**Conversion:**
- Convert a lead with fixed commission
- Convert a lead with percentage commission
- Verify commission calculation is correct
- Verify commission assignment
- View conversion details
- Update project status

**User Management (Admin):**
- Create new user with initial commission settings
- View user performance with commission breakdown
- Add extra commission for user
- Edit user details
- Deactivate user

**Responsive Design:**
- Test on mobile (375px width)
- Test on tablet (768px width)
- Test on desktop (1920px width)
- Test date navigation on mobile

### 7.2 Database Testing
- Foreign key constraints working
- Cascade deletes functioning
- Data validation at database level
- Index performance on large datasets
- Commission calculations stored correctly

### 7.3 Commission Calculation Testing
**Test Cases:**
1. Fixed commission (500 BDT) on 10,000 BDT deal = 500 BDT
2. 10% commission on 10,000 BDT deal = 1,000 BDT
3. 5% commission on 50,000 BDT deal = 2,500 BDT
4. Change rate mid-month, verify old conversions unchanged
5. Extra commission adds correctly to total

---

## 8. Deployment Considerations

### 8.1 Environment Setup
- PHP 8.2+
- MySQL 8.0+
- Composer dependencies installed
- Node.js and npm for asset compilation

### 8.2 Configuration
- Environment variables properly set
- Database credentials configured
- App key generated
- Debug mode OFF in production
- Timezone set correctly for accurate lead_date

### 8.3 Performance Optimization
- PHP OPcache enabled
- Database connection pooling
- Compiled and minified assets
- Gzip compression enabled
- Query caching for daily/monthly aggregations

---

## 9. Maintenance and Scalability

### 9.1 Future Scalability Considerations

**Database:**
- Currently designed for up to 100,000 leads
- lead_date index supports fast daily queries
- Partitioning by month can be added for larger datasets
- Read replicas for reporting if needed

**Caching Strategy:**
- Redis cache for daily statistics (5-minute TTL)
- Cache daily report results
- Cache monthly aggregations
- Session storage in cache (future)

**Code Maintainability:**
- Service pattern allows easy testing
- CommissionService handles all commission logic
- Repository pattern isolates database logic
- Clear separation of concerns
- Well-documented code

### 9.2 Monitoring Requirements
- Laravel logs monitoring
- Database query performance (especially date-based queries)
- User activity tracking
- Commission calculation accuracy monitoring
- Error rate monitoring

---

## 10. Glossary

**Lead:** A potential client who has shown interest in services
**Lead Date:** The date when the lead was received (critical for daily tracking)
**Conversion:** When a lead becomes a paying client
**Follow-up:** Scheduled communication with a lead
**Standard Commission:** Commission earned per conversion based on user's settings
**Extra Commission:** Additional commission beyond standard (bonuses, incentives)
**Fixed Commission:** A set amount earned per conversion regardless of deal value
**Percentage Commission:** Commission calculated as percentage of deal value
**Commission Rate Used:** The rate applied at the time of conversion (immutable)
**Repeat Lead:** Same phone number contacted again
**Service Pattern:** Design pattern separating business logic from controllers
**Daily View:** Interface showing leads organized by specific date
**Monthly View:** Aggregated view of all leads in a month

---

## 11. Success Criteria

The project is considered successful when:
1. Admin can add users and manage the system
2. Sales persons can add, track, and convert leads with daily date focus
3. Daily lead view displays correctly for any selected date
4. Monthly view aggregates data accurately
5. Users can set and update their own commission rates
6. Commission calculations work correctly for both fixed and percentage
7. Commission rates are stored immutably with conversions
8. Admins can add and approve extra commissions
9. Search by phone number works instantly
10. Dashboard shows real-time metrics including commission data
11. System is fully responsive on all devices
12. All forms have proper validation
13. User can view their performance and commission breakdown
14. No critical bugs in core functionality
15. Page load times meet performance requirements
16. Date navigation is smooth and intuitive

---

**Document Version:** 1.1  
**Last Updated:** December 14, 2025  
**Author:** Development Team