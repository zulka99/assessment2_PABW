document.addEventListener('DOMContentLoaded', function () {
    const $ = window.jQuery;
    const page = document.getElementById('laporan-page');

    // Halaman selain laporan tidak memerlukan script ini.
    if (!page) return;

    const base = page.dataset.base;
    const petugas = page.dataset.petugas === '1';
    const form = document.getElementById('form-pengaduan');

    let listRequest = null;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    function notice(message, type = 'success') {
        $('#notice').empty().append(
            $('<div>')
                .addClass('alert alert-' + type)
                .text(message)
        );
    }

    function clearErrors() {
        $(form).find('.is-invalid').removeClass('is-invalid');
        $(form).find('.field-error').remove();
    }

    function failed(xhr) {
        if (xhr.statusText === 'abort') return;

        const body = xhr.responseJSON || {};

        const messages = {
            0: 'Koneksi gagal. Periksa apakah server masih berjalan.',
            401: 'Sesi berakhir. Silakan login kembali.',
            403: 'Kamu tidak memiliki izin melakukan tindakan ini.',
            404: 'Data tidak ditemukan.',
            419: 'Sesi atau token CSRF kedaluwarsa. Muat ulang halaman.',
            422: 'Periksa kembali data yang diisi.',
            500: 'Terjadi kesalahan server. Periksa log Laravel.'
        };

        notice(
            messages[xhr.status] || 'Permintaan gagal.',
            'danger'
        );

        if (body.errors) {
            Object.entries(body.errors).forEach(function ([name, errors]) {
                const input = form.elements.namedItem(name);

                if (!input) return;

                $(input).addClass('is-invalid').after(
                    $('<div>')
                        .addClass('invalid-feedback field-error')
                        .text(errors[0])
                );
            });
        }
    }

    function resetForm() {
        form.reset();
        $('#record-id').val('');

        $('#form-title').text(
            petugas
                ? 'Pilih laporan melalui tombol Tangani'
                : 'Buat laporan'
        );

        $('#save').prop('disabled', petugas);
        clearErrors();
    }

    function firstPageUrl() {
        return base + '?' + $('#search-form').serialize();
    }

    function loadTable(url = firstPageUrl()) {
        if (listRequest) {
            listRequest.abort();
        }

        $('#loading').removeClass('d-none');

        const request = $.ajax({
            url: url,
            method: 'GET',
            dataType: 'html'
        });

        listRequest = request;

        request.done(function (html) {
            $('#table-area').html(html);
        }).fail(failed).always(function () {
            if (listRequest === request) {
                listRequest = null;
                $('#loading').addClass('d-none');
            }
        });
    }

    $('#search-form').on('submit', function (event) {
        event.preventDefault();
        loadTable();
    });

    $('#table-area').on('click', '.pagination a', function (event) {
        event.preventDefault();
        loadTable(this.href);
    });

    $('#cancel').on('click', resetForm);

    $('#table-area').on('click', '.edit', function () {
        const button = $(this).prop('disabled', true);

        $.getJSON(this.dataset.url)
            .done(function (data) {
                resetForm();

                $('#record-id').val(data.id);
                $('#form-title').text(
                    (petugas ? 'Tangani laporan #' : 'Edit laporan #')
                    + data.id
                );

                Object.entries(data).forEach(function ([name, value]) {
                    const input = form.elements.namedItem(name);

                    if (input && input.type !== 'file') {
                        input.value = value ?? '';
                    }
                });

                $('#save').prop('disabled', false);

                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            })
            .fail(failed)
            .always(function () {
                button.prop('disabled', false);
            });
    });

    $(form).on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        const id = $('#record-id').val();
        const data = new FormData(form);

        let url = base;

        if (petugas) {
            if (!id) {
                notice('Pilih laporan melalui tombol Tangani.', 'warning');
                return;
            }

            url = base + '/' + id + '/status';
            data.set('_method', 'PATCH');
        } else if (id) {
            url = base + '/' + id;
            data.set('_method', 'PUT');
        }

        $('#save')
            .prop('disabled', true)
            .text('Menyimpan...');

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function (result) {
            resetForm();
            notice(result.message);
            loadTable();
        }).fail(failed).always(function () {
            const belumDipilih = petugas && !$('#record-id').val();

            $('#save')
                .prop('disabled', Boolean(belumDipilih))
                .text('Simpan');
        });
    });

    $('#table-area').on('click', '.delete', function () {
        if (!confirm('Yakin ingin menghapus laporan ini?')) {
            return;
        }

        const button = $(this).prop('disabled', true);

        $.ajax({
            url: this.dataset.url,
            method: 'DELETE',
            dataType: 'json'
        }).done(function (result) {
            resetForm();
            notice(result.message);
            loadTable();
        }).fail(failed).always(function () {
            button.prop('disabled', false);
        });
    });
});