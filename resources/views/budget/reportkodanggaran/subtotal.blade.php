

<tr>
    <td align="center">SUBTOTAL</td>
    <td></td><td></td>
    @php
    $collection = collect($datanya);
    $colbudget = collect($databudget);
    $budget = $colbudget->where('keterangan', $current_kode_group)->sum('nilai_budget');
    $progress = $collection->where('descanggaran', $current_kode_group)->sum('belum');
    $realisasi = $collection->where('descanggaran', $current_kode_group)->sum('closed');
    if($budget == 0){
        $sisa = $budget - ($progress + $realisasi);
        $persentase = 0;
    }else{
        $sisa = $budget - ($progress + $realisasi);
        $persentase = ($realisasi / $budget) * 100;
    }
    @endphp
    <td>{{ number_format($budget,0) }}</td>
    <td><b><a href="{{url('budget/reportdetail')}}/search/{{ $kodegroup }}/{{ $tahun }}/{{ strtolower($data->periode) }}/{{ $anggaran }}/proses" style="color:black" target="_blank"> 
        {{ number_format($progress,0) }}</a></b></td>
    <td><b><a href="{{url('budget/reportdetail')}}/search/{{ $kodegroup }}/{{ $tahun }}/{{ strtolower($data->periode) }}/{{ $anggaran }}/closed" style="color:black" target="_blank"> 
        {{ number_format($realisasi,0) }}</a></b></td>
    <td>{{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
    <td>{{ number_format($persentase,2) }}%</td>
</tr>

