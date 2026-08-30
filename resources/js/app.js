import DataTable from 'datatables.net-dt';
import { Modal } from 'bootstrap';

const languages = {
    ar: {
        search: 'بحث:',
        lengthMenu: 'عرض _MENU_',
        info: 'عرض _START_ إلى _END_ من _TOTAL_',
        infoEmpty: 'لا توجد نتائج',
        zeroRecords: 'لا توجد نتائج مطابقة',
        processing: 'جارٍ التحميل…',
        paginate: {
            first: 'الأول',
            last: 'الأخير',
            next: 'التالي',
            previous: 'السابق',
        },
    },
    en: {
        search: 'Search:',
        lengthMenu: 'Show _MENU_',
        info: 'Showing _START_ to _END_ of _TOTAL_',
        infoEmpty: 'No results',
        zeroRecords: 'No matching records',
        processing: 'Loading…',
    },
};

document.querySelectorAll('.admin-datatable').forEach((table) => {
    const columns = table.dataset.columnNames.split(',').map((data, index, all) => ({
        data,
        name: data,
        orderable: index !== all.length - 1,
        searchable: index !== all.length - 1,
    }));

    new DataTable(table, {
        processing: true,
        serverSide: true,
        ajax: {
            url: table.dataset.url,
            error: () => {
                const error = document.getElementById(table.dataset.errorTarget);

                if (error) {
                    error.hidden = false;
                }
            },
        },
        columns,
        order: [[0, 'asc']],
        pageLength: 15,
        language: languages[document.documentElement.lang] ?? languages.en,
    });
});

const remoteModalElement = document.getElementById('remote-crud-modal');
const deleteModalElement = document.getElementById('delete-confirmation-modal');
const inquiryStatusModalElement = document.getElementById('inquiry-status-modal');
const remoteModal = remoteModalElement ? new Modal(remoteModalElement) : null;
const deleteModal = deleteModalElement ? new Modal(deleteModalElement) : null;
const inquiryStatusModal = inquiryStatusModalElement ? new Modal(inquiryStatusModalElement) : null;

document.addEventListener('click', async (event) => {
    const remoteTrigger = event.target.closest('[data-modal-url]');

    if (remoteTrigger && remoteModal) {
        event.preventDefault();
        const body = remoteModalElement.querySelector('[data-remote-modal-body]');
        remoteModalElement.querySelector('[data-remote-modal-title]').textContent = remoteTrigger.dataset.modalTitle;
        body.innerHTML = '<div class="modal-loading" role="status">Loading…</div>';
        remoteModal.show();

        try {
            const response = await fetch(remoteTrigger.dataset.modalUrl, {
                headers: { Accept: 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) {
                throw new Error('Unable to load form');
            }

            body.innerHTML = await response.text();
        } catch {
            body.innerHTML = '<div class="alert alert-danger">The form could not be loaded. Please try again.</div>';
        }
    }

    const deleteTrigger = event.target.closest('[data-delete-action]');

    if (deleteTrigger && deleteModal) {
        deleteModalElement.querySelector('[data-delete-form]').action = deleteTrigger.dataset.deleteAction;
        deleteModalElement.querySelector('[data-delete-record-name]').textContent = deleteTrigger.dataset.deleteName;
        deleteModal.show();
    }

    const sidebarToggle = event.target.closest('[data-sidebar-toggle]');

    const inquiryStatusTrigger = event.target.closest('[data-inquiry-status-action]');

    if (inquiryStatusTrigger && inquiryStatusModal) {
        inquiryStatusModalElement.querySelector('[data-inquiry-status-form]').action = inquiryStatusTrigger.dataset.inquiryStatusAction;
        inquiryStatusModalElement.querySelector('[name="status"]').value = inquiryStatusTrigger.dataset.inquiryStatus;
        inquiryStatusModal.show();
    }

    if (sidebarToggle) {
        const isOpen = document.body.classList.toggle('sidebar-open');
        sidebarToggle.setAttribute('aria-expanded', String(isOpen));
    }

    if (event.target.closest('[data-sidebar-dismiss]')) {
        document.body.classList.remove('sidebar-open');
        document.querySelector('[data-sidebar-toggle]')?.setAttribute('aria-expanded', 'false');
    }
});
