<!DOCTYPE html>
<html>

<head>
    <title>Laporan Harga Pasar</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
        }

        table,
        th,
        td {
            border: 1px solid #333;
            word-wrap: break-word;
        }

        th {
            background-color: #0088CC;
            color: white;
            padding: 8px;
        }

        td {
            padding: 6px;
            text-align: left;
        }

        .pasar-title {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #333;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>PEMERINTAH KOTA PAREPARE</h2>
        <h3>UPTD PASAR KOTA PAREPARE</h3>
        <p>Laporan Harga: <strong>{{ str_replace('_', ' ', $nama_kategori) }}</strong></p>
        <p>Periode: {{ $bulan }} / {{ $tahun }}</p>
    </div>

    @foreach ($laporan as $nama_pasar => $items)
        <div class="pasar-title">Lokasi: {{ $nama_pasar }}</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 13%;">Kategori</th>
                    <th style="width: 25%;">Nama Barang</th>
                    <th style="text-align: right; width: 37.5%;">Harga Pedagang</th>
                    <th style="text-align: right; width: 12.5%;">Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($item->kategori) }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        @php
                            $hargaPedagangText = '-';
                            if (isset($item->harga_pedagang) && $item->harga_pedagang) {
                                $array = json_decode($item->harga_pedagang, true);
                                if (is_array($array)) {
                                    $hargaPedagangText = implode(', ', array_map(function($v) { return number_format($v, 0, ',', '.'); }, $array));
                                }
                            } else {
                                $arr = [];
                                if (isset($item->harga_pedagang_1) && $item->harga_pedagang_1) $arr[] = number_format($item->harga_pedagang_1, 0, ',', '.');
                                if (isset($item->harga_pedagang_2) && $item->harga_pedagang_2) $arr[] = number_format($item->harga_pedagang_2, 0, ',', '.');
                                if (isset($item->harga_pedagang_3) && $item->harga_pedagang_3) $arr[] = number_format($item->harga_pedagang_3, 0, ',', '.');
                                if (count($arr) > 0) $hargaPedagangText = implode(', ', $arr);
                            }
                        @endphp
                        <td style="text-align: right">{{ $hargaPedagangText }}</td>
                        <td style="text-align: right">{{ number_format($item->harga_hari_ini ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div style="margin-top: 30px; text-align: right;">
        <p>Parepare, {{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Kepala UPTD Pasar</strong></p>
    </div>

</body>

</html>
