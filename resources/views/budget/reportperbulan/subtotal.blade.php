<tr>
    <td>{{ $current_desc }}</td>
    <td>SUBTOTAL</td>    
    <td></td><td></td>
    @php
    $collection = collect($datanya);
    $budget = $collection->where('descanggaran', $current_desc)->sum('budgetnya');
    $progress = $collection->where('descanggaran', $current_desc)->sum('belum');
    $realisasi = $collection->where('descanggaran', $current_desc)->sum('closed');
    $sisa = $budget - ($progress + $realisasi);
    if($budget > 0){
        $persentase = ($realisasi / $budget) * 100;
    }else{
    $persentase = 0;
    }
    
    @endphp
    <td>{{ number_format($budget,0) }}</td>
    <td><b> 
        {{ number_format($progress,0) }}</b></td>
    {{-- <td>{{ number_format($progress,0) }}</td> --}}
    <td><b>
        {{ number_format($realisasi,0) }}</b></td>
    {{-- <td>{{ number_format($realisasi,0) }}</td> --}}
    <td>{{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
    <td>{{ number_format($persentase,2) }}%</td>
</tr>
{{-- 
<tr>
    <td>SUBTOTAL</td>
    <td></td><td></td>
    @php
    $collection = collect($datanya);
    $colbudget = collect($databudget);
    $budget = $colbudget->where('kode_group', $current_kode_group)->sum('nilai_budget');
    $progress = $collection->where('kode_group', $current_kode_group)->sum('belum');
    $realisasi = $collection->where('kode_group', $current_kode_group)->sum('closed');
    $sisa = $budget - ($progress + $realisasi);
    $persentase = ($realisasi / $budget) * 100;
    @endphp
    <td>{{ number_format($budget,0) }}</td>
    <td><b><a href="{{url('budget/reportkodanggaran')}}/search/{{ strtolower($data->periode) }}/{{ $tahun }}/{{ $current_kode_group }}" style="color:black" target="_blank"> 
        {{ number_format($progress,0) }}</a></b></td>
    {{-- <td>{{ number_format($progress,0) }}</td> 
    <td><b><a href="{{url('budget/reportkodanggaran')}}/search/{{ strtolower($data->periode) }}/{{ $tahun }}/{{ $current_kode_group }}" style="color:black" target="_blank"> 
        {{ number_format($realisasi,0) }}</a></b></td>
    {{-- <td>{{ number_format($realisasi,0) }}</td> 
    <td>{{ ($sisa < 0 ? "(".number_format(abs($sisa),0).")" : number_format($sisa,0)) }}</td>
    <td>{{ number_format($persentase,2) }}%</td>
</tr> --}}

