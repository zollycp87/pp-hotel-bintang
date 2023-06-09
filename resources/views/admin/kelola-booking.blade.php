@extends('layouts.main-admin')
@section('content')
    <div class="pagetitle">
        <h1>Kelola Data Booking</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Kelola Data Booking</li>
                <li class="breadcrumb-item active">Data Booking</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title d-flex justify-content-start">Data Booking</h5>
                <div class="d-flex align-items-center">
                    <form action="{{ route('booking.filter') }}" method="post" class="mx-2">
                        @csrf
                        <div class="input-group">
                            @if (Route::currentRouteName() == 'booking.filter')
                                <input type="date" class="form-control @error('booking-date') is-invalid @enderror"
                                    id="booking-date" name="booking-date" value="{{ $bookingDate }}" placeholder="">
                                @error('booking-date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <input type="date" class="form-control @error('booking-date') is-invalid @enderror"
                                    id="booking-date" name="booking-date"
                                    value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="">
                                @error('booking-date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                            <button type="submit" class="btn btn-secondary"><i class="bi bi-funnel me-1"></i></button>
                        </div>
                    </form>
                    <a href="{{ route('booking.create') }}" type="button" class="btn btn-primary"><i
                            class="bi bi-plus me-1"></i> Tambah Data</a>
                </div>
            </div>


            @include('komponen.pesan')

            <!-- Bordered Table -->
            <table class="table table-bordered" id="booking">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        {{-- <th scope="col">Invoice</th> --}}
                        <th scope="col">Nama Customer</th>
                        <th scope="col">Tanggal Pesan</th>
                        <th scope="col">Total Bayar</th>
                        <th scope="col">Status Bayar</th>
                        <th scope="col">Status Booking</th>
                        <th scope="col" class="noExp">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bookingTable">
                    @php($nomor_urut = 1)
                    @forelse ($posts as $item)
                        <tr>
                            <th scope="row">{{ $nomor_urut++ }}</th>
                            {{-- <td>{{ substr($item->invoice, 0, 14) }}</td> --}}
                            <td>{{ $item->customer->nama }}</td>
                            <td>{{ $item->tanggal_pesan }}</td>
                            {{-- @dd($item->detailBayar) --}}
                            <td>
                                @foreach ($item->detailBayar as $detailBayar)
                                    Rp {{ number_format($detailBayar->total_bayar, 0, '.', ',') }}
                                    <br>
                                @endforeach
                            </td>

                            <td>
                                @foreach ($item->detailBayar as $detailBayar)
                                    @if ($detailBayar->status_bayar == 'DP')
                                        <span class="badge text-bg-primary">DP</span>
                                        @if ($detailBayar->bukti_bayar !== null && $detailBayar->bukti_bayar !== '-')
                                            <button type="button" class="text-primary btn-details noExp"
                                                style="border: none; background: transparent;" data-bs-toggle="modal"
                                                data-bs-target="#buktibayar{{ $item->invoice }}">Bukti</button>

                                            <!-- Modal -->
                                            <div class="modal fade noExp" id="buktibayar{{ $item->invoice }}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Bayar
                                                                {{ $item->invoice }}
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3 d-flex justify-content-center">
                                                                <img src="{{ url('foto') . '/' . $detailBayar->bukti_bayar }}"
                                                                    alt="" width="300px" height="300px">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @elseif ($detailBayar->status_bayar == 'Pelunasan')
                                        <br>
                                        <span class="badge text-bg-info">Pelunasan</span>
                                    @elseif ($detailBayar->status_bayar == 'Full Payment')
                                        <span class="badge text-bg-success">Full Payment</span>
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @if ($item->status_booking == 'New')
                                    <span class="badge text-bg-primary">New</span>
                                @elseif ($item->status_booking == 'Booking')
                                    <span class="badge text-bg-warning">Booking</span>
                                @elseif ($item->status_booking == 'Check In')
                                    <span class="badge text-bg-success">Check In</span>
                                @elseif ($item->status_booking == 'Check Out')
                                    <span class="badge text-bg-danger">Check Out</span>
                                @elseif ($item->status_booking == 'Cancel')
                                    <span class="badge text-bg-dark">Cancel</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-between noExp">
                                    <button type="button" class="text-primary btn-details"
                                        style="border: none; background: transparent;" data-bs-toggle="modal"
                                        data-bs-target="#detailBooking{{ $item->invoice }}">Detail</button>
                                    |
                                    <a href="{{ route('booking.edit', $item->invoice) }}" class="text-secondary">Edit</a>
                                    |
                                    {{-- <form action="{{ route('booking.destroy', $item->invoice) }}" method="post"
                                        onsubmit="return confirm('Yakin akan menghapus data ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" name="submit" class="text-danger"
                                            style="border: none; background: transparent;">Hapus</button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div>
                    @endforelse
                </tbody>
            </table>
            <!-- End Bordered Table -->
        </div>
    </div>

    <!-- Modal Detail -->
    @foreach ($posts as $item)
        <div class="modal fade" id="detailBooking{{ $item->invoice }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $item->invoice }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBodyDetail">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-12">
                                Status
                                @if ($item->status_booking == 'New')
                                    <span class="badge text-bg-primary">New</span>
                                @elseif ($item->status_booking == 'Booking')
                                    <span class="badge text-bg-warning">Booking</span>
                                @elseif ($item->status_booking == 'Check In')
                                    <span class="badge text-bg-success">Check In</span>
                                @elseif ($item->status_booking == 'Check Out')
                                    <span class="badge text-bg-danger">Check Out</span>
                                @elseif ($item->status_booking == 'Cancel')
                                    <span class="badge text-bg-dark">Cancel</span>
                                @endif
                            </h4>
                            <div class="mb-3">
                                <h3 class="mb-1 text-muted">Hotel Bintang</h3>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-muted">
                                    <h5 class="font-size-16 mb-3">Atas Nama:</h5>
                                    <h5 class="font-size-15 mb-2">{{ $item->customer->nama }}</h5>
                                    <p class="mb-1">Check In :
                                        {{ \Carbon\Carbon::parse($item->start_date)->format('j F Y') }}</p>
                                    <p class="mb-1">Check Out :
                                        {{ \Carbon\Carbon::parse($item->end_date)->format('j F Y') }}</p>
                                    @php($lamaHari = \Carbon\Carbon::parse($item->end_date)->diffInDays(\Carbon\Carbon::parse($item->start_date)))
                                    <p class="mb-1">Lama Hari :
                                        {{ $lamaHari }} Hari</p>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-sm-6">
                                <div class="text-muted text-sm-end">
                                    <div>
                                        <h5 class="font-size-15 mb-1">Invoice No:</h5>
                                        <p>#{{ $item->invoice }}</p>
                                    </div>
                                    <div class="mt-3">
                                        <h5 class="font-size-15 mb-1">Invoice Date:</h5>
                                        <p>{{ \Carbon\Carbon::parse($item->tanggal_pesan)->format('j F Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->

                        <div class="py-2">
                            <h5 class="font-size-15">Detail Order</h5>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px;">No.</th>
                                            <th>Kategori Kamar</th>
                                            <th>Harga Kategori</th>
                                            <th>Jumlah Kamar</th>
                                            <th class="text-end" style="width: 120px;">Total</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @php($nomor_urut = 1)
                                        @php($total = 0)
                                        @php($totalKamar = 0)
                                        @foreach ($details as $detail)
                                            @if ($detail->invoice == $item->invoice)
                                                <tr>
                                                    <th scope="row">{{ $nomor_urut++ }}</th>
                                                    <td>{{ $detail->kategori->nama_kategori }}</td>
                                                    <td>Rp{{ number_format($detail->kategori->harga_kategori, 0, '.', ',') }}
                                                    </td>
                                                    <td>{{ $detail->jumlah_kamar }}</td>
                                                    @php($totalKamar += $detail->jumlah_kamar)
                                                    <td hidden>
                                                        {{ $totalKategori = $detail->kategori->harga_kategori * $totalKamar * $lamaHari }}
                                                    </td>
                                                    <td class="text-end">
                                                        Rp{{ number_format($totalKategori, 0, '.', ',') }}
                                                    </td>
                                                    <td hidden>{{ $total += $totalKategori }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <!-- end tr -->
                                        <tr>
                                            <th scope="row" colspan="4" class="border-0 text-end">Total</th>
                                            <td class="border-0 text-end">
                                                <h5 class="m-0 fw-semibold">Rp{{ number_format($total, 0, '.', ',') }}
                                                </h5>
                                            </td>
                                        </tr>
                                        <!-- end tr -->
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div><!-- end table responsive -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printModalContent()">Print</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('sidebar')
    @include('sidebar-admin')
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#booking').DataTable({
                dom: 'lBftrip',
                buttons: {
                    buttons: [{
                            extend: 'copy',
                            footer: true,
                            className: 'btn btn-primary my-3 mr-1',
                            exportOptions: {
                                columns: "thead th:not(.noExp)"
                            },

                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-primary my-3 mr-1',
                            footer: true,
                            exportOptions: {
                                columns: "thead th:not(.noExp)"
                            },

                        },
                        {
                            title: "Laporan Pemasukan Hotel Hari Ini",
                            extend: 'pdf',
                            className: 'btn btn-primary my-3 mr-1',
                            footer: true,
                            exportOptions: {
                                columns: "thead th:not(.noExp)",
                                customize: function(doc) {
                                    doc.querySelectorAll('.noExp').forEach(function(element) {
                                        element.parentNode.removeChild(element);
                                    });
                                }
                            },
                        },
                    ],
                }
            });
        });
    </script>
@endsection
