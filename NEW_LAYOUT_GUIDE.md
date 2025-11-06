# New Dashboard Layout Guide

## Overview
The dashboard has been redesigned with a modern sidebar navigation and streamlined top bar, optimized for both desktop and mobile experiences with full dark/light mode support.

## Layout Architecture

### Key Components
1. **Sidebar** (`layouts/sidebar.blade.php`) - Left navigation with collapsible feature
2. **Top Bar** (`layouts/topbar.blade.php`) - Header with profile, notifications, and theme toggle
3. **Main Layout** (`layouts/app.blade.php`) - Wrapper that combines sidebar and content area

### Features
- ✅ **Collapsible Sidebar** - Desktop users can collapse to icon-only view (state persists in localStorage)
- ✅ **Mobile Responsive** - Sidebar slides out on mobile with overlay backdrop
- ✅ **Pure Black Design** - Active links use black/white with rounded corners
- ✅ **Dark Mode Optimized** - Inverted colors for perfect dark mode experience
- ✅ **Icon + Text Navigation** - Clear visual hierarchy with icons and labels
- ✅ **Grouped Sections** - Organized by: Core, Management, and Performance

## Navigation Structure

### Core Section
- Dashboard
- Employees
- Attendance
- Projects
- UAT Testing
- GitHub Logs

### Management Section
- Invoices
- Contracts
- Personal Notes
- Email Inbox (with unread badge)
- Content Calendar
- SOP

### Performance Section
- Review Cycles
- Performance Reviews
- Goals & OKRs
- Skills

## Design System

### Colors
- **Active State**: Pure black (`bg-black`) with white text in light mode
- **Active State (Dark)**: Pure white (`bg-white`) with black text
- **Hover State**: Light gray background (`hover:bg-gray-100` / `dark:hover:bg-gray-700`)
- **Text**: Gray tones for inactive states

### Buttons
Use the new button components for consistency:

```blade
<!-- Solid Black Button -->
<x-black-button>
    Save Changes
</x-black-button>

<!-- Outline Black Button -->
<x-black-button variant="outline">
    Cancel
</x-black-button>

<!-- Icon Button -->
<x-icon-button>
    <svg>...</svg>
</x-icon-button>

<!-- Black Icon Button -->
<x-icon-button variant="black">
    <svg>...</svg>
</x-icon-button>
```

### Sizes
All components support size variants:
- `sm` - Small (compact)
- `md` - Medium (default)
- `lg` - Large (prominent CTAs)

## Top Bar Elements

### Left Side
- **Hamburger Menu** (mobile only) - Toggles sidebar
- **Page Title** (desktop only) - Dynamic page title or breadcrumb

### Right Side
- **Theme Toggle** - Sun/moon icon switches between light/dark mode
- **Notifications** - Bell icon with badge count
- **Profile Dropdown** - Avatar with user menu

## Mobile Behavior

### Breakpoints
- `< 1024px (lg)`: Mobile mode activated
  - Sidebar hidden by default
  - Hamburger menu appears
  - Overlay backdrop when sidebar open
- `≥ 1024px`: Desktop mode
  - Sidebar always visible
  - Can be collapsed to icon-only
  - No overlay needed

### Touch Interactions
- Tap hamburger to open sidebar
- Tap outside sidebar to close
- Tap link to navigate and auto-close sidebar

## Sidebar States

### Expanded (Default Desktop)
- Width: `w-64` (256px)
- Shows: Icon + full text labels
- Grouped sections with headers

### Collapsed (Optional Desktop)
- Width: `w-20` (80px)
- Shows: Icons only
- Section headers hidden
- Tooltip on hover (optional enhancement)

### Mobile
- Slides from left edge
- Full screen overlay
- Closes automatically on navigation

## State Management

### Alpine.js Data
```javascript
{
    sidebarOpen: false,          // Mobile menu state
    sidebarCollapsed: false,     // Desktop collapse state (persists)
    toggleSidebar() { ... }      // Toggle collapse state
}
```

### LocalStorage Keys
- `sidebarCollapsed`: Boolean - stores desktop collapse preference
- `theme`: String ('dark' or 'light') - stores theme preference

## CSS Classes Reference

