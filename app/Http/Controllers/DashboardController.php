<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Obat;
use App\Models\Pengiriman;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        // KPI: penjualan hari ini
        $penjualanHariIni = Penjualan::whereDate('tgl_penjualan', $today)->sum('total_bayar');

        // KPI: penjualan bulan ini
        $penjualanBulanIni = Penjualan::whereBetween('tgl_penjualan', [$monthStart, $monthEnd])->sum('total_bayar');

        // KPI: pembelian bulan ini
        $pembelianBulanIni = Pembelian::whereBetween('tgl_pembelian', [$monthStart, $monthEnd])->sum('total_bayar');

        // KPI: stok menipis (threshold 10, bisa kamu ubah)
        $threshold = 10;
        $stokMenipisCount = Obat::where('stok', '<=', $threshold)->count();

        // Top obat terjual 30 hari terakhir (by qty)
        $last30 = now()->subDays(30)->toDateString();

        $topObatTerjual = DB::table('detail_penjualan as dp')
            ->join('penjualan as p', 'p.id', '=', 'dp.id_penjualan')
            ->join('obat as o', 'o.id', '=', 'dp.id_obat')
            ->select(
                'o.id',
                'o.nama_obat',
                DB::raw('SUM(dp.jumlah_beli) as qty'),
                DB::raw('SUM(dp.subtotal) as omzet')
            )
            ->whereDate('p.tgl_penjualan', '>=', $last30)
            ->groupBy('o.id', 'o.nama_obat')
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

        // Stok menipis list
        $stokMenipis = Obat::orderBy('stok', 'asc')
            ->limit(10)
            ->get(['id', 'nama_obat', 'stok']);

        // Pengiriman terbaru
        $pengirimanTerbaru = Pengiriman::with(['penjualan.pelanggan', 'penjualan.jenisPengiriman'])
            ->latest()
            ->limit(8)
            ->get();

        // Penjualan terbaru
        $penjualanTerbaru = Penjualan::with(['pelanggan', 'pengiriman'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'penjualanHariIni',
            'penjualanBulanIni',
            'pembelianBulanIni',
            'stokMenipisCount',
            'threshold',
            'topObatTerjual',
            'stokMenipis',
            'pengirimanTerbaru',
            'penjualanTerbaru'
        ));
    }
}
