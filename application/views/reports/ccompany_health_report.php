
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/dgpk.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
        <style>
            
           .newbgCL{ background: #f5f5f5;}
        </style>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading"dir="ltr">
                                        <h6 class="panel-title">3PL Company Report</h6>
                                    </div>
                                    

                                    <div class="table-responsive">
                                        <table class="table table-lg text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <td class="col-md-5"><div class="media-left">
                                                            <div id="campaigns-donut"></div>
                                                        </div>
                                                        <div class="media-left"> 

                                                        </div></td>
                                                    <td class="col-md-5"><div class="media-left">
                                                            <div id="campaign-status-pie"></div>
                                                        </div>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 

                        <!-- Main charts -->
                        <div class="row">
                            <div class="col-lg-12"> 
                                <!-- Traffic sources --> 

                                <div class="panel panel-flat">

                                <div class="panel-heading" style="text-align:center;"> 
                                        <h2 class="panel-title"><strong>3PL Courier Company Details </strong></h2>
                                    </div>                                                    
                                    
                                    <!-- Manish Today shipment according to the forwarded shipment end  -->
                                        <?php if (menuIdExitsInPrivilageArray(97) == 'Y') { ?>   
                                            
                                                
                                               
                                            
                                                <div class="panel-heading" style="width:90%;"> 
                                                    <h4 class="panel-title"><strong>Today's Shipment </strong></h4>
                                                    <div class="heading-elements">

                                                    </div>

                                                </div>

                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                        <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-lg-12">  
                                                            <div class="panel-heading" style="width:90%;"> 
                                                            <form method="post" action="<?= base_url(); ?>courierHealthReport">
                                                                <div class="col-md-3"> 
                                                                    <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                                        <input class="form-control date" placeholder="From" id="from_date" name="from_date" value="<?=$from_date;?>"   autocomplete="off">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                                            <input class="form-control date" placeholder="To" id="to_date"name="to_date" value="<?=$to_date;?>"  autocomplete="off" > 
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3"> 
                                                                    <div class="form-group" ><strong>Per Day:</strong>
                                                                        <input class="form-control date" placeholder="Per Day" id="from_date" name="single_date" value="<?=$single_date;?>"   autocomplete="off">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group" ><strong></strong><br/><button type="submit"  class="btn-sm btn-danger"- ><?= lang('lang_Get_Details'); ?></button>
                                                                        <button type="submit" value="1" name="clfilter"  class="btn-sm btn-danger"- ><?=lang('lang_Clear_Filter');?></button>
                                                                    </div>
                                                                </div>
                                                            </form>           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                            <div class="col-lg-10 col-lg-offset-1">
                                                                <div id="todaysshipment"></div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <hr />

                                <!-- monthely shipment Start -->
                                <?php if (menuIdExitsInPrivilageArray(97) == 'Y') { ?>   
                                                                    
                                    <?php
                                       
                                        $now = date('m');

                                        // build years menu
                                        $monthDrop .= '<form action="'.base_url().'courierHealthReport"  method="post" style="float:right;"><select name="month" class=""  onchange="this.form.submit()"><option value="">Month</option>' . PHP_EOL;
                                        for ($y = 1; $y <= $now; $y++) {
                                            $month = date('m', mktime(0,0,0,$y, 1, date('Y')));
                                            $month_name = date("F", mktime(0, 0, 0, $month, 10));
                                            $selected = '';
                                            if($selected_month == $month){
                                                $selected = "selected='selected'";
                                            }
                                            $monthDrop .= '  <option value="' . $month . '"  '.$selected.' >' . $month_name . '</option>' . PHP_EOL;
                                                
                                        }
                                        $monthDrop .= '</select></form>' . PHP_EOL;
                                        
                                        ?>
                                        <div class="panel-heading" style="width:90%;">
                                                <h4 class="panel-title"><strong>Monthly Shipment <?=$monthDrop;?></strong></h4>
                                                <div class="heading-elements">

                                                </div>
                                            </div>

                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-lg-12">


                                                        <div class="col-lg-10 col-lg-offset-1">
                                                            <div id="monthelyshipment"></div>

                                                        </div>
                                                    </div>





                                                </div>
                                            </div>
                                        <?php } ?>


                            <!-- monthely  shipmentend -->
                            <!-- Manish Today shipment according to the forwarded shipment end  -->

                                    <div class="position-relative" id="traffic-sources"></div>
                                </div>  
                                <!-- /traffic sources --> 

                            </div>
                            <div class="col-lg-5"> 


                                <!-- /sales stats --> 
                            </div>
                        </div>
                        <!-- /main charts -->

                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 

                </div>
                <!-- /main content --> 

            </div>
            <!-- /page content --> 

        </div>

        <!-- /page container -->
    </body>

    <script src="<?php echo base_url(); ?>assets/js/stock/highcharts-more.js"></script> 
    <script src="https://code.highcharts.com/stock/highstock.js"></script>


    <script>
        runtimechart('rendom');
        function runtimechart(start)
        {
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Shipment/getlivechartallshipment",
                dataType: "json",
                data: {id: 1},
                success: function (data) {
                    console.log(data);
                    var products = data;
                    var dataseries = [];
                    var dataitems = [];
                    for (var key in products['All']) {

                        ///alert(products['All'][key][0]);

                        //alert(products['All'][key]['yy']+","+products['All'][key]['mm']+","+products['All'][key]['dd']+","+products['All'][key]['hh']+","+products['All'][key]['ii']+","+products['All'][key]['ss']);
                        dataitems.push({"x": Date.UTC(products['All'][key]['yy'], products['All'][key]['mm'], products['All'][key]['dd'], products['All'][key]['hh'], products['All'][key]['ii'], products['All'][key]['ss']), "y": products['All'][key][1], "color": products['All'][key]['color']});
                    }
                    dataseries.push({name: 'Performance', type: 'column', data: dataitems, threshold: null, visible: true, step: false, dataGrouping: {enabled: false}, shadow: false, tooltip: {pointFormat: "performance: {point.y:,.2f} s"}, });
                    Highcharts.chart({
                        chart: {backgroundColor: null, plotBackgroundColor: "rgba(255, 255, 255, 1)", plotBorderWidth: 1, plotBorderColor: "#d2d2d2", renderTo: 'container5'},
                        yAxis: {
                            labels: {
                                formatter: function () {
                                    return this.value + "s";
                                },
                                style: {color: "#000", textShadow: "0 1px 2px #EEEEEE", }
                            }, min: -0
                        },

                        navigator: {enabled: true, height: 60, adaptToUpdatedData: false},
                        scrollbar: {liveRedraw: false},
                        legend: {layout: 'vertical', align: 'right', verticalAlign: 'middle', borderWidth: 0, enabled: false},
                        rangeSelector: {
                            selected: 2, enabled: true,
                            buttons: [
                                {type: 'hour', count: 1, text: '1h'},
                                {type: 'hour', count: 12, text: '12h'},
                                {type: 'day', count: 1, text: '1d'},
                                {type: 'week', count: 1, text: '1w'},
                                {type: 'month', count: 1, text: '1m'},
                                {type: 'all', text: 'All'}
                            ]
                        },

                        xAxis: {events: {}, minRange: 3600},
                        credits: {enabled: false},
                        title: {text: ''},
                        plotOptions: {series: {turboThreshold: 500000}},
                        series: dataseries

                    });


                }
            });
        }
        // Create the chart
        $(".highcharts-credits").empty();
        // manish Chart Start

        Highcharts.chart('monthelyshipment', {
            chart: {
                type: 'column'
            },
            title: {
                text: ""
            },
            /*  subtitle: {
             text: ''
             },*/
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: '<?=lang('lang_Total_Shipments');?>Monthly'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                },
                column: {pointPadding: 0.2, borderWidth: 0}

            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>',
                shared: true,
                useHTML: true
            },
            labels: {
                items: [{

                        style: {
                            left: '50px',
                            top: '18px',
                            color: (// theme
                                    Highcharts.defaultOptions.title.style &&
                                    Highcharts.defaultOptions.title.style.color
                                    ) || 'black'
                        }
                    }]
            },
            series: [
                {
                    name: "Fulfillment",
                    colorByPoint: true,
                    // data: [{name: "Order Created",y: <?= get_total_current(1); ?>,drilldown: "Order Created"},]
                    data: <?= json_encode($monthlyshipment, JSON_NUMERIC_CHECK) ?>
                    //JSON_NUMERIC_CHECK

                }
            ],

        });
        $(".highcharts-credits").empty();
        //todays shipment Start
        Highcharts.chart('todaysshipment', {
            chart: {
                type: 'column'
            },
            title: {
                text: ""
            },
            /*  subtitle: {
             text: ''
             },*/
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: '<?=lang('lang_Total_Shipments');?>'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                },
                column: {pointPadding: 0.2, borderWidth: 0}

            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>',
                shared: true,
                useHTML: true
            },
            labels: {
                items: [{

                        style: {
                            left: '50px',
                            top: '18px',
                            color: (// theme
                                    Highcharts.defaultOptions.title.style &&
                                    Highcharts.defaultOptions.title.style.color
                                    ) || 'black'
                        }
                    }]
            },
            series: [
                {
                    name: "Fulfillment",
                    colorByPoint: true,
                    // data: [{name: "Order Created",y: <?= get_total_current(1); ?>,drilldown: "Order Created"},]
                    data: <?= json_encode($todaysshipment, JSON_NUMERIC_CHECK) ?>
                    //JSON_NUMERIC_CHECK

                }
            ],

        });
        $(".highcharts-credits").empty();
        //todays shipment end
        var currentDate = new Date();
        // Manish Creation Chart End
        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose:true,
            endDate: "currentDate",
            maxDate: currentDate
        });

    </script>
</html>
<!-- footer limited  check it-->