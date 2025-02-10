@extends('layouts.adminprint')
@section('content')

<div class="container-fluid">
<div class="row"> <!--Form Pencarian -->
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="now">Pilih</option>
                        <?php
                        $t = date(2024);
                        for ($i=$t; $i<=$t +5; $i++){
                        ?>
                        <option value={{ $i }} {{ ($tahun == $i) ? 'selected' :'' }}>{{ $i }}</option>
                        <?php
                        }
                        ?>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->            
            <div class="col-md-4">
            <form class="form-horizontal">
                <div class="form-group row">
                    <label for="group" class="col-sm-4 col-form-label">Periode</label>
                    <div class="col-sm-8">
                    <select class="form-control" name="group" id="group">                        
                        <option value="sm1" {{ ($kodegroup == 'sm1') ? 'selected':'' }}>Semester 1</option>
                        <option value="sm2" {{ ($kodegroup == 'sm2') ? 'selected':'' }}>Semester 2</option>
                        <option value="q1" {{ ($kodegroup == 'q1') ? 'selected':'' }}>Quartal 1</option>
                        <option value="q2" {{ ($kodegroup == 'q2') ? 'selected':'' }}>Quartal 2</option>
                        <option value="q3" {{ ($kodegroup == 'q3') ? 'selected':'' }}>Quartal 3</option>
                        <option value="q2" {{ ($kodegroup == 'q4') ? 'selected':'' }}>Quartal 4</option>
                    </select>
                    </div>
                </div>
            </form>
            </div> <!-- div col-4-->
    
            <div class="col-md-4">
                <span class="float-md-right">
                    <button type="submit" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
                </span>
            </div>
</div>
<div class="row">
  <div class="col-12">
    <!-- Main content -->
    <div class="invoice p-3 mb-3">
      <!-- title row -->
      <div class="row">
        <div class="col-12">
          <h4 style="text-align: center;">
             CASH FLOW PT. ADITYA MANDIRI SEJAHTERA PERIODE 2024
          </h4>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <br>
      
@if (isset($datanya))
      <!-- Table row -->
      <div class="row">
        <div class="col-12 table-responsive">
          <table class="display compact nowrap table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center;">Item</th>
                    <th colspan="2" style="text-align: center;">Januari</th>
                    <th colspan="2" style="text-align: center;">Februari</th>
                    <th colspan="2" style="text-align: center;">Maret</th>
                    <th colspan="2" style="text-align: center;">April</th>
                    <th colspan="2" style="text-align: center;">Mei</th></th>
                    <th colspan="2" style="text-align: center;">Juni</th>
                </tr>
                <tr>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                </tr>
                
            </thead>
            <tbody>
              <tr>
                    <td colspan="1" style="text-align: center;"><strong>PEMASUKAN</strong></td>
                    <td colspan="12"></td>
                </tr>
            @foreach($dataincome as $key => $datain)
                <tr>
                    <td> {{ $datain->label_report }}</td>
                    <td> {{ number_format($datain->budget_01,0) }}</td>
                    <td> {{ number_format($datain->realisasi_01,0) }}</td>
                    <td> {{ number_format($datain->budget_02,0) }}</td>
                    <td> {{ number_format($datain->realisasi_02,0) }}</td>
                    <td> {{ number_format($datain->budget_03,0) }}</td>
                    <td> {{ number_format($datain->realisasi_03,0) }}</td>
                    <td> {{ number_format($datain->budget_04,0) }}</td>
                    <td> {{ number_format($datain->realisasi_04,0) }}</td>
                    <td> {{ number_format($datain->budget_05,0) }}</td>
                    <td> {{ number_format($datain->realisasi_05,0) }}</td>
                    <td> {{ number_format($datain->budget_06,0) }}</td>
                    <td> {{ number_format($datain->realisasi_06,0) }}</td>
                </tr>
            @endforeach
            <tr><td colspan="13"></td></tr>
              <tr>
                    <td colspan="1" style="text-align: center;"><strong>PENGELUARAN</strong></td>
                    <td colspan="12"></td>
                </tr>
            @foreach($datanya as $key => $data)
                <tr>
                    <td> {{ $data->label_report }}</td>
                    <td> {{ number_format($data->budget_01,0) }}</td>
                    <td> {{ number_format($data->realisasi_01,0) }}</td>
                    <td> {{ number_format($data->budget_02,0) }}</td>
                    <td> {{ number_format($data->realisasi_02,0) }}</td>
                    <td> {{ number_format($data->budget_03,0) }}</td>
                    <td> {{ number_format($data->realisasi_03,0) }}</td>
                    <td> {{ number_format($data->budget_04,0) }}</td>
                    <td> {{ number_format($data->realisasi_04,0) }}</td>
                    <td> {{ number_format($data->budget_05,0) }}</td>
                    <td> {{ number_format($data->realisasi_05,0) }}</td>
                    <td> {{ number_format($data->budget_06,0) }}</td>
                    <td> {{ number_format($data->realisasi_06,0) }}</td>
                </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
@else
 <div class="row">
        <div class="col-12 table-responsive">
          <table class="display compact nowrap table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center;">Item</th>
                    <th colspan="2" style="text-align: center;">Januari</th>
                    <th colspan="2" style="text-align: center;">Februari</th>
                    <th colspan="2" style="text-align: center;">Maret</th>
                    <th colspan="2" style="text-align: center;">April</th>
                    <th colspan="2" style="text-align: center;">Mei</th></th>
                    <th colspan="2" style="text-align: center;">Juni</th>
                </tr>
                <tr>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                    <th>Budget</th><th>Realisasi</th>
                </tr>
                <tr>
                    <th colspan="1" style="text-align: center;">PENGELUARAN</th>
                    <th colspan="12"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
@endif
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-12">
          <button type="submit" id="btnPrint" class="btn btn-default float-right"> <i class="fas fa-print"></i>Print</button>
        </div>
      </div>
    </div> 
    <!-- /.invoice -->
  </div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container-fluid -->
  @endsection

  
@section('scripts')
@parent
<script>
$(document).ready( function () {

    $('#btnFiterSubmitSearch').click(function(){
        var spinner = $('#loader');
        //var periode = $("#periode option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportcf') }}"+ "/search/" + tahun + "/" + group + "";
    });
    $('#btnPrint').click(function(){
        var spinner = $('#loader');
        //var periode = $("#periode option:selected").val();
        var group = $("#group option:selected").val();
        var tahun = $("#tahun option:selected").val();
        spinner.show();
        window.location.href = "{{ url('budget/reportcf') }}"+ "/print/" + tahun + "/" + group + "";
    });
});
</script>
@endsection