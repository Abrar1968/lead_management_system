# Lead Hard Delete Migration - Summary

## Overview
Converted the Lead model from soft deletes to hard deletes to prevent unique constraint violations when reusing lead numbers.

## Changes Made

### 1. Model Changes
**File:** `app/Models/Lead.php`
- ✅ Removed `use SoftDeletes` trait
- ✅ Removed `use Illuminate\Database\Eloquent\SoftDeletes` import

### 2. Database Migrations

**Migration 1:** `2025_12_22_104741_remove_unique_constraint_from_lead_number.php`
- ✅ Removed unique constraint from `lead_number` column
- Status: Successfully executed

**Migration 2:** `2025_12_22_105152_remove_soft_deletes_from_leads_table.php`
- ✅ Dropped `deleted_at` column
- ✅ Added unique constraint back to `lead_number` column
- Status: Successfully executed via `migrate:fresh`

### 3. Foreign Key Constraints (Already Configured)
All related tables properly handle lead deletion:

**Cascade Deletes (automatically deleted when lead is deleted):**
- `lead_contacts` → `onDelete('cascade')`
- `follow_ups` → `onDelete('cascade')`
- `meetings` → `onDelete('cascade')`
- `conversions` → `onDelete('cascade')`
  - `client_details` → `onDelete('cascade')` (via conversions)

**Null on Delete (lead_id set to null):**
- `demos` → `nullOnDelete()`

## Behavior Changes

### Before (Soft Delete)
- `$lead->delete()` → Sets `deleted_at` timestamp
- Lead still exists in database
- Unique constraint on `lead_number` prevented reusing numbers
- Could restore with `$lead->restore()`
- Queries needed `withTrashed()` to see deleted leads

### After (Hard Delete)
- `$lead->delete()` → Permanently removes record
- Lead is completely removed from database
- Related records automatically handled via foreign keys
- `lead_number` can be reused for new leads (same day sequence)
- No way to restore deleted leads

## Why This Change?

**Problem:** Soft deletes caused duplicate key errors when:
1. Lead created: `LEAD-20251222-001`
2. Lead soft deleted (still in DB with `deleted_at` set)
3. New lead created same day → tries to use `LEAD-20251222-001` again
4. **ERROR:** Unique constraint violation

**Solution:** Hard deletes allow:
- Lead deleted → completely removed
- New lead same day → can reuse sequence number
- No orphaned "deleted" records taking up unique values

## Testing

### Manual Testing Required
Since automated tests use SQLite (which has compatibility issues with ENUM modifications), manual testing is needed:

```bash
# Test 1: Create a lead
1. Navigate to /leads/create
2. Fill in lead details
3. Submit form
4. Note the lead_number (e.g., LEAD-20251222-001)

# Test 2: Delete the lead
1. Navigate to the lead's page
2. Click Delete (admin only)
3. Verify lead is removed from list

# Test 3: Create new lead same day
1. Navigate to /leads/create again
2. Fill in lead details
3. Submit form
4. Verify it gets the next sequence number (e.g., LEAD-20251222-002)
   OR if no other leads, it should reuse LEAD-20251222-001

# Test 4: Verify cascading deletes
1. Create a lead
2. Add a contact to the lead
3. Add a follow-up to the lead
4. Convert the lead
5. Delete the lead
6. Verify all related records are deleted:
   - Contacts deleted
   - Follow-ups deleted
   - Conversion deleted
   - Client details deleted (if any)
```

### Database Verification
```bash
# Verify deleted_at column is removed
php artisan tinker
>>> Schema::hasColumn('leads', 'deleted_at');
=> false

# Verify unique constraint exists
>>> DB::select("SHOW INDEX FROM leads WHERE Key_name = 'leads_lead_number_unique'");
=> Should return the index

# Verify cascading deletes work
>>> $lead = Lead::first();
>>> $leadId = $lead->id;
>>> LeadContact::create(['lead_id' => $leadId, ...]);
>>> $lead->delete();
>>> LeadContact::where('lead_id', $leadId)->count();
=> 0 (contact should be deleted)
```

## Production Deployment Notes

When deploying to Hostinger:

1. **Backup database first:**
   ```bash
   mysqldump -u username -p database_name > backup_before_hard_delete.sql
   ```

2. **Run migrations:**
   ```bash
   php artisan migrate
   ```
   This will:
   - Remove unique constraint (migration 1)
   - Remove deleted_at column and restore unique constraint (migration 2)

3. **Verify no errors:**
   - Check that all migrations completed successfully
   - Test lead creation and deletion

4. **User Communication:**
   - Inform users that deleted leads cannot be restored
   - Recommend double-checking before deleting
   - Consider adding a confirmation dialog in the UI

## Code Cleanup

**No soft delete references found in codebase:**
- ✅ No `withTrashed()` calls
- ✅ No `onlyTrashed()` calls
- ✅ No `restore()` calls
- ✅ No soft delete-specific queries

## Files Modified

1. `app/Models/Lead.php` - Removed SoftDeletes trait
2. `database/migrations/2025_12_22_104741_remove_unique_constraint_from_lead_number.php` - New
3. `database/migrations/2025_12_22_105152_remove_soft_deletes_from_leads_table.php` - New

## Rollback Plan (If Needed)

If you need to rollback to soft deletes:

```bash
# Rollback migrations
php artisan migrate:rollback --step=2

# Restore SoftDeletes trait in Lead model
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;
    ...
}
```

**Note:** This should only be done if the hard delete approach causes issues. The hard delete approach is recommended for this application.

## Next Steps

1. ✅ Migrations completed successfully
2. ✅ Model updated
3. ✅ Code formatted with Pint
4. ⏳ Manual testing required (automated tests have SQLite compatibility issue)
5. ⏳ Deploy to production with database backup
6. ⏳ Monitor for any issues

## Success Criteria

- [x] Lead model doesn't use SoftDeletes
- [x] `deleted_at` column removed from leads table
- [x] `lead_number` has unique constraint
- [x] Foreign keys properly cascade/nullify on delete
- [ ] Manual testing confirms delete works correctly
- [ ] Manual testing confirms cascading deletes work
- [ ] Production deployment successful
