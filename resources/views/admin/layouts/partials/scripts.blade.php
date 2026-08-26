<!-- ✅ Vite Bundle -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- ✅ Flash Messages via Toastr -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ✅ Show flash messages using global toastr
        setTimeout(function () {
            @if(session('success'))
                if (window.showToast) window.showToast('success', "{{ session('success') }}");
            @endif
            @if(session('error'))
                if (window.showToast) window.showToast('error', "{{ session('error') }}");
            @endif
            @if(session('warning'))
                if (window.showToast) window.showToast('warning', "{{ session('warning') }}");
            @endif
            @if(session('info'))
                if (window.showToast) window.showToast('info', "{{ session('info') }}");
            @endif
    }, 300);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ✅ Toggle sidebar
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const closeBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        document.getElementById('toggleSidebar')?.addEventListener('click', toggleSidebar);
        closeBtn?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);

        // ============================================================
        // ✅ SUB-MENU TOGGLE WITH ATTRACTIVE ARROW
        // ============================================================
        function toggleSubMenu(element) {
            const subMenu = element.nextElementSibling;
            const arrow = element.querySelector('.arrow');

            if (subMenu && subMenu.classList.contains('sub-menu')) {
                subMenu.classList.toggle('open');

                if (arrow) {
                    arrow.classList.toggle('open');
                    if (arrow.classList.contains('open')) {
                        arrow.innerHTML = '▼';
                        arrow.style.color = '#38ef7d';
                        arrow.style.background = 'rgba(56, 239, 125, 0.15)';
                    } else {
                        arrow.innerHTML = '▶';
                        arrow.style.color = 'rgba(255, 255, 255, 0.3)';
                        arrow.style.background = 'rgba(255, 255, 255, 0.05)';
                    }
                }

                // ✅ After toggling, scroll to keep parent in view
                setTimeout(function () {
                    const parent = element.closest('.admin-sidebar');
                    if (parent) {
                        const elementTop = element.offsetTop;
                        const parentHeight = parent.clientHeight;
                        const elementHeight = element.offsetHeight;
                        if (elementTop > parentHeight) {
                            parent.scrollTop = elementTop - (parentHeight / 2) + (elementHeight / 2);
                        }
                    }
                }, 100);
            }
        }

        // ✅ Make toggleSubMenu globally available
        window.toggleSubMenu = toggleSubMenu;

        // ============================================================
        // ✅ KEEP SUB-MENU OPEN IF ANY CHILD IS ACTIVE
        // ============================================================
        const activeSubItem = document.querySelector('.sub-item.active');
        if (activeSubItem) {
            const subMenu = activeSubItem.closest('.sub-menu');
            if (subMenu) {
                subMenu.classList.add('open');
                const parentItem = subMenu.previousElementSibling;
                if (parentItem && parentItem.classList.contains('menu-item')) {
                    const arrow = parentItem.querySelector('.arrow');
                    if (arrow) {
                        arrow.classList.add('open');
                        arrow.innerHTML = '▼';
                        arrow.style.color = '#38ef7d';
                        arrow.style.background = 'rgba(56, 239, 125, 0.15)';
                    }
                }
            }
        }

        // ============================================================
        // ✅ AUTO-SCROLL TO ACTIVE MENU ITEM (FIXED)
        // ============================================================
        function scrollToActiveMenuItem() {
            const sidebar = document.getElementById('adminSidebar');
            if (!sidebar) return;

            // ✅ Try to find active sub-item first
            let activeItem = document.querySelector('.sub-item.active');

            // ✅ If no active sub-item, find active menu-item
            if (!activeItem) {
                activeItem = document.querySelector('.menu-item.active');
            }

            if (activeItem) {
                const itemTop = activeItem.offsetTop;
                const sidebarHeight = sidebar.clientHeight;
                const itemHeight = activeItem.offsetHeight;
                const scrollPosition = itemTop - (sidebarHeight / 2) + (itemHeight / 2);

                // ✅ Smooth scroll to active item
                sidebar.scrollTo({
                    top: scrollPosition,
                    behavior: 'smooth'
                });
            }
        }

        // ✅ Scroll on load
        setTimeout(scrollToActiveMenuItem, 300);

        // ✅ Also scroll when window resizes
        window.addEventListener('resize', function () {
            setTimeout(scrollToActiveMenuItem, 200);
        });

        // ✅ Also scroll when any sub-menu is toggled
        document.addEventListener('click', function (e) {
            if (e.target.closest('.has-sub')) {
                setTimeout(scrollToActiveMenuItem, 200);
            }
        });
    });
</script>
