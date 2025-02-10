<tr>
    <td></td>
    <td>GrandTotal</td>
    <td></td>
    <td></td>
    @php
    $collection = collect($datanya);
    $budget = $collection->sum('budgetnya');
    $progress = $collection->sum('belum');
    $realisasi = $collection->sum('closed');
    $sisa = $budget - ($progress + $realisasi);
    $persentase = ($realisasi / $budget) * 100;
    @endphp
    <td>{{ number_format($budget,0) }}</td>
    <td>{{ number_format($progress,0) }}</td>
    <td>{{ number_format($realisasi,0) }}</td>
    <td>{{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
    <td>{{ number_format($persentase,2) }}%</td>
</tr>