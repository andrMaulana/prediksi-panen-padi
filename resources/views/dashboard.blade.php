<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{--<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-jet-welcome />
            </div>--}}

            <div class="container-fluid text-center overflow-hidden sm:rounded-lg">
                <div class="row justify-content-evenly">
                    <div class="col-sm-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-wrap align-items-center justify-content-center" style="height: 200px">
                                <canvas id="chartKecamatan"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-wrap align-items-center justify-content-center" style="height: 200px">
                                <canvas id="chartTotalData"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-between my-5" style="min-height: 10em">
                    <div class="card" style="min-height: 25em">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-center">
                            <canvas id="chartActualPredict"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('extra-script')
        <script>
            const chartKecamatan = new Chart($('#chartKecamatan'), {
                type: 'doughnut',
                data: {
                    labels: {{ Js::from($array_kecamatan) }},
                    datasets: [{
                        label: 'Total Produksi',
                        data: {{ Js::from($array_produksi_kecamatan) }},
                        borderWidth: 1,
                        hoverOffset: 10,
                    }]
                },
                options: {
                    layout: {
                        padding: {
                            bottom: 10
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Total Produksi per Kecamatan'
                        },
                        colors: {
                            forceOverride: true
                        },
                    }
                }
            })
            const chartTotalData = new Chart($('#chartTotalData'), {
                type: 'pie',
                data: {
                    labels: ['Data Aktual', 'Data User'],
                    datasets: [{
                        label: 'Total',
                        data: {{ Js::from($array_total_data) }},
                        borderWidth: 1,
                        hoverOffset: 10,
                    }]
                },
                options: {
                    layout: {
                        padding: {
                            bottom: 10
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Total Data'
                        },
                        colors: {
                            forceOverride: true
                        },
                    }
                }
            })
            const chartActualPredict = new Chart($('#chartActualPredict'), {
                type: 'bar',
                data: {
                    // labels: [2013, 2014, 2015, 2016, 2017, 2018, 2019],
                    labels: {{ Js::from($array_year) }},
                    datasets: [
                        {
                            label: 'Data Aktual',
                            stack: 'Stack 0',
                            // data: [10, 11, 12, 13, 14, 15, 0],
                            data: {{ Js::from($array_actual) }},
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                            ],
                            borderColor: [
                                'rgb(255, 99, 132)',
                            ],
                            borderWidth: 1,
                            hoverOffset: 10,
                        },
                        {
                            label: 'Data Prediksi',
                            stack: 'Stack 1',
                            // data: [0, 12, 13, 14, 15, 16, 17],
                            data: {{ Js::from($array_predict) }},
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.5)'
                            ],
                            borderColor: [
                                'rgb(54, 162, 235)'
                            ],
                            borderWidth: 1,
                            hoverOffset: 10,
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            beginAtZero: true,
                            stacked: true,
                        }
                    },
                    interaction: {
                        intersect: false,
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Total Panen Padi per Tahun (Data Aktual vs Data Prediksi)'
                        },
                    }
                }
            })
        </script>
    @endpush
</x-app-layout>
