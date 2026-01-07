# Quick Salary Increment/Decrement Guide

## Where to Find It
**Employee Profile → Overview Tab → Salary Summary Card → Green "Adjust" Button**

## How It Works

### Step 1: Open the Modal
- Click the green "Adjust" button next to "View History"
- Modal appears with salary adjustment form

### Step 2: Choose Adjustment Type
Select from 4 options:
- **💰 Manual Adjustment** - General salary changes
- **📈 Promotion** - Employee promoted/responsibilities increased
- **📉 Demotion** - Reduce salary
- **🎁 Bonus** - One-time bonus amount

### Step 3: Enter New Salary
- Input the new monthly salary amount
- See real-time calculation of salary change:
  - 📈 Green text for increases
  - 📉 Red text for decreases
  - Shows exact amount difference in employee's currency

### Step 4: Add Reason
Explain why the adjustment is happening:
- "Performance improvement - exceeded sales targets"
- "Promotion to Senior Developer role"
- "Cost of living adjustment (2025)"
- "Market rate adjustment"
- etc.

### Step 5: Confirm
Click "Confirm Adjustment" button to save

## What Happens After

✅ **Immediate**:
- Salary updated in system
- Success message displayed
- Page reloads to show new salary

✅ **Automatic Recording**:
- Audit trail created in `salary_adjustment_history` table
- Records who made the change and when
- Keeps old and new salary for comparison
- Stores adjustment type and reason

✅ **Visible History**:
- All adjustments appear in employee salary history page
- Can view who made changes and why
- Complete timeline of salary modifications

## Real-Time Calculation Example

```
Current Salary: $5,000.00
New Salary Input: $5,500.00

Result Display:
📈 Increase
+$500.00 USD
```

## Keyboard Shortcuts
- **Escape**: Close modal without saving
- **Tab**: Navigate between form fields
- **Enter** (in textarea): New line (Shift+Enter for fast submit)

## Validation Rules

| Field | Rules | Example |
|-------|-------|---------|
| **New Salary** | Must be >= 0, numeric | 5500.50 |
| **Reason** | Required, max 1000 chars | "Promotion to Senior" |
| **Type** | Must select one | "promotion" |

## Salary Types Explained

### 💰 Manual Adjustment
- General salary modifications
- Cost of living adjustments
- Market rate corrections
- Mid-year raises not tied to review

### 📈 Promotion
- Role level increase
- Title change
- Responsibility expansion
- Department promotion
- **Typical**: 10-30% increase

### 📉 Demotion
- Role level decrease
- Responsibility reduction
- Performance-based reduction
- **Less common but available**

### 🎁 Bonus
- One-time or periodic bonus
- Performance bonus
- Year-end bonus
- Referral bonus
- **Note**: Doesn't permanently change base salary

## Common Scenarios

### Annual Raise (3%)
```
Adjustment Type: Manual Adjustment
New Salary: Calculate (old × 1.03)
Reason: "Annual merit increase 2025"
```

### Promotion
```
Adjustment Type: Promotion
New Salary: old + 500 to 1500 (varies)
Reason: "Promoted to Senior Developer"
```

### Performance Bonus
```
Adjustment Type: Bonus
New Salary: Same as current or increased
Reason: "Q4 Performance Bonus"
```

### Cost of Living
```
Adjustment Type: Manual Adjustment
New Salary: Based on inflation rate
Reason: "COLA adjustment - inflation 2025"
```

## Tips & Tricks

### Calculate Percentage Increase
Want to give a 5% raise?
```
Formula: Current × 1.05 = New Salary
Example: $5000 × 1.05 = $5,250
```

### Calculate Dollar Increase
Want to add $500?
```
Formula: Current + 500 = New Salary
Example: $5000 + $500 = $5,500
```

### Bulk Changes Planning
For multiple employees:
1. Note the percentage/amount you'll use
2. Adjust one employee at a time
3. Check salary history to see pattern
4. Consistency helps with fairness documentation

## Viewing History

After making adjustment:
1. Click "View History" link in salary card
2. See all adjustments in chronological order
3. Filter by adjustment type (promotion, demotion, etc.)
4. See who made change and when
5. Full audit trail available

## Troubleshooting

### Modal Won't Open
- ✅ Check you're on employee profile page
- ✅ Check browser console for errors
- ✅ Try refreshing page
- ✅ Verify you have `manage-employees` permission

### "Failed to adjust salary" Message
- ✅ Check all fields are filled
- ✅ Verify salary is valid number
- ✅ Reason field needs text
- ✅ Check network connection
- ✅ See browser console for details

### Salary Not Updating
- ✅ Check page actually reloaded
- ✅ Verify CSRF token (check meta tags)
- ✅ Check Laravel logs for errors
- ✅ Try clearing browser cache

### Currency Issues
- ✅ Currency auto-filled from employee record
- ✅ All calculations in employee's currency
- ✅ No currency conversion (enters as-is)
- ✅ Update employee's currency if needed

## Permissions Required

User must have:
- ✅ `manage-employees` permission
- ✅ Access to employee profile
- ✅ Active user account

Admin/Manager role typically has these permissions.

## Database Tables Affected

| Table | Action | Details |
|-------|--------|---------|
| `employees` | UPDATE | salary column updated |
| `salary_adjustment_history` | INSERT | New record created per adjustment |
| `activity_log` | INSERT | Optional - depends on audit config |

## API Endpoint

**Method**: POST  
**Route**: `/employees/{id}/adjust-salary`  
**Headers**: 
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

**Request Body**:
```json
{
  "new_salary": 5500.00,
  "type": "promotion",
  "reason": "Promoted to Senior Developer"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Salary adjusted successfully!",
  "old_salary": 5000.00,
  "new_salary": 5500.00,
  "adjustment_amount": 500.00,
  "type": "promotion"
}
```

## Undo/Correction

If adjustment was made in error:
1. Make another adjustment with correct amount
2. In reason field: "Correction - previous adjustment was in error"
3. This creates clear audit trail of correction
4. Both adjustments visible in history

**Note**: There's no delete - all changes preserved for audit compliance.

## Best Practices

✅ **DO**:
- Document reasons clearly
- Use correct adjustment type
- Round to 2 decimal places
- Be consistent with policy
- Review history regularly

❌ **DON'T**:
- Make large changes without documentation
- Use generic reasons like "raise"
- Adjust same employee multiple times rapidly
- Skip the reason field
- Use text message format in reason

## Compliance & Audit

- ✅ Full audit trail maintained
- ✅ Who made change (user ID)
- ✅ When change was made (timestamp)
- ✅ What changed (old→new salary)
- ✅ Why it changed (reason field)
- ✅ Type of change (adjustment type)

All information available in salary history and database records.

---
**Last Updated**: 2025  
**Version**: 1.0  
**Status**: Production Ready ✅
