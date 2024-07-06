<x-table-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Aktual') }}
        </h2>
    </x-slot>

    <div class="container-fluid px-4 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <!-- Alert element -->
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible" role="alert">
                        <i class="fa-solid fa-triangle-exclamation flex-shrink-0 me-2"></i>
                        <div>{!! $error !!}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif

            <div class="card mb-4">

                <!-- Start of Modal Add/Update -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="staticBackdropLabel">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <form method="post" class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalTitle">Add Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-0">
                                <div class="px-4">
                                    @csrf
                                    <input type="hidden" name="id" id="updateID" value="">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="tahun" id="modalTahun"
                                               type="text" placeholder="Tahun" required/>
                                        <label for="modalTahun">Tahun</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="kecamatan" id="modalKecamatan" type="text"
                                               placeholder="Kecamatan" required/>
                                        <label for="modalKecamatan">Kecamatan</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="luas_lahan" id="modalLuasLahan" type="number"
                                               step="0.01" placeholder="Luas Lahan (Ha)" required/>
                                        <label for="modalLuasLahan">Luas Lahan (Ha)</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="produksi" id="modalProduksi" type="number"
                                               step="0.01" placeholder="Produksi (Ton)" required/>
                                        <label for="modalProduksi">Produksi (Ton)</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="" id="modalAction">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End of Modal Add/Update -->

                <!-- Start of Modal Delete -->
                <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="label">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <form method="post" class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="label">Hapus Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <p>Apakah anda yakin ingin menghapus data ini ?</p>
                                <div class="table-responsive">
                                    <table class="table table-bordered dt-responsive nowrap" style="width:100%">
                                    </table>
                                </div>
                                <input type="hidden" name="id" value="" id="deleteID">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-danger" id="deleteAction">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End of Modal Delete -->

                <div class="card-header text-black" id="btnContainer">
                    <button type="button" id="btnAdd" class="btn btn-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop" style="min-width: 78px" disabled>
                        Add
                    </button>
                    <button type="button" id="btnUpdate" class="btn btn-warning me-2" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop" style="min-width: 78px" disabled>
                        Update
                    </button>
                    <button type="button" id="btnDelete" class="btn btn-danger me-2" data-bs-toggle="modal"
                            data-bs-target="#delete" style="min-width: 78px" disabled>
                        Delete
                    </button>
                </div>

                <div class="card-body table-responsive">
                    <table id="dataTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('extra-script')
        <script>
            $(document).ready(function () {
                //$.fn.dataTable.ext.errMode = 'throw';
                const userLocale = window.navigator.language || window.navigator.userLanguage

                const btnAdd = $('#btnAdd')
                const btnUpdate = $('#btnUpdate')
                const btnDelete = $('#btnDelete')

                const modalDelete = $('#delete')
                const modalUpdateID = $('#updateID')
                const modalDeleteID = $('#deleteID')
                const modalTitle = $('#modalTitle')
                const modalAction = $('#modalAction')
                const modalActionDelete = $('#deleteAction')
                const modalTahun = $('#modalTahun')
                const modalKecamatan = $('#modalKecamatan')
                const modalLuasLahan = $('#modalLuasLahan')
                const modalProduksi = $('#modalProduksi')

                moment.locale(userLocale);
                let dateLang = 'en';
                if (userLocale === 'id' || userLocale === 'id-ID') {
                    dateLang = 'id'
                }

                modalTahun.datepicker({
                    startView: 2,
                    minViewMode: 2,
                    maxViewMode: 2,
                    todayBtn: "linked",
                    clearBtn: true,
                    daysOfWeekHighlighted: "0",
                    autoclose: true,
                    todayHighlight: true,
                    format: 'yyyy',
                    forceParse: true,
                    startDate: '2000',
                    endDate: new Date().getFullYear().toString(),
                    language: dateLang,
                });

                const table = $('#dataTable').DataTable({
                    ajax: '{{ route('data.actual') }}',
                    processing: true,
                    deferRender: true,
                    autoWidth: true,
                    responsive: true,
                    fixedHeader: true,
                    select: {
                        info: false
                    },
                    columns: [
                        {
                            title: 'No', data: null, className: 'text-center',
                            orderable: false, sortable: false, searchable: false
                        },
                        {
                            title: 'Tahun', data: 'tahun', className: 'text-center',
                            render: function (data, type, row) {
                                return moment(data.toString()).format('YYYY')
                            }
                        },
                        {title: 'Kecamatan', data: 'kecamatan', className: 'text-center'},
                        {title: 'Luas Lahan (Ha)', data: 'luas_lahan', className: 'text-center'},
                        {title: 'Produksi (Ton)', data: 'produksi', className: 'text-center'},
                    ],
                    order: [[1, 'asc']],
                });

                const tableDelete = modalDelete.find('table').DataTable({
                    autoWidth: true,
                    responsive: true,
                    columns: [
                        {
                            title: 'Tahun', data: 'tahun', className: 'text-center',
                            render: function (data, type, row) {
                                if(data === null) return data;
                                // data tahun is 'date' not string
                                return moment(data.toString()).format('YYYY')
                            }
                        },
                        {title: 'Kecamatan', data: 'kecamatan', className: 'text-center'},
                        {title: 'Luas Lahan', data: 'luas_lahan', className: 'text-center'},
                        {title: 'Produksi', data: 'produksi', className: 'text-center'},
                    ],
                    paging: false,
                    ordering: false,
                    searching: false,
                    info: false,
                });

                table.on('order.dt search.dt page.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied', page: 'applied'}).nodes().each((cell, i) => {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                table.on('init.dt', (e, settings, jsonObj) => {
                    btnAdd.prop('disabled', false)
                });

                const clearForm = () => {
                    modalTitle.text('Add Data')
                    modalUpdateID.val(undefined)
                    modalAction.prop('name', 'data.actual.store')
                    modalAction.text('Add')
                    modalTahun.val(undefined)
                    modalKecamatan.val(undefined)
                    modalLuasLahan.val(undefined)
                    modalProduksi.val(undefined)
                }

                btnAdd.on('click', function () {
                    clearForm()
                })

                table.on('select', (e, dt, type, indexes) => {
                    if (type === 'row') {
                        btnUpdate.prop('disabled', false)
                        btnDelete.prop('disabled', false)
                        // single selection
                        // if you want change to multi selection
                        // please first change dataTable configuration
                        const data = dt.row(indexes[0]).data();

                        btnUpdate.on('click', function () {
                            modalTitle.text('Update Data')
                            modalUpdateID.val(data.id)
                            modalAction.prop('name', 'data.actual.change')
                            modalAction.text('Update')
                            modalTahun.val(moment(data.tahun).year())
                            modalKecamatan.val(data.kecamatan)
                            modalLuasLahan.val(data.luas_lahan)
                            modalProduksi.val(data.produksi)
                        })

                        btnDelete.on('click', function () {
                            modalDeleteID.val(data.id)
                            // make table for deletion process
                            tableDelete.row.add(data).draw();
                            modalActionDelete.prop('name', 'data.actual.erase')
                        })
                    }
                });

                table.on('deselect', (e, dt, type, indexes) => {
                    if (type === 'row') {
                        btnUpdate.prop('disabled', true)
                        btnDelete.prop('disabled', true)

                        // clear table (delete) when deselect
                        tableDelete.clear().draw();
                        modalDeleteID.val(undefined)

                        // clear form (update) when deselect
                        clearForm()

                        // remove onClick event
                        btnUpdate.off()
                        btnDelete.off()
                    }
                });

                let syncID = -1
                // 5 minutes refresh
                syncID = window.setInterval(function () {
                    table.ajax.reload(null, false);
                    console.log('View actual','Datatables, refreshing data with ID', syncID)
                }, 300000)
            });
        </script>
    @endpush
</x-table-layout>
