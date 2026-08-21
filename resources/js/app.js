// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.Alpine = Alpine;
Alpine.start();

// 1. ✅ Import jQuery Core Module Globally
import $ from 'jquery';
window.$ = window.jQuery = $;

// 2. ✅ Import Bootstrap 5 (Global)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// 2. ✅ Import Dependency-Free DataTables & Extensions (Modern ESM Standard)
import DataTable from 'datatables.net';
import Responsive from 'datatables.net-responsive';

// ✅ Import CKEditor 5
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// ✅ Make it global (so blade views can access)
window.ClassicEditor = ClassicEditor;

// ✅ Initialize CKEditor 5 on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // CKEditor 5 for all textareas with class 'ckeditor5'
    document.querySelectorAll('.ckeditor5').forEach(function(element) {
        if (element.id) {
            ClassicEditor
                .create(element, {
                    // ✅ Optional: Custom configuration
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'link', 'imageUpload', 'mediaEmbed', '|',
                        'undo', 'redo'
                    ],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    }
                })
                .catch(error => {
                    console.error('CKEditor error for', element.id, error);
                });
        }
    });
});

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

window.toastr = toastr;

toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 5000,
    extendedTimeOut: 1000,
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
};

// ✅ Global Toastr Helpers
window.showToast = function(type, message) {
    if (toastr[type]) {
        toastr[type](message);
    }
};

window.showToastConfirm = function(message, callback) {
    toastr.clear();

    var confirmHtml = `
        <div style="text-align: center; padding: 10px 0;">
            <p style="font-size: 15px; margin-bottom: 15px; color: #fff;">${message}</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="window._toastrConfirmCallback(true)"
                        style="background: #28a745; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-check"></i> Yes
                </button>
                <button onclick="window._toastrConfirmCallback(false)"
                        style="background: #dc3545; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-times"></i> No
                </button>
            </div>
        </div>
    `;

    window._toastrConfirmCallback = function(result) {
        toastr.clear();
        if (result) {
            callback();
        } else {
            toastr.info('Action cancelled');
        }
        delete window._toastrConfirmCallback;
    };

    toastr.warning(confirmHtml, 'Confirm Action', {
        closeButton: true,
        timeOut: 0,
        extendedTimeOut: 0,
        positionClass: 'toast-top-center',
        progressBar: false,
        escapeHtml: false,
    });
};

// 🖥️ Client Script Runtime Pipeline Area
$(document).ready(function() {

    // ✅ Initialize Offline Full Features DataTables ONLY for management pages
    // $('.datatable').each(function() {
    //     new DataTable(this, {
    //         responsive: true,
    //         pageLength: 25,
    //         retrieve: true, // ✅ Prevents "Cannot reinitialise DataTable" error
    //         destroy: true,  // ✅ Destroy existing table before reinitializing
    //         language: {
    //             search: "Search:",
    //             lengthMenu: "Show _MENU_ entries",
    //             info: "Showing _START_ to _END_ of _TOTAL_ entries",
    //         }
    //     });
    // });

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
