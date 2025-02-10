
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AMS ERP - Report CashFlow</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4 -->

  <!-- Font Awesome -->
  <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet" />

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body>

<div class="wrapper">

  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
          <h4 style="text-align: center;">
             CASH FLOW PT. ADITYA MANDIRI SEJAHTERA PERIODE 2024
          </h4>
        </div>
        <!-- /.col -->
    </div>
  <br>
    <!-- Table row -->
    <div class="row">
    <div class="col-12 table-responsive">
    <table class="display compact nowrap table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
                <tr>
                    <th rowspan="2" style="text-align: center;">Item</th>
                    <th colspan="2" style="text-align: center;">Juli</th>
                    <th colspan="2" style="text-align: center;">Agustus</th>
                    <th colspan="2" style="text-align: center;">September</th>
                    <th colspan="2" style="text-align: center;">Oktober</th>
                    <th colspan="2" style="text-align: center;">November</th></th>
                    <th colspan="2" style="text-align: center;">Desember</th>
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
                    <td> {{ number_format($datain->budget_07,0) }}</td>
                    <td> {{ number_format($datain->realisasi_07,0) }}</td>
                    <td> {{ number_format($datain->budget_08,0) }}</td>
                    <td> {{ number_format($datain->realisasi_08,0) }}</td>
                    <td> {{ number_format($datain->budget_09,0) }}</td>
                    <td> {{ number_format($datain->realisasi_09,0) }}</td>
                    <td> {{ number_format($datain->budget_10,0) }}</td>
                    <td> {{ number_format($datain->realisasi_10,0) }}</td>
                    <td> {{ number_format($datain->budget_11,0) }}</td>
                    <td> {{ number_format($datain->realisasi_11,0) }}</td>
                    <td> {{ number_format($datain->budget_12,0) }}</td>
                    <td> {{ number_format($datain->realisasi_12,0) }}</td>
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
                    <td> {{ number_format($data->budget_07,0) }}</td>
                    <td> {{ number_format($data->realisasi_07,0) }}</td>
                    <td> {{ number_format($data->budget_08,0) }}</td>
                    <td> {{ number_format($data->realisasi_08,0) }}</td>
                    <td> {{ number_format($data->budget_09,0) }}</td>
                    <td> {{ number_format($data->realisasi_09,0) }}</td>
                    <td> {{ number_format($data->budget_10,0) }}</td>
                    <td> {{ number_format($data->realisasi_10,0) }}</td>
                    <td> {{ number_format($data->budget_11,0) }}</td>
                    <td> {{ number_format($data->realisasi_11,0) }}</td>
                    <td> {{ number_format($data->budget_12,0) }}</td>
                    <td> {{ number_format($data->realisasi_12,0) }}</td>
                </tr>
            @endforeach
            </tbody>
      </table>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  </section>

  <!-- /.content -->
</div>
<!-- ./wrapper -->
<script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>
</body>
</html>
