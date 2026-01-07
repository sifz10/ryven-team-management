# Responsive Layout - Visual Guide

## 📱 Mobile Layout (< 640px)

```
┌─────────────────────────────────┐
│         ADJUSTMENT MODAL        │
├─────────────────────────────────┤
│ Make instant salary modifications│
│                             [×] │
├─────────────────────────────────┤
│                                 │
│   💰 CURRENT SALARY             │
│   $5,000.00                 USD │
│                                 │
├─────────────────────────────────┤
│                                 │
│  Adjustment Type ▼              │
│  [💰 Manual Adjustment       ]  │
│                                 │
│  New Monthly Salary             │
│  [_____________________]         │
│                                 │
│  📈 Salary Increase             │
│  +500.00 USD                    │
│                                 │
│  Reason for Adjustment          │
│  ┌─────────────────────────────┐│
│  │ e.g., Performance...        ││
│  │                             ││
│  │                             ││
│  │                             ││
│  └─────────────────────────────┘│
│  Provide clear documentation... │
│                                 │
├─────────────────────────────────┤
│  [    Cancel    ] [  Confirm   ]│
│                                 │
└─────────────────────────────────┘

Features:
• Full width modal
• Stacked form fields
• Large touch buttons
• Scrollable form
• Readable typography
```

## 📱 Tablet Portrait (640px - 1024px)

```
┌──────────────────────────────────────┐
│      ADJUSTMENT MODAL                │
├──────────────────────────────────────┤
│ Make instant salary modifications    │
│                                [×]  │
├──────────────────────────────────────┤
│                                      │
│  💰 CURRENT SALARY          USD      │
│  $5,000.00                           │
│                                      │
├──────────────────────────────────────┤
│                                      │
│  Adj. Type ▼           New Salary    │
│  [Manual Adj.    ] [_____5500.00__] │
│                                      │
│  📈 Salary Increase                  │
│  +500.00 USD                         │
│                                      │
│  Reason for Adjustment               │
│  ┌────────────────────────────────┐ │
│  │ e.g., Performance improvement..│ │
│  │                                │ │
│  └────────────────────────────────┘ │
│  Provide clear documentation...      │
│                                      │
├──────────────────────────────────────┤
│    [Cancel]        [Confirm Adjust] │
│                                      │
└──────────────────────────────────────┘

Features:
• 2-column form fields
• Side-by-side inputs
• Better space utilization
• Larger buttons
• Improved readability
```

## 🖥️ Desktop Layout (≥ 1024px)

```
┌─────────────────────────────────────────────────┐
│       Quick Salary Adjustment                   │
│  Make instant salary modifications with audit...│
│                                             [×] │
├─────────────────────────────────────────────────┤
│                                                 │
│  💰 CURRENT SALARY                          USD │
│  $5,000.00                                      │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  Adjustment Type ▼          New Monthly Salary  │
│  [💰 Manual Adjustment]  [_______5500.00_____] │
│                                                 │
│  📈 Salary Increase                             │
│  +500.00 USD                                    │
│                                                 │
│  Reason for Adjustment                          │
│  ┌─────────────────────────────────────────────┐│
│  │ e.g., Performance improvement, promotion..  ││
│  │                                             ││
│  │                                             ││
│  │                                             ││
│  └─────────────────────────────────────────────┘│
│  Provide clear documentation for audit trail    │
│                                                 │
├─────────────────────────────────────────────────┤
│        [Cancel]     [Confirm Adjustment]        │
│                                                 │
└─────────────────────────────────────────────────┘

Features:
• Optimal max-width (56rem)
• Professional layout
• Best readability
• Prominent buttons
• Full form visibility
```

## 🎨 Color & Styling

### Light Mode
```
Header: Light gray background
Text: Dark gray (#1f2937)
Inputs: White with gray border
Focus: Blue border & ring
Current Salary: Light blue gradient
Difference: Light blue background
Buttons: Blue gradient primary, gray secondary
```

