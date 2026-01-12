// ========================================
// NAVIGATION DEBUG & TEST SCRIPT
// ========================================
// Run this in Browser Console (F12) to test navigation

console.log('='.repeat(60));
console.log('NAVIGATION DEBUG SCRIPT');
console.log('='.repeat(60));

// 1. Check if nav items exist
console.log('\n1️⃣  CHECKING NAV ITEMS:');
const navItems = document.querySelectorAll('.nav-item');
console.log(`✅ Found ${navItems.length} nav items`);
navItems.forEach((item, idx) => {
    console.log(`   [${idx}] data-menu="${item.dataset.menu}" - ${item.textContent.trim()}`);
});

// 2. Check if menu-content elements exist
console.log('\n2️⃣  CHECKING MENU CONTENT ELEMENTS:');
const menus = document.querySelectorAll('.menu-content');
console.log(`✅ Found ${menus.length} menu content elements`);
menus.forEach((menu, idx) => {
    console.log(`   [${idx}] id="${menu.id}" - active=${menu.classList.contains('active')}`);
});

// 3. Test click on each nav item
console.log('\n3️⃣  TESTING NAVIGATION CLICKS:');
console.log('---');

function testNavigation(menuType) {
    console.log(`\n🔴 TEST: Click "${menuType}" tab`);
    console.log('-'.repeat(40));
    
    // Find nav item
    const navItem = document.querySelector(`[data-menu="${menuType}"]`);
    if (!navItem) {
        console.error(`❌ Nav item not found: ${menuType}`);
        return;
    }
    console.log(`✅ Found nav item: ${menuType}`);
    
    // Click it
    console.log(`🔴 Simulating click...`);
    navItem.click();
    
    // Check results
    setTimeout(() => {
        const menuId = menuType + 'Menu';
        const menu = document.getElementById(menuId);
        const isActive = menu ? menu.classList.contains('active') : false;
        
        console.log(`✅ Menu "${menuId}" active: ${isActive}`);
        console.log(`✅ Nav item "${menuType}" has active class: ${navItem.classList.contains('active')}`);
        console.log(`✅ Page title: "${document.getElementById('pageTitle').textContent}"`);
        console.log('-'.repeat(40));
    }, 100);
}

// Test all menus
console.log('\nTesting all menu clicks...\n');
testNavigation('dashboard');
setTimeout(() => testNavigation('penduduk'), 500);
setTimeout(() => testNavigation('grafik'), 1000);
setTimeout(() => testNavigation('laporan'), 1500);

console.log('\n='.repeat(60));
console.log('✅ TEST COMPLETE - Check results above');
console.log('='.repeat(60));

// 4. Manual test function
console.log('\n4️⃣  MANUAL TEST FUNCTION:');
console.log('You can also manually test by running:');
console.log('   testNavigation("dashboard")');
console.log('   testNavigation("penduduk")');
console.log('   testNavigation("grafik")');
console.log('   testNavigation("laporan")');
