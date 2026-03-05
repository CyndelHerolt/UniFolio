    var yourModal = document.getElementById('staticBackdrop{{ commentaire.id }}');

    document.querySelectorAll('.btn-delete-comment').addEventListener('click', function() {
        var yourModal = document.getElementById('staticBackdrop{{ commentaire.id }}');

        yourModal.addEventListener('hidden.bs.modal', function () {
            document.querySelector('body').classList.remove('modal-open');
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });
    });