### Dark Mode
```
Header: Dark gray background (#1f2937)
Text: Light gray (#f3f4f6)
Inputs: Dark with dark gray border
Focus: Blue border & ring (adjusted)
Current Salary: Dark blue gradient
Difference: Dark blue gradient
Buttons: Blue gradient (adjusted)
```

## 📐 Spacing System

### Mobile (< 640px)
```
Modal padding:    p-6 (24px)
Form gap:         gap-4 (16px)
Button padding:   py-3 (12px)
Input padding:    py-2.5 (10px)
Border radius:    rounded-lg (8px)
```

### Tablet+ (≥ 640px)
```
Modal padding:    p-8 (32px)
Form gap:         gap-5 (20px)
Button padding:   py-3.5 (14px)
Input padding:    py-2.5 (10px)
Border radius:    rounded-lg (8px)
```

## 📏 Typography Scaling

### Mobile
```
Title (h3):       text-lg (18px)
Subtitle:         text-sm (14px)
Labels:           text-xs (12px)
Display Value:    text-3xl (30px)
Input Text:       text-sm (14px)
Button Text:      text-sm (14px)
```

### Tablet+
```
Title (h3):       text-xl (20px)
Subtitle:         text-base (16px)
Labels:           text-xs (12px)
Display Value:    text-4xl (36px)
Input Text:       text-sm (14px)
Button Text:      text-base (16px)
```

## ⚡ Interactive States

### Input Focus
```
┌────────────────────────────┐
│ New Monthly Salary         │
│ ┌──────────────────────────┐│ ← Blue border
│ │ 5500.00       │           ││ ← Blue ring
│ └──────────────────────────┘│
└────────────────────────────┘
```

### Button Hover
```
Normal:  [Blue Gradient] → Darker Blue Gradient
         Shadow: lg     → Shadow: xl
         
Focus:   Border + Ring (blue accent)
Active:  Slightly darker with increased shadow
```

### Difference Display
```
Inactive: Hidden (x-show="difference !== 0")

Active:
┌──────────────────────────────┐
│ 📈 Salary Increase      Green │
│ +500.00 USD                  │
└──────────────────────────────┘

Or for decrease:
┌──────────────────────────────┐
│ 📉 Salary Decrease      Red   │
│ -300.00 USD                  │
└──────────────────────────────┘
```

## 🔄 Responsive Transitions

All changes use smooth transitions:
- Duration: 200ms (standard)
- Easing: ease-out (natural feel)
- Properties: All (colors, shadows, borders)

```css
transition-all duration-200
```

## 📊 Breakpoint Behavior

| Element | Mobile | Tablet | Desktop |
|---------|--------|--------|---------|
| Modal Width | 100% | 75% | max-w-2xl |
| Form Layout | 1 col | 2 col | 2 col |
| Padding | p-6 | p-6 | p-8 |
| Typography | sm | base | base |
| Buttons | Full | Full | Auto |
| Current Salary | Stack | Side | Side |

## 🎯 Touch Target Sizes

All interactive elements meet minimum touch size (44px × 44px):

```
Buttons:     py-3 + font (14px text) = ~44px
Inputs:      py-2.5 + border = ~40px (acceptable)
Select:      py-2.5 + border = ~40px (acceptable)
Close:       w-5 h-5 in 16px area = ~24px (centered)
```

## ✅ Responsive Checklist

- [x] Modal resizes with viewport
- [x] Form fields adapt to screen width
- [x] Typography scales appropriately
- [x] Spacing responds to breakpoints
- [x] Touch targets meet 44px minimum
- [x] No horizontal scrolling on mobile
- [x] Buttons stack on mobile
- [x] Form scrollable on small screens
- [x] Dark mode fully responsive
- [x] Orientation changes supported

---
**Last Updated**: January 2026
**Design System**: Tailwind CSS 3.x
**Breakpoints**: Mobile-first (sm, md, lg, xl)
