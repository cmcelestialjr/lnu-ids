$(document).ready(function() {
        // Scroll to the active item in the sidebar after page load
        var activeItem = $('.nav-sidebar .active');
        if (activeItem.length > 0) {
            var sidebar = activeItem.closest('.sidebar');
            var activeItemOffset = activeItem.offset();
            var sidebarOffset = sidebar.offset();
            if (activeItemOffset && sidebarOffset) {
                var scrollTo = activeItemOffset.top - sidebarOffset.top + sidebar.scrollTop() - (sidebar.height() / 2) + 300;
                sidebar.animate({ scrollTop: scrollTo }, 'slow');
            } else {
                console.error('Failed to calculate positions for scrolling to the active item.');
            }
        }
});