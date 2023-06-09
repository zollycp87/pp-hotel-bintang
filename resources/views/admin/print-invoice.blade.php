<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <div class="invoice-title">
                <h4 class="float-end font-size-12">Status <span
                        class="badge bg-success font-size-12 ms-2">{{ $item->status_booking }}</span></h4>
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
    </div>
</body>
</html>