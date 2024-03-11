<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/reports.app.js?v<?= time(); ?>"></script>
    </head>

    <body ng-app="AppReports" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CTR_StorageinvoiceView" ng-init="getallseller();"> 

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
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1> <strong>Storage Report</strong> 
                                            <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;

                                            <a onclick="printPage();" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                        
                                        <!-- Quick stats boxes -->
                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <!-- Today's revenue --> 

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 80%;">
                                                            <td><div class="form-group" ><strong><?= lang('lang_Sellers'); ?>:</strong>
                                                                    <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control">
                                                                        <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                                        <option ng-repeat="sdata in sellerdata"  value="{{sdata.id}}">{{sdata.name}}</option>
                                                                    </select>
                                                                </div></td>
                                                            <td><div class="form-group" ><strong><?= lang('lang_Year'); ?>:</strong>
                                                                    <select id="years" name="years" ng-model="filterData.years" class="form-control">
                                                                        <option value=""><?= lang('lang_select'); ?> <?= lang('lang_Year'); ?></option>
                                                                        <?php
                                                                        // Sets the top option to be the current year. (IE. the option that is chosen by default).
                                                                        $currently_selected = date('Y');
                                                                        // Year to start available options at
                                                                        $earliest_year = 2019;
                                                                        // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                                                                        $latest_year = date('Y');

                                                                        // Loops over each int[year] from current year, back to the $earliest_year [1950]
                                                                        foreach (range($latest_year, $earliest_year) as $i) {
                                                                            // Prints the option with the next year in range.
                                                                            print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div></td>
                                                            <td><div class="form-group" ><strong><?= lang('lang_Months'); ?>:</strong>
                                                                    <select id="monthid"name="monthid" ng-model="filterData.monthid" class="form-control">
                                                                        <option value=""><?= lang('lang_Select_Month'); ?></option>
                                                                        <option ng-repeat="num in [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]"  value="{{$index + 1}}">{{$index| month}}</option>
                                                                    </select>
                                                                </div></td>

                                                            <td><button  class="btn btn-danger" ng-click="loadMore();" ><?= lang('lang_Get_Details'); ?></button></td>

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
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>

                                                <th><?= lang('lang_Date'); ?></th>

                                                <th>Warehouse Name</th>
                                                <th>Client Name</th>
                                                <?php
                                                $storage_arr = Getallstorage_drop_default();

                                                foreach ($storage_arr as $val) 
                                                {
                                                  echo ' <th>'.$val['storage_type'].'</th>'; 
                                                }
                                                ?>

                                            </tr>

                                            
                                        </thead>
                                       
                                        <tr ng-repeat="data in showlistData">
                                            <td>{{data.date}} </td>
                                             <td>{{data.company}}</td>
                                            <td>{{data.seller_name}}</td>
                                            <td ng-repeat="data2 in storageArr">{{data['pallet_total_'+data2.id]}}</td>

                                           



                                        </tr>
                                    </table>
                                  
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


        </div>


        <script>
            function printPage()
            {
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
            var todaysDate = 'Storage Reports '+ new Date();
            var blobURL = tableToExcel('printTable', 'Reports');
            $(this).attr('download',todaysDate+'.xls')
            $(this).attr('href',blobURL);
        });



        </script>
        <!-- /page container -->

    </body>
</html>
