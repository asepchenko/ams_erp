<tr>
    <td></td>
    <td></td>
    <td>GrandTotal</td>
    @php
    $collection = collect($datanya);
    $budget = $collection->sum('jum_budget');
    $progress = $collection->sum('progress');
    $realisasi = $collection->sum('jum_real');
    $sisa = $collection->sum('jum_sisa');
    $persentase = ($realisasi / $budget) * 100;
    @endphp
    <td>{{ number_format($budget,0) }}</td>
    <td>{{ number_format($progress,0) }}</td>
    <td>{{ number_format($realisasi,0) }}</td>
    <td>{{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
    <td>{{ number_format($persentase,2) }}%</td>
</tr>