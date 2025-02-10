@extends('layouts.approvalprintcopy')
@section('content')
@php 
function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
    function adaKoma($nilai){
        $temp = (explode(".",$nilai));

        if ($temp[1] == "00" ){
            return "";
        }else{
            $hasil = trim(penyebut($temp[1]));
            return " koma ".$hasil;
        }
    }

	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     

        $koma = trim(adaKoma($nilai));		
		return $hasil." ".$koma;
	}
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cetak Document {{ $data->no_document}}</h1>
            </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Approval</a></li>
            <li class="breadcrumb-item"><a href="#">Document</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $data->no_document}}</a></li>
            <li class="breadcrumb-item active">Print</li>
        </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
                @if($data->last_status == "closed")
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-success text-lg">
                        PAID
                    </div>
                </div>
                @endif
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h5>
                    Document {{ $data->no_document}} - 
                    <small>[Tanggal Sistem : {{ $tgl }}] @if($data->created_by != $data->updated_by) 
                    Updated by {{ $data->nama_update }} @endif</small>
                  </h5>
                  <span class="label label-info">{{ $data->keterangan }}</span>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->

              <div class="row">
              @foreach($data_digital as $key => $digital)
                <div class="col-md-6">
                <div class="table-responsive">
                    @if( $digital->kode_category == 'KB')
                    <table class="table-bordered" style="width:100%">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <th colspan="4">{{ $digital->nama_category }}</th>
                        </tr>
                        <tr>
                            <td colspan="2">{{ $digital->mata_uang }} {{ $digital->jumlah }}</td>
                            <td colspan="2" rowspan="2">Terbilang : {{ terbilang($digital->jum) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="2">Keperluan</th>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" rowspan="2">{{ $data->keterangan }}</td>
                        </tr>
                        <tr>
                        </tr>
                    </table>
                    @else
                    <table class="table-bordered" style="width:100%">
                        <tr>
                            <th colspan="3">{{ $digital->nama_category }}&nbsp;
                            @if($digital->pu == "checked")
                                <input type="checkbox" {{ $digital->pu}} onclick="return false;" /></th>
                            @endif
                            
                            @if( $digital->kode_category == 'SPD')
                                <td colspan="3">{{ $digital->no_ref }} {{ $digital->no_digital }}</td>
                            @else
                                <td colspan="3">{{ $digital->no_ref }}</td>
                            @endif
                        </tr>
                        
                        <tr>
                            <th rowspan="2" colspan="2">Direksi</th>
                            <th style="text-align: center">Tgl</th>
                            <th style="text-align: center">Tgl Bayar</th>
                            <th colspan="2" style="text-align: center">Tgl Realisasi</th>
                        </tr>
                        <tr>
                            <td style="text-align: center">{{ $digital->tanggal }}</td>
                            <td style="text-align: center">{{ $digital->tanggal_bayar }}</td>
                            @if($bayar == "-")
                            <td colspan="2" style="text-align: center">-</td>
                            @else
                            <td colspan="2" style="text-align: center">{{ $bayar->tgl_realisasi }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th colspan="2">Dikeluarkan kepada</th>
                            <td colspan="4">{{ $digital->nama_tujuan }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Jumlah</th>
                            <td colspan="4">{{ $digital->mata_uang }} {{ $digital->jumlah }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Terbilang</th>
                            <td colspan="4">{{ terbilang($digital->jum) }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">No Rekening</th>
                            <td colspan="4" nowrap>
                            {{ $digital->bank }} - {{ $digital->rek_tujuan }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2"></th>
                            <td colspan="4">( {{ $digital->nama_rek }} )</td>
                        </tr>
                        <tr>
                            <th colspan="2">Untuk Keperluan</th>
                            <td colspan="4">{{ $data->keterangan }}</td>
                        </tr>
                        </table>
                        @endif
                    </div> <!-- table responsive -->
                </div> <!-- col -->
               
              @endforeach
              <hr>
            </div> <!-- row -->
            </div> <!-- div invoice -->
            <div class="row">
                <div class="col-12">
                <div class="card">
                <div class="table-responsive">
                    <table class="table-bordered" style="width:100%">
                        <tr>
                        @foreach($data_ttd as $key => $ttd)
                            <td align="center"><b>{{ $ttd->departemen ?? '' }}</b></td>
                        @endforeach
                        </tr>
                        <tr>
                        @foreach($data_ttd as $key => $ttd)
                            <td align="center"><img src="{{ $ttd->signature}}" height="75" width="75"></td>
                        @endforeach
                        </tr>
                        @foreach($data_ttd as $key => $ttd)
                            <td align="center">{{ $ttd->tgl ?? '' }}</td>
                        @endforeach
                        </tr>
                        <tr>
                        @foreach($data_ttd as $key => $ttd)
                            <td align="center"> {{ $ttd->nama ?? '' }}</td>
                        @endforeach
                        </tr>
                    </table>
                </div>
                </div> <!-- card -->
                </div> <!-- col -->
              </div> <!-- row -->

    <!-- this row will not appear when printing -->
    <hr>
    <div class="row no-print">
        <div class="col-12">
            <button type="button" class="btn btn-info float-left" onclick="backPage()">Kembali</button>&nbsp;
            <button type="button" class="btn btn-info" onclick="lampiran()">Lampiran</button>
            <button type="button" class="btn btn-primary float-right" onclick="printPage()" style="margin-right: 5px;">
                <i class="fas fa-download"></i> Print/Save as PDF
            </button>
        </div>
    </div>

<!-- START MODAL FORM -->
<div id="formmodal" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title">List Lampiran</h5>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<span id="form"></span>
            <div class="table-responsive">
                @if($prioritas > 2)
                    @can('approval_file_special_access')
                    <table class="table-bordered" style="width:100%">
                        <tr>
                            <th>Kategory</th>
                            <th>Nama File</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                        <tr>
                        @foreach($data_file as $key => $file)
                        <tr>
                            <td>{{ $file->category_name ?? '' }}</td>
                            <td>{{ $file->nama_file ?? '' }}</td>
                            <td>{{ $file->keterangan ?? '' }}</td>
                            <td><a href="{{ asset('/file/'.$file->nama_file ?? '') }}" class="btn btn-sm btn-primary">Download</a></td>
                        </tr>
                        @endforeach
                    </table>
                    @endcan
                @else
                    <table class="table-bordered" style="width:100%">
                        <tr>
                            <th>Kategory</th>
                            <th>Nama File</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                        <tr>
                        @foreach($data_file as $key => $file)
                        <tr>
                            <td>{{ $file->category_name ?? '' }}</td>
                            <td>{{ $file->nama_file ?? '' }}</td>
                            <td>{{ $file->keterangan ?? '' }}</td>
                            <td><a href="{{ asset('/file/'.$file->nama_file ?? '') }}" class="btn btn-sm btn-primary">Download</a></td>
                        </tr>
                        @endforeach
                    </table>
                @endif
            </div>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL FORM -->
@endsection
@section('scripts')
@parent
<script>
    function lampiran(){
        $('#formmodal').modal('show');
    }

    function backPage(){
        window.history.back();
    }

    function printPage(){
        window.print();
    }
</script>
@endsection