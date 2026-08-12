// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.Alpine = Alpine;
Alpine.start();

// 1. ✅ Import jQuery Core Module Globally
import $ from 'jquery';
window.$ = window.jQuery = $;

// 2. ✅ Import Dependency-Free DataTables & Extensions (Modern ESM Standard)
import DataTable from 'datatables.net';
import Responsive from 'datatables.net-responsive';

// 3. ✅ Securely bind DataTables extensions into the core module context
DataTable.use(Responsive); // 👈 Correct, dependency-free native linkage

// 4. ✅ Attach to global jQuery interface to support old-school $(...).DataTable() syntax
window.DataTable = DataTable;
$.fn.dataTable = DataTable;
$.fn.DataTable = function (opts) {
    return new DataTable(this[0], opts);
};

// 5. ✅ Import Toastr Notification Engine & Select2 Dropdowns
import toastr from 'toastr';
import 'select2';

// ⚡ Toastr Base Options
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 5000,
};
window.showToast = (type, message) => toastr[type](message);

// 🖥️ Client Script Runtime Pipeline Area
$(document).ready(function() {

    // ✅ Initialize Offline Full Features DataTables ONLY for management pages
    $('.datatable').each(function() {
        new DataTable(this, {
            responsive: true,
            pageLength: 25,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
            }
        });
    });

    // ✅ Offline Select2 Dropdowns Initialization
    $('.select2').each(function() {
        $(this).select2({
            placeholder: 'Select option',
            allowClear: true
        });
    });

    // ✅ Close & Remove Custom App Notifications after 5 seconds
    setTimeout(() => {
        $('.alert-custom').css('opacity', '0');
        setTimeout(() => $('.alert-custom').remove(), 500);
    }, 5000);

    // ✅ Global Delete Confirmation Form Framework Link Handler
    window.confirmDelete = function(url, message) {
        if (confirm(message || 'Are you sure you want to delete this item?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message || 'Deleted successfully');
                        location.reload();
                    } else {
                        toastr.error(response.message || 'Error deleting item');
                    }
                },
                error: function() {
                    toastr.error('Error deleting item');
                }
            });
        }
    };
});