### Custom Classes (app.css)
- `.sidebar-transition` - Smooth sidebar animations
- `.btn-black` - Solid black button
- `.btn-black-outline` - Outline black button
- `.icon-btn` - Icon button base
- `.badge-pulse` - Pulsing notification badge

### Utility Patterns
```blade
<!-- Active Navigation Link -->
class="bg-black text-white dark:bg-white dark:text-black"

<!-- Hover State -->
class="hover:bg-gray-100 dark:hover:bg-gray-700"

<!-- Rounded Elements -->
class="rounded-lg"  <!-- Standard -->
class="rounded-xl"  <!-- Larger -->
class="rounded-full" <!-- Circular -->

<!-- Transitions -->
class="transition-all duration-200"
```

## How to Add New Navigation Items

### 1. Add to Sidebar (`layouts/sidebar.blade.php`)
```blade
<a href="{{ route('your.route') }}" 
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
          {{ request()->routeIs('your.route.*') 
             ? 'bg-black text-white dark:bg-white dark:text-black' 
             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
   x-tooltip="sidebarCollapsed ? 'Your Label' : ''">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- Your icon path -->
    </svg>
    <span x-show="!sidebarCollapsed" class="font-medium">Your Label</span>
</a>
```

### 2. Choose the Right Section
- **Core**: Main daily-use features
- **Management**: Administrative/organizational tools
- **Performance**: HR/evaluation features

Add new sections by creating a divider + header:
```blade
<div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
<div x-show="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
    Your Section
</div>
```

## Customization Tips

### Changing Sidebar Width
Edit `sidebar.blade.php` and `app.blade.php`:
```blade
<!-- Sidebar -->
'w-64': !sidebarCollapsed,  <!-- Change 64 to desired -->
'w-20': sidebarCollapsed,   <!-- Change 20 for icon-only -->

<!-- Main content -->
'lg:ml-64': !sidebarCollapsed,  <!-- Match sidebar width -->
'lg:ml-20': sidebarCollapsed,   <!-- Match collapsed width -->
```

### Adding Badges
```blade
<span class="absolute top-1 right-1 px-1.5 py-0.5 text-xs font-bold rounded-full bg-red-500 text-white">
    {{ $count }}
</span>
```

### Icons
Use Heroicons (already included):
- Browse: https://heroicons.com
- Copy SVG code
- Use `stroke` for outline style (recommended)
- Use `fill` for solid style

## Browser Support
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

## Performance Notes
- CSS is compiled with Tailwind JIT (minimal bundle size)
- Alpine.js handles all interactivity (lightweight, ~15KB)
- No jQuery required
- LocalStorage prevents state flickering on reload
- Smooth hardware-accelerated transitions

## Accessibility
- ✅ Keyboard navigation support
- ✅ Focus indicators on all interactive elements
- ✅ ARIA labels for icon-only buttons
- ✅ Color contrast meets WCAG AA standards
- ✅ Responsive text sizes

## Migration from Old Layout
The old `navigation.blade.php` can be safely removed after verifying all pages work correctly. The new layout is a drop-in replacement - no changes needed to existing page templates.

## Troubleshooting

### Sidebar not showing
- Check `@include('layouts.sidebar')` exists in `app.blade.php`
- Verify CSS is compiled: `npm run build`
- Clear Laravel view cache: `php artisan view:clear`

### Theme toggle not working
- Check browser console for JavaScript errors
- Verify Alpine.js is loaded
- Check localStorage is enabled in browser

### Mobile menu stuck
- Click outside sidebar to close
- Refresh page to reset state
- Check for JavaScript conflicts

### Active state not highlighting
- Verify route names match in `request()->routeIs()`
- Check for typos in route names
- Use wildcard for child routes: `'employees.*'`

## Build Process
After making changes:
```bash
# Development (with hot reload)
npm run dev

# Production (optimized)
npm run build
```

## Future Enhancements
- [ ] Tooltip component for collapsed sidebar
- [ ] Search in sidebar
- [ ] Keyboard shortcuts (⌘K to search)
- [ ] Sidebar resize handle
- [ ] Recent pages quick access
- [ ] Favorites/pinned items
