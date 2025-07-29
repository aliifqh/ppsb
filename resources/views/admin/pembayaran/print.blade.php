@php
  // Data yang dibutuhkan: $pembayaran (relasi: santri, gelombang, orangTua)
  $santri = $pembayaran->santri;
  $gelombang = $santri->gelombang;
  $orangTua = $santri->orangTua;
  $type = $jenis ?? null;
  $status_adm = $pembayaran->status_administrasi;
  $status_du = $pembayaran->status_daftar_ulang;
  $isLunas = ($type === 'administrasi' && $status_adm === 'diverifikasi') || ($type === 'daftar_ulang' && $status_du === 'diverifikasi');
  $badgeColor = $isLunas ? '#059669' : (($status_adm === 'sudah_bayar' || $status_du === 'sudah_bayar') ? '#eab308' : '#ef4444');
  $badgeText = $isLunas ? 'LUNAS' : (($status_adm === 'sudah_bayar' || $status_du === 'sudah_bayar') ? 'Menunggu Verifikasi' : 'Belum Bayar');
  $rows = [];
  if ($type === 'administrasi') $rows[] = ['desc' => 'Biaya Administrasi', 'amount' => $gelombang->biaya_administrasi ?? 0];
  if ($type === 'daftar_ulang') $rows[] = ['desc' => 'Biaya Daftar Ulang', 'amount' => $gelombang->biaya_daftar_ulang ?? 0];
  $total = array_sum(array_column($rows, 'amount'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=21cm, initial-scale=1.0">
  <title>Invoice Pembayaran - {{ $santri->nama }}</title>
  <style>
    @page { size: A4; margin: 0; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f4f6fa;
      color: #222;
      margin: 0;
      padding: 0;
    }
    .container {
      background: #fff;
      min-width: 21cm; max-width: 21cm;
      min-height: 29.7cm; max-height: 29.7cm;
      margin: 0 auto;
      padding: 2.2cm 1.5cm 1.5cm 1.5cm;
      box-sizing: border-box;
      border-radius: 18px;
      box-shadow: 0 4px 32px #0001;
      position: relative;
    }
    .header {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .header img { height: 64px; margin-bottom: 0.5rem; }
    .invoice-title {
      font-size: 2.3rem;
      font-weight: 800;
      color: #047857;
      margin: 0.2rem 0 0.5rem 0;
      letter-spacing: 1.5px;
      text-transform: uppercase;
    }
    .invoice-number {
      color: #64748b;
      font-size: 1.1rem;
      margin-bottom: 0.2rem;
      font-weight: 500;
    }
    .status-badge {
      display: inline-block;
      font-size: 1.1rem;
      font-weight: 700;
      color: #fff;
      background: {{ $badgeColor }};
      border-radius: 999px;
      padding: 0.18rem 1.3rem 0.18rem 1.3rem;
      letter-spacing: 1px;
      margin-left: 0.7rem;
      box-shadow: 0 2px 8px #0001;
      text-shadow: 0 1px 2px #0002;
      border: none;
      vertical-align: middle;
    }
    .date {
      color: #64748b;
      font-size: 1rem;
      margin-bottom: 1.2rem;
      font-weight: 400;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 2.2rem;
      gap: 2.5rem;
    }
    .info-block { flex: 1; font-size: 1.04rem; }
    .info-block .label { font-weight: 700; color: #0f172a; margin-bottom: 0.2rem; font-size: 1.08rem; letter-spacing: 0.2px; }
    .info-block .value { color: #222; font-size: 1.01rem; margin-bottom: 0.2rem; }
    .invoice-table {
      border-collapse: collapse;
      width: 100%;
      margin-bottom: 1.5rem;
      font-size: 1.04rem;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 1px 4px #0001;
      table-layout: fixed;
    }
    .invoice-table th, .invoice-table td {
      border: 1px solid #e5e7eb;
      padding: 12px 16px;
      background: #fff;
      color: #222;
      vertical-align: middle;
      transition: background 0.2s;
    }
    .invoice-table th {
      background: #f1f5f9;
      font-weight: 700;
      font-size: 1.08rem;
      letter-spacing: 0.5px;
      color: #334155;
      border-bottom: 2px solid #e5e7eb;
      text-align: left;
    }
    .invoice-table th:last-child, .invoice-table td:last-child {
      text-align: right;
    }
    .invoice-table tr:nth-child(even) td {
      background: #f7fafc;
    }
    .invoice-table tr.total-row td {
      font-weight: 800;
      color: #059669;
      background: #e6f9f2;
      font-size: 1.13rem;
      border-top: 3px solid #059669;
      text-align: right;
      padding-top: 16px;
      padding-bottom: 16px;
    }
    .invoice-table tr.total-row td:first-child {
      text-align: left;
    }
    .notes {
      color: #334155;
      font-size: 1.01rem;
      margin-top: 2.2rem;
      margin-bottom: 0.5rem;
      background: #f8fafc;
      border-radius: 8px;
      padding: 1.1rem 1.5rem;
      box-shadow: 0 1px 4px #0001;
    }
    .notes b { color: #047857; }
    .notes span { color: #059669; font-style: italic; display: block; margin-top: 0.5rem; }
    .footer {
      text-align: center;
      color: #94a3b8;
      font-size: 0.97rem;
      border-top: 1px solid #e2e8f0;
      padding-top: 1.2rem;
      margin-top: 2.5rem;
      letter-spacing: 0.2px;
    }
    .print-hide {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin: 1.5rem 0;
    }
    .print-hide button {
      padding: 0.7rem 2.2rem;
      border-radius: 8px;
      font-weight: 700;
      font-size: 1.07rem;
      border: none;
      box-shadow: 0 1px 4px #0001;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      outline: none;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .print-hide .btn-back {
      background: #e5e7eb;
      color: #222;
    }
    .print-hide .btn-back:hover {
      background: #d1d5db;
    }
    .print-hide .btn-print {
      background: #059669;
      color: #fff;
    }
    .print-hide .btn-print:hover {
      background: #047857;
      color: #fff;
    }
    @media print {
      body { background: #fff !important; }
      .container { box-shadow: none !important; border-radius: 0 !important; }
      .footer { color: #aaa !important; }
      .print-hide { display: none !important; }
    }
    @media (max-width: 600px) {
      .footer > div {
        flex-direction: column !important;
        gap: 0.1rem !important;
      }
      .footer span[style*='inline-block'] {
        display: none !important;
      }
    }
    @media screen {
      .print-only { display: none !important; }
    }
    @media print {
      .print-only { display: block !important; }
    }
    .watermark-lunas {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-20deg);
      font-size: 7rem;
      color: #059669;
      font-weight: 800;
      letter-spacing: 0.3em;
      z-index: 9999;
      pointer-events: none;
      user-select: none;
      opacity: 0.13;
      white-space: nowrap;
      display: none;
      text-shadow: 1px 1px 4px #fff;
      border-radius: 8px;
    }
    @media print {
      .watermark-lunas { display: block !important; }
    }
    .watermark-lunas-long {
      font-size: 4.5rem;
    }
  </style>
</head>
<body>
  @if (!($isPublic ?? false))
  <div class="print-hide">
    <button class="btn-back" id="btn-back"><span style="font-size:1.2em;">&laquo;</span> Kembali</button>
    <button class="btn-print" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
  </div>
  @else
  <div class="print-hide">
    <button class="btn-print" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
  </div>
  @endif
  <div class="container">
    <div class="header">
      <img src="{{ asset('/img/logo-ppin.png') }}" alt="Logo PPIN Ngruki">
      <div class="invoice-title">INVOICE PEMBAYARAN</div>
      <div class="invoice-number">No. Invoice: {{ $santri->nomor_pendaftaran ?? '-' }}</div>
      {{-- Watermark LUNAS hanya saat print --}}
      @if($badgeText === 'LUNAS')
        <div class="watermark-lunas print-only">LUNAS</div>
      @elseif($badgeText === 'Menunggu Verifikasi')
        <div class="watermark-lunas watermark-lunas-long print-only" style="color:#eab308;line-height:1.1;text-align:center;">MENUNGGU<br>VERIFIKASI</div>
      @elseif($badgeText === 'Belum Bayar')
        <div class="watermark-lunas watermark-lunas-long print-only" style="color:#ef4444;line-height:1.1;text-align:center;">BELUM<br>BAYAR</div>
      @endif
    </div>
    <div class="info-row">
      <div class="info-block">
        <div class="label">Ditagihkan Kepada</div>
        <ul style="list-style:none; padding:0; margin:0;">
          <li><b>Nama:</b> <span style="font-weight:400;">{{ $santri->nama }}</span></li>
          <li><b>Orang Tua/Wali:</b> <span style="font-weight:400;">{{ $orangTua->nama_ayah ?? $orangTua->nama_ibu ?? '-' }}</span></li>
          <li><b>No. WhatsApp:</b> <span style="font-weight:400;"><img src='https://img.icons8.com/color/16/000000/whatsapp--v1.png' style='vertical-align:middle;margin-right:4px;'>{{ $orangTua->wa_ayah ?? $orangTua->wa_ibu ?? '-' }}</span></li>
          <li><b>Unit:</b> <span style="font-weight:400;">{{ $santri->unit }}</span></li>
          <li><b>Alamat:</b> <span style="font-weight:400;">{{ $orangTua->alamat ?? '-' }}</span></li>
        </ul>
      </div>
      <div class="info-block">
        <div class="label">Diterbitkan Oleh</div>
        <div class="value">Pondok Pesantren Al-Mukmin Ngruki</div>
        <div class="value">Jl. Semen Romo Cemani 90, Ngruki, Cemani, Grogol, Sukoharjo, Jawa Tengah 57552</div>
        <div class="value">Telp: 0271 719171</div>
      </div>
    </div>
    <table class="invoice-table">
      <thead>
        <tr>
          <th>Keterangan</th>
          <th>Nominal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $row)
          <tr>
            <td>{{ $row['desc'] }}</td>
            <td>Rp {{ number_format($row['amount'], 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="notes">
      <b>Catatan:</b> Invoice ini diterbitkan oleh Pondok Pesantren Al-Mukmin Ngruki sebagai bukti pembayaran yang sah. Mohon simpan invoice ini sebagai arsip. Semoga Allah memberkahi ilmu dan amal ananda.<br>
      <span>"Barangsiapa menempuh jalan untuk mencari ilmu, maka Allah akan mudahkan baginya jalan menuju surga." (HR. Muslim)</span>
    </div>
    <div class="footer" style="text-align:center; margin-top:2.5rem; color:#64748b; font-size:1.01rem;">
      &copy; {{ now()->year }} <b>Pondok Pesantren Al-Mukmin Ngruki</b>. Semua hak cipta dilindungi.<br>
      Website:
      <a href="https://almukminngruki.or.id/" target="_blank" style="color:#047857; text-decoration:underline;">
        almukminngruki.or.id
      </a>
    </div>
    <div class="tanggal-cetak print-only" style="text-align:center; margin-top:1.5rem; color:#64748b; font-size:0.98rem; width:100%; position:fixed; bottom:1.2rem; left:0;">
      Tanggal Cetak: {{ now()->format('d F Y (H:i)') }}
    </div>
  </div>
  <script>
    const btnBack = document.getElementById('btn-back');
    if (btnBack) {
        btnBack.addEventListener('click', function(e) {
        e.preventDefault();
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/admin/pembayaran'; // fallback ke index pembayaran
        }
        });
    }
  </script>
</body>
</html>
