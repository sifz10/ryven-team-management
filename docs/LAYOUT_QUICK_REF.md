# Quick Reference: New Sidebar Layout

## 🎨 Design Tokens

### Active Navigation Link
```blade
bg-black text-white dark:bg-white dark:text-black
```

### Button Styles
```blade
<!-- Primary Button -->
<x-black-button>Click Me</x-black-button>

<!-- Outline Button -->
<x-black-button variant="outline">Cancel</x-black-button>

<!-- Icon Button -->
<x-icon-button variant="black">
    <svg>...</svg>
</x-icon-button>
```

### Border Radius
- Small: `rounded-md` (4px)
- Default: `rounded-lg` (8px)
- Large: `rounded-xl` (12px)
- Circle: `rounded-full`

## 📱 Responsive Breakpoints
- Mobile: `< 1024px` - Sidebar hidden, hamburger menu
- Desktop: `≥ 1024px` - Sidebar visible, collapsible

## 🔧 Alpine.js State
```javascript
sidebarOpen: false          // Mobile menu toggle
sidebarCollapsed: false     // Desktop collapse (persists)
```

## 📂 File Structure
```
resources/views/layouts/
├── app.blade.php           # Main wrapper
├── sidebar.blade.php       # Left navigation
└── topbar.blade.php        # Top header bar

resources/views/components/
├── black-button.blade.php  # Primary button
└── icon-button.blade.php   # Icon-only button
```

## ✨ Key Features
✅ Pure black/white active states
✅ Smooth transitions (200ms)
✅ Dark mode optimized
✅ Mobile responsive
✅ Persistent collapse state
✅ Icon + text navigation
✅ Grouped sections

## 🚀 After Making Changes
```bash
npm run build  # Production
npm run dev    # Development with hot reload
```
