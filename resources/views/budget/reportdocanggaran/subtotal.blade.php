<tr>
    <td>{{ $current_kode_group }}</td>
    <td>SubTotal</td>
    @php
    $collection = collect($datanya);
    $budget = $collection->where('kode_group', $current_kode_group)->sum('jum_budget');
    $progress = $collection->where('kode_group', $current_kode_group)->sum('progress');
    $realisasi = $collection->where('kode_group', $current_kode_group)->sum('jum_real');
    $sisa = $collection->where('kode_group', $current_kode_group)->sum('jum_sisa');
    $persentase = ($realisasi / $budget) * 100;
    @endphp
    <td>{{ number_format($budget,0) }}</td>
    <td>{{ number_format($progress,0) }}</td>
    {{-- <td>{{ number_format($progress,0) }}</td> --}}
    <td>{{ number_format($realisasi,0) }}</td>
    {{-- <td>{{ number_format($realisasi,0) }}</td> --}}
    <td>{{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
    <td>{{ number_format(($realisasi/$budget)*100,2) }}%</td>
</tr>
