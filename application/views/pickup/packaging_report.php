
<?php
/* $number = cal_days_in_month(CAL_GREGORIAN, 10, date('Y')); // 31
  echo "There were {$number} days in August 2003";
  die; */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/pickup.app.js?v=<?=time();?>"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 

    </head>

    <body ng-app="appPickup" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="pickListView" ng-init="getallseller();">

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" >
                        <!--style="background-color: red;"-->
                         <div class="loader logloder" ng-show="loadershow"></div>
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" dir="ltr">
                                    <div class="panel-heading">
                                     <div class="panel-heading">
                                        <h1> <strong><?= lang('lang_Packaging_Report'); ?> </strong> 
                                            <!-- <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp; -->
                                            <a  ng-click="getPackagingExcelDetails();"><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  
                                            </select> 
                                            <a onclick="printPage();" style="display:none"><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 
                                        </h1>
                                    </div>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments'); ?>" --> 
                                    <!-- href="<? //base_url('Pdf_export/all_report_view'); ?>" --> 
                                        <!-- Quick stats boxes -->
                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <!-- Today's revenue --> 

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 80%;">
                                                            <td><div class="form-group" ><strong><?= lang('lang_AWB_No'); ?>:</strong>
                                                                    <input type="text" class="form-control" name="slip_no" ng-model="filterData.slip_no">
                                                                </div></td>
                                                            <td><div class="form-group" ><strong><?= lang('lang_From_Date'); ?>:</strong>
                                                                    <input class="form-control date" name="from_date" ng-model="filterData.from_date" placeholder="YYY-MM-DD">
                                                                </div></td>

                                                            <td><div class="form-group" ><strong><?= lang('lang_To_Date'); ?>:</strong>
                                                                    <input class="form-control date" name="to_date" ng-model="filterData.to_date" placeholder="YYY-MM-DD">
                                                                </div></td>

                                                            <td><button  class="btn btn-danger" ng-click="loadMore1(1, 1);" ><?= lang('lang_Get_Details'); ?></button></td>          
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div id="today-revenue"></div>
                                                <!-- </div> panel-body--> 

                                                <!-- /today's revenue --> 

                                            </div>
                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;"  > 
                                    <!--style="background-color: green;"-->

                                    <table ng-show="tableshow" class="table table-striped table-hover table-bordered display nowrap" id="printTable"  style="width:100%;">

                                        <thead>
                                            <tr>

                                                <th><?= lang('lang_AWB'); ?>#</th>
                                                <th>SKU </th>
                                                <th><?= lang('lang_Quantity'); ?> <?= lang('lang_Required'); ?></th>
                                                <th><?= lang('lang_Quantity'); ?> <?= lang('lang_Scaned'); ?></th>
                                                <th><?= lang('lang_Quantity'); ?> Extra</th>

                                                <th><?= lang('lang_UpdatedBy'); ?></th>
                                                <th><?= lang('lang_Entry_Date'); ?></th>
                                               
                                                <!-- <th class="text-center" ><i class="icon-database-edit2"></i></th>-->

                                            </tr>

                                        </thead>

                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">

                                            <td width="150">{{data.slip_no}}</td>
                                            <td>{{data.sku}} </td>     
                                            <td>{{data.quantity}} </td> 
                                            <td>{{data.qty_scan}}</td>
                                            <td>{{data.qty_extra}} </td> 

                                            <td>{{data.updated_by}}</td>
                                            <td>{{data.entrydate}}</td>   
                                             


                                        </tr>

                                    </table>

                                     <button ng-show="tableshow" ng-hide="shipData.length==totalCount" class="btn btn-info" ng-click="loadMore1(count=count+1,0);" ng-init="count=1">Load More</button>
                                </div>

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

<!-- <script>
var $rows = $('tbody tr');
$('#search').keyup(function() {
var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

$rows.show().filter(function() {
var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
return !~text.indexOf(val);
}).hide();
});
</script> -->

        </div>

        <!-- /page container -->
        <script>

            function printPage()
                    {
                    document.getElementById('disdisplay').style.display = "none";
                    var divToPrint = document.getElementById('printTable');
                    var htmlToPrint = '' +
                            '<style type="text/css">' +
                            'table th, table td {' +
                            'border:1px solid #000;' +
                            'width:1200px' +
                            '}' +
                            'table th, table td {' +
                            'border:1px solid #000;' +
                            'padding:8px;' +
                            '}' +
                            'table th {' +
                            'padding-top: 12px;' +
                            'padding-bottom: 12px;' +
                            ' text-align: left;' +
                            'border:1px solid #000;' +
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
            var todaysDate = 'Packaging Report '+ new Date();
            var blobURL = tableToExcel('printTable', 'invoice');
            $(this).attr('download',todaysDate+'.xls')
            $(this).attr('href',blobURL);
        });




        </script>
        <script type="text/javascript">

                                       $('.date').datepicker({

                                       format: 'yyyy-mm-dd'

                                       });

        </script>
    </body>
</html>
