<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
       <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/package.app.js?auth=<?= time(); ?>"></script>

    </head>

    <body ng-app="PackageApp" ng-controller="package_view_ctrl">

        <?php $this->load->view('include/main_navbar'); ?>
        <!-- Page container -->
        <div class="page-container" >

            <!-- Page content -->
            <div class="page-content" ng-init="loadMore(1, 0)">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        <?php if ($this->session->flashdata('msg')): ?>
                            <?= '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?> 
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <?= '<div class="alert alert-danger">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php endif; ?>

                        <div class="loader logloder" ng-show="loadershow"></div>
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat"  >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>View List</strong> <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> </h1><hr></div>

                            <div class="panel-body" > <table class="table table-bordered table-hover" style="width: 100%;">
                                    <!-- width="170px;" height="200px;" -->
                                    <tbody >  <tr style="width: 80%;">
                                            <td> <div class="form-group" ><strong>Name:</strong>
                                                    <input type="text" class="form-control" placeholder="Name" ng-model="filterData.name"> </div></td>

                                            <td><button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?= lang('lang_Search'); ?></button></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered" id="downloadtable">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>No. Of Orders</th>
                                                <th>Validity Days</th>
                                                <th>Created Date</th>
                                                <th>Details</th>
<!--                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-if="listdata != 0" ng-repeat="data in listdata">
                                                <td>{{$index + 1}}</td>
                                                <td>{{data.name}}</td>
                                                <td> <span class="badge badge-success">{{data.price}}</span> </td>
                                                <td><span class="badge badge-warning">{{data.no_of_orders}}</span> </td>
                                                <td> <span class="badge badge-danger">{{data.validity_days}}</span> </td>
                                                 <td>{{data.created_at}}</td>
                                                <td>{{data.details}}</td>
                                            </tr>

                                        </tbody>
                                    </table>


                                </div>
                                <button ng-hide="listdata.length < 100 || listdata.length == totalCount || listdata == 0" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table --> 
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>


        <!-- /page container -->
      
        <script>


                            var tableToExcel = (function() {
                                    var uri = 'data:application/vnd.ms-excel;base64,'
                            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                                                        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                                                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
                                                return function(table, name) {
                                                if (!table.nodeType) table = document.getElementById(table)
                                                        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                                                var blob = new Blob([format(template, ctx)]);
                                                        var blobURL = window.URL.createObjectURL(blob);
                                                        return blobURL;
                                                }
                                                })()

                                                $("#btnExport").click(function () {
                                        var todaysDate = 'Package List ' + new Date();
                                                var blobURL = tableToExcel('downloadtable', 'Package');
                                                $(this).attr('download', todaysDate + '.xls')
                                                $(this).attr('href', blobURL);
                                        });
                                                function printPage()
                                                {
                                                var divToPrint = document.getElementById('printTable');
                                                        var htmlToPrint = '' +
                                                        '<style type="text/css">' +
                                                                                    'table {' +
                                                                                    'border:1px solid #000;' +                                                       '}' +                                                       'table th, table td {' +
                                                                'width:1200px' +                   '}' +   'table th, table td {' +      'padding:8px;' +
'}' +
    'table th {' +
    'padding-top: 12px;'+
    'padding-bottom: 12px;'+
    ' text-align: left;'+
       
'padding:0.5em;' +
'}' +
'</style>';
htmlToPrint += divToPrint.outerHTML;
newWin = window.open("");
newWin.document.write(htmlToPrint);
newWin.print();
newWin.close();
}
        </script>

    </body>
</html>