# Salary Adjustment - Code Reference

## Architecture Overview

```
User clicks "Adjust" Button
    ↓
Alpine.js toggles openAdjustmentModal = true
    ↓
Modal appears with form (employee shows.blade.php)
    ↓
User fills form & clicks "Confirm Adjustment"
    ↓
JavaScript submitAdjustment() fires
    ↓
AJAX POST to /employees/{id}/adjust-salary
    ↓
SalaryReviewController::adjustSalary() validates & processes
    ↓
Creates SalaryAdjustmentHistory record
    ↓
Updates employee.salary
    ↓
Returns JSON response
    ↓
JavaScript reloads page on success
    ↓
Salary updated throughout system
```

## Frontend Code

### Modal Trigger Button
**Location**: `resources/views/employees/show.blade.php` (line ~459)

```blade
<button @click="openAdjustmentModal = true" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
    Adjust
</button>
```

### Alpine.js State Management
**Location**: `resources/views/employees/show.blade.php` (line 65)

```javascript
<div x-data="{ 
    tab: getInitialTab(), 
    openAdjustmentModal: false 
}" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
```

### Modal HTML Structure
**Location**: `resources/views/employees/show.blade.php` (line ~220)

```blade
<!-- Salary Adjustment Modal -->
<div x-show="openAdjustmentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click.self="openAdjustmentModal = false" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all" x-transition:enter="ease-out duration-200" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
        <!-- Modal content here -->
    </div>
</div>
```

### Form Data & Submission
**Location**: Form element in modal (nested x-data)

```javascript
x-data="{
    adjustmentType: 'adjustment',
    newSalary: {{ $employee->salary ?? 0 }},
    reason: '',
    submitting: false,
    currentSalary: {{ $employee->salary ?? 0 }},
    difference: 0,

    updateDifference() {
        this.difference = this.newSalary - this.currentSalary;
    },

    async submitAdjustment() {
        if (!this.newSalary || !this.reason.trim()) {
            alert('Please fill in all fields');
            return;
        }

        this.submitting = true;

        try {
            const response = await fetch('{{ route('employees.adjust-salary', $employee) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    new_salary: parseFloat(this.newSalary),
                    type: this.adjustmentType,
                    reason: this.reason
                })
            });

            const data = await response.json();

            if (data.success) {
                showToast('✓ Salary adjusted successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Failed to adjust salary', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        } finally {
            this.submitting = false;
        }
    }
}"
```

### Form Fields

#### Adjustment Type Select
```blade
<select x-model="adjustmentType" id="adj_type" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg px-3 py-2 text-sm focus:border-black dark:focus:border-white focus:ring-1 focus:ring-black dark:focus:ring-white">
    <option value="adjustment">💰 Manual Adjustment</option>
    <option value="promotion">📈 Promotion</option>
    <option value="demotion">📉 Demotion</option>
    <option value="bonus">🎁 Bonus</option>
</select>
```

#### Salary Input
```blade
<input 
    x-model.number="newSalary" 
    @input="updateDifference()"
    id="new_salary" 
    type="number" 
    step="0.01" 
    min="0"
    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg px-3 py-2 text-sm focus:border-black dark:focus:border-white focus:ring-1 focus:ring-black dark:focus:ring-white" 
    placeholder="0.00"
    required
>
```

#### Salary Difference Display
```blade
<div x-show="difference !== 0" x-cloak class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
    <div class="flex items-center justify-between">
        <span class="text-sm text-blue-700 dark:text-blue-300">
            <span x-text="difference > 0 ? '📈 Increase' : '📉 Decrease'"></span>
        </span>
        <span class="text-lg font-bold" :class="difference > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
            <span x-text="difference > 0 ? '+' : ''"></span>
            <span x-text="difference.toFixed(2)"></span>
            <span class="text-sm ml-1">{{ $employee->currency ?? 'USD' }}</span>
        </span>
    </div>
</div>
```

#### Reason Textarea
```blade
<textarea 
    x-model="reason" 
    id="adj_reason" 
    rows="3"
    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg px-3 py-2 text-sm focus:border-black dark:focus:border-white focus:ring-1 focus:ring-black dark:focus:ring-white resize-none"
    placeholder="e.g., Performance improvement, promotion to senior role, cost of living adjustment..."
    required
></textarea>
```

#### Submit Button
```blade
<button 
    type="submit"
    :disabled="submitting"
    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-black hover:bg-gray-800 dark:hover:bg-gray-700 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition disabled:opacity-50 disabled:cursor-not-allowed"
>
    <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <svg x-show="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <span x-text="submitting ? 'Adjusting...' : 'Confirm Adjustment'"></span>
</button>
```

