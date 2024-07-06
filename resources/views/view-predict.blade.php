<x-table-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Prediksi') }}
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
                <div class="card-header text-black" id="btnContainer">
                    <div class="input-group" style="width: 40%">
                        <span class="input-group-text">Alpha</span>
                        <input type="number" step="0.01" min="0.01" max="1" class="form-control" id="inputAlpha"
                               placeholder="Nilai Alpha" name="alpha" aria-label="Nilai alpha"
                               aria-describedby="btnPredict" required>
                        <button type="button" id="btnPredict" class="btn btn-primary me-2" style="min-width: 78px"
                                disabled>
                            Proses Prediksi
                        </button>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table id="dataTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                    </table>
                </div>

                <div class="card-footer text-black" id="testContainer" style="text-align: -webkit-center">
                    {{--                    <h5 class="mt-3">Prediksi hasil panen padi periode <strong>2019</strong> adalah <strong>90</strong> ton</h5>--}}
                    <table class="table table-md table-bordered mt-4 text-center" style="width: 50%">
                        <thead>
                        <tr>
                            <th colspan="2" scope="col">Hasil Pengujian</th>
                        </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        <tr>
                            <td>MAD</td>
                            <td id="resultMad">-</td>
                        </tr>
                        <tr>
                            <td>MSE</td>
                            <td id="resultMse">-</td>
                        </tr>
                        <tr>
                            <td>MAPE</td>
                            <td id="resultMape">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('extra-script')
        <script type="text/javascript" src="{{ asset('js/extra.js') }}"></script>
        <script>
            const resultMad = $('#resultMad')
            const resultMse = $('#resultMse')
            const resultMape = $('#resultMape')

            const clearResultPredict = () => {
                resultMad.text('-')
                resultMse.text('-')
                resultMape.text('-')
            }

            const updateResultPredict = (arrayData) => {
                /*if (arrayActual.length === 0 || arrayPredict.length === 0) {
                    clearResultPredict()
                    console.log('All array is empty.')
                    return
                }
                if (arrayPredict.length < 2) {
                    clearResultPredict()
                    console.log('Data Predict need at least 2 record.')
                    return
                }
                if (arrayActual.length !== arrayPredict.length) {
                    clearResultPredict()
                    console.log('Data Actual and Predict not same size.')
                    return
                }*/

                const absoluteError = []
                const errorError = []
                const percentageError = []
                arrayData.forEach((item) => {
                    if(!(item.produksi_actual == null) && !(item.produksi_predict == null)){
                        const actual = item.produksi_actual
                        const predict = item.produksi_predict

                        const error = actual - predict
                        const absError = Math.abs(error)
                        absoluteError.push(absError);
                        const eError = error * error
                        errorError.push(eError);
                        const pError = Math.abs((error / actual) * 100)
                        percentageError.push(pError);

                        console.log(
                            'Actual: ' + actual + ' Predict: ' + predict
                            + ' absoluteError: ' + absError + ' errorError: ' + eError
                            + ' percentageError: ' + pError
                        )
                    }
                })

                if (absoluteError.length !== 0 && errorError.length !== 0 && percentageError.length !== 0){
                    const mad = (absoluteError.reduce((a, b) => a + b, 0) / absoluteError.length).toFixed(2)
                    const mse = (errorError.reduce((a, b) => a + b, 0) / errorError.length).toFixed(2)
                    const mape = (percentageError.reduce((a, b) => a + b, 0) / percentageError.length).toFixed(2)
                    console.log('MAD: ' + mad + ' MSE: ' + mse + ' MAPE: ' + mape)
                    resultMad.text(mad)
                    resultMse.text(mse)
                    resultMape.text(mape + '%')
                }
            }

            const testResultPredict = () => {
                updateResultPredict(
                    // actual
                    [125097, 168649, 169963, 162748, 159544],
                    // predict
                    [132351, 131188.65, 154342.42, 162658.70, 169049.72]
                )
            }
            $(document).ready(function () {
                //$.fn.dataTable.ext.errMode = 'throw';
                const userLocale = window.navigator.language || window.navigator.userLanguage

                const btnPredict = $('#btnPredict')
                const testContainer = $('#testContainer')
                const inputAlpha = $('#inputAlpha')

                moment.locale(userLocale);

                const table = $('#dataTable').DataTable({
                    ajax: '{{ route('data.predict') }}',
                    processing: true,
                    deferRender: true,
                    autoWidth: true,
                    responsive: true,
                    fixedHeader: true,
                    order: [[1, 'asc']],
                    columns: [
                        {
                            title: 'No', data: null, className: 'text-center',
                            orderable: false, sortable: false, searchable: false
                        },
                        {
                            title: 'Tahun', data: 'tahun', className: 'text-center',
                            render: function (data, type, row) {
                                if(data === null) return data;
                                // data tahun is 'date' not string
                                return moment(data.toString()).format('YYYY')
                            }
                        },
                        {
                            title: 'Produksi (Aktual)',
                            data: 'produksi_actual',
                            className: 'text-center',
                            render: function (data, type, row) {
                                if (type === 'display') {
                                    if (row.produksi_actual === null) {
                                        return ('-');
                                    }
                                    return (row.produksi_actual).toFixed(2)
                                } else {
                                    return data
                                }
                            },
                        },
                        {
                            title: 'Produksi (Prediksi)',
                            data: 'produksi_predict',
                            className: 'text-center',
                            render: function (data, type, row) {
                                if (type === 'display') {
                                    if (row.produksi_predict === null) {
                                        return ('-');
                                    }
                                    return (row.produksi_predict).toFixed(2)
                                } else {
                                    return data
                                }
                            },
                        },
                        {
                            title: 'Error',
                            data: null,
                            className: 'text-center',
                            render: function (data, type, row) {
                                if (type === 'display') {
                                    if (row.produksi_predict === null || row.produksi_actual === null) {
                                        return ('-');
                                    }
                                    return (row.produksi_predict - row.produksi_actual).toFixed(2)
                                } else {
                                    return data
                                }
                            },
                        },
                        {
                            title: 'Error (%)',
                            data: null,
                            className: 'text-center',
                            render: function (data, type, row) {
                                if (type === 'display') {
                                    if (row.produksi_predict === null || row.produksi_actual === null) {
                                        return ('-');
                                    }
                                    return (Math.abs(
                                        (row.produksi_predict - row.produksi_actual) / row.produksi_actual
                                    ) * 100).toFixed(2)
                                } else {
                                    return data
                                }
                            },
                        },
                    ]
                });

                table.on('order.dt search.dt page.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied', page: 'applied'}).nodes().each((cell, i) => {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                table.on('init.dt', (e, settings, jsonObj) => {
                    updateResultPredict(jsonObj.data)
                    btnPredict.prop('disabled', false)
                    btnPredict.on('click', function () {
                        const alpha = inputAlpha.val().trim()
                        if(alpha === '' || alpha < 0.01 || alpha > 1){
                            inputAlpha.addClass('is-invalid')
                            return
                        } else {
                            inputAlpha.removeClass('is-invalid')
                        }

                        $.ajax({
                            url: '{{ route('make.predict') }}',
                            type: 'POST',
                            contentType: "application/json; charset=utf-8",
                            data: JSON.stringify({
                                _token: '{{ csrf_token() }}',
                                data: table.data().toArray(),
                                alpha: alpha,
                            }),
                            beforeSend: function () {
                                btnPredict.prop('disabled', true)
                                btnPredict.html('<i class="fas fa-spinner fa-spin"></i> Memproses...')
                            },
                            success: function (data, textStatus, jqXHR) {
                                console.log('Ajax Success', data, textStatus, jqXHR)
                                // add data into datatable
                                table.ajax.reload(null, false);
                                updateResultPredict(data.data)
                                // testContainer.append('<h5 class="mt-3">Prediksi hasil panen padi periode <strong>2019</strong> adalah <strong>90</strong> ton</h5>')
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                // show toast error
                                console.log('Ajax Error', textStatus, errorThrown)
                            },
                            complete: function (jqXHR, textStatus) {
                                // called when the request finishes (after success and error callbacks are executed).
                                //  1. remove spinner loading
                                console.log('Ajax Complete', textStatus)
                                btnPredict.prop('disabled', false)
                                btnPredict.html('Proses Prediksi')
                            }
                        });
                    });
                });

                let syncID = -1
                // 5 minutes refresh
                syncID = window.setInterval(function () {
                    table.ajax.reload(null, false);
                    console.log('View predict','Datatables, refreshing data with ID', syncID)
                }, 300000)
            });
        </script>
    @endpush
</x-table-layout>

