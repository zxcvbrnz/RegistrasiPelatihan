import './bootstrap';
import Swal from 'sweetalert2';

const successMessage = document.querySelector('[data-sweetalert-success]');

if (successMessage) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: successMessage.textContent.trim(),
        confirmButtonText: 'Mengerti',
        confirmButtonColor: '#167c72',
    });
}

document.querySelectorAll('[data-single-submit]').forEach((form) => {
    form.addEventListener('submit', () => {
        const submitButton = form.querySelector('button[type="submit"]');

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.setAttribute('aria-busy', 'true');
            submitButton.textContent = 'Mengirim...';
        }
    });
});

document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: 'Hapus data peserta?',
            text: `Data ${form.dataset.name} akan dihapus dan tidak dapat dipulihkan.`,
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#c74f47',
            cancelButtonColor: '#829098',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