## Backend Code

### Controller Method
**Location**: `app/Http/Controllers/SalaryReviewController.php` (line 111)

```php
public function adjustSalary(Request $request, Employee $employee)
{
    $validated = $request->validate([
        'new_salary' => ['required', 'numeric', 'min:0'],
        'reason' => ['required', 'string', 'max:1000'],
        'type' => ['required', 'in:promotion,demotion,adjustment,manual,bonus'],
    ]);

    $oldSalary = $employee->salary;
    $newSalary = $validated['new_salary'];

    // Create audit trail
    SalaryAdjustmentHistory::create([
        'employee_id' => $employee->id,
        'old_salary' => $oldSalary,
        'new_salary' => $newSalary,
        'adjustment_amount' => $newSalary - $oldSalary,
        'type' => $validated['type'],
        'reason' => $validated['reason'],
        'adjusted_by' => Auth::id(),
        'currency' => $employee->currency ?? 'USD',
    ]);

    // Update salary
    $employee->update(['salary' => $newSalary]);

    // Return JSON for AJAX, redirect for forms
    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Salary adjusted successfully!',
            'old_salary' => $oldSalary,
            'new_salary' => $newSalary,
            'adjustment_amount' => $newSalary - $oldSalary,
            'type' => $validated['type'],
        ]);
    }

    return back()->with('success', 'Salary adjusted successfully!');
}
```

### Route Definition
**Location**: `routes/web.php` (line 340)

```php
Route::post('employees/{employee}/adjust-salary', [SalaryReviewController::class, 'adjustSalary'])
    ->name('employees.adjust-salary')
    ->middleware('permission:manage-employees');
```

### SalaryAdjustmentHistory Model
**Location**: `app/Models/SalaryAdjustmentHistory.php`

```php
class SalaryAdjustmentHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'salary_review_id',
        'old_salary',
        'new_salary',
        'adjustment_amount',
        'type',
        'reason',
        'adjusted_by',
        'currency',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    public function salaryReview()
    {
        return $this->belongsTo(SalaryReview::class);
    }
}
```

### Employee Model Relationship
**Location**: `app/Models/Employee.php`

```php
public function salaryAdjustmentHistory()
{
    return $this->hasMany(SalaryAdjustmentHistory::class);
}
```

## Database Schema

### salary_adjustment_history Table Migration
**Location**: `database/migrations/2026_01_07_000002_create_salary_adjustment_history_table.php`

```php
Schema::create('salary_adjustment_history', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->foreignId('salary_review_id')->nullable()->constrained()->onDelete('set null');
    $table->decimal('old_salary', 15, 2);
    $table->decimal('new_salary', 15, 2);
    $table->decimal('adjustment_amount', 15, 2);
    $table->enum('type', ['promotion', 'demotion', 'adjustment', 'manual', 'bonus'])->default('adjustment');
    $table->text('reason')->nullable();
    $table->foreignId('adjusted_by')->constrained('users')->onDelete('cascade');
    $table->string('currency', 3)->default('USD');
    $table->timestamps();
    
    $table->index('employee_id');
    $table->index('type');
    $table->index('created_at');
});
```

## Toast Notification Function

**Location**: `resources/views/employees/show.blade.php` (JavaScript section)

```javascript
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : type === 'info' ? 'bg-blue-500' : 'bg-red-500'
    } text-white font-medium`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
```

## Integration Points

### With Salary History View
The adjustment automatically appears in:
- `resources/views/salary-reviews/employee-history.blade.php`
- Shows in "Complete Adjustment History" table
- Displays type, reason, dates, amounts

### With Employee Model
- `employee->salaryAdjustmentHistory()` relationship
- `employee->salary` updated field
- `employee->currency` used for adjustment currency

### With Activity Log
Optionally tracked in:
- `activity_log` table (if configured)
- Shows who made change and when

## Testing Checklist

- [ ] Modal opens when clicking "Adjust" button
- [ ] Modal closes when clicking X or outside
- [ ] Real-time difference calculation works
- [ ] Decimal values handled correctly
- [ ] All validation messages show
- [ ] Form submits successfully
- [ ] Page reloads after success
- [ ] Salary updated in database
- [ ] Audit record created
- [ ] History shows new adjustment
- [ ] Error messages display on failure
- [ ] Dark mode styling works
- [ ] Mobile responsive layout
- [ ] Different currencies handled
- [ ] All adjustment types selectable

---
**Version**: 1.0  
**Last Updated**: 2025  
**Status**: Production Ready ✅
