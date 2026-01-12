# 🔧 TROUBLESHOOTING NAVIGASI TAB

## STEP 1: Check Browser Console (F12)

1. Open browser
2. Press **F12** to open DevTools
3. Click **Console** tab
4. You should see messages like:
   ```
   ✅ PAGE FULLY LOADED
   📊 Nav items: 4
   📊 Menu contents: 4
   ```

If you don't see these, there's a JS loading problem.

---

## STEP 2: Manual Navigation Test

Copy and paste this in Console:

```javascript
// Simple test - click Penduduk tab
document.querySelector('[data-menu="penduduk"]').click();
```

Expected result:
- Penduduk tab becomes highlighted
- Page content changes to Penduduk data table
- Console shows "🔴 CLICKED: penduduk"

If this works, then JavaScript navigation is working fine.

---

## STEP 3: Check Menu Elements

In Console, run:

```javascript
// Check if menu elements exist
console.log(document.querySelectorAll('.menu-content'));
```

You should see 4 elements:
- dashboardMenu
- pendudukMenu
- grafikMenu
- laporanMenu

---

## STEP 4: Detailed Debug

Run the full test script in console:

```javascript
// Copy and paste NAVIGATION_TEST.js content here
// OR load it: fetch('NAVIGATION_TEST.js').then(r=>r.text()).then(eval)
```

This will test all navigation and show detailed results.

---

## POSSIBLE ISSUES & SOLUTIONS

### ❌ Issue: "Nav items not found"
**Solution:** Check that nav-item elements have `data-menu` attribute
```html
<a href="javascript:void(0);" class="nav-item" data-menu="penduduk">
```

### ❌ Issue: "Menu content not found"
**Solution:** Check menu IDs are correct (menuNameMenu format)
```html
<div id="pendudukMenu" class="menu-content">
```

### ❌ Issue: Menu is found but not activated
**Solution:** Check CSS - `.menu-content.active { display: block; }`

### ❌ Issue: Page content doesn't load
**Solution:** Check if loadPenduduk(), loadGrafik() functions exist

---

## WHAT THE NEW CODE DOES

1. **setupEventListeners()** - Attaches click handlers to all nav items
2. When tab clicked:
   - Hide all menus
   - Remove active from all tabs
   - Add active to clicked tab
   - Show target menu
   - Load corresponding data
3. Console logs every step for debugging

---

## HOW TO REPORT ISSUE

If navigation still doesn't work:

1. Open Browser Console (F12)
2. Click on a tab (e.g., "Penduduk")
3. Copy all console messages
4. Paste them when reporting the issue

The messages will show exactly where it breaks.

---

## QUICK CHECKLIST

- [ ] Browser console shows "✅ PAGE FULLY LOADED"
- [ ] Running `document.querySelectorAll('.nav-item')` shows 4 items
- [ ] Running `document.querySelector('[data-menu="penduduk"]').click()` works
- [ ] Clicking tabs in UI changes the page
- [ ] Console shows "🔴 CLICKED: penduduk" when clicking tab
- [ ] No red error messages in console
