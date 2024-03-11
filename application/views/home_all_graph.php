
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
                                        <h6 class="panel-title"><?=lang('lang_Dashboard');?></h6>
                                        <div class="heading-elements"> <span class="label bg-success heading-text"><?=lang('lang_DETAILS');?></span> 

                                        </div>
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

                                    <!-- Quick stats boxes  style="background-color: #263238"-->

                                  
                                  



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

                                    <?php if (menuIdExitsInPrivilageArray(95) == 'Y') { ?>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <!--	<div id="container5"></div>-->
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <div id="container3"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (menuIdExitsInPrivilageArray(96) == 'Y') { ?>    
                                        <div class="panel-heading">
                                            <h6 class="panel-title"><?=lang('lang_TodayDashboard');?></h6>
                                            <div class="heading-elements">

                                            </div>
                                        </div>
                                        <?php
                                        //print_r(json_encode($totalorderschart));die;
                                        ?>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <div id="container"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (menuIdExitsInPrivilageArray(97) == 'Y') { ?>   
                                    
                                    <?php
                                      // lowest year wanted
                                                                $cutoff = 2020;

                                                                // current year
                                                                $now = date('Y');

                                                                // build years menu
                                                                $yearDrop .= '<form action="'.base_url().'Home"  method="post" style="float:right;"><select name="year" class=""  onchange="this.form.submit()"><option value="">Year</option>' . PHP_EOL;
                                                                for ($y = $now; $y >= $cutoff; $y--) {
                                                                    if(set_value('year')==$y)
                                                                    $yearDrop .= '  <option value="' . $y . '" seleted>' . $y . '</option>' . PHP_EOL;
                                                                    else
                                                                     $yearDrop .= '  <option value="' . $y . '">' . $y . '</option>' . PHP_EOL;
                                                                        
                                                                }
                                                                $yearDrop .= '</select></form>' . PHP_EOL;
                                    
                                    ?>
                                    <div class="panel-heading" style="width:90%;">
                                            <h6 class="panel-title"><?=lang('lang_MonthlyShipmentDetails');?>   <?=$yearDrop;?></h6>
                                            <div class="heading-elements">

                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <div id="container2"></div>

                                                    </div>
                                                </div>





                                            </div>
                                        </div>
                                    <?php } ?>

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
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: "<?=lang('lang_TodayDashboard');?>"
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

            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
            },

            series: [
                {
                    name: "Shipment",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Order Created",
                            y: <?= get_total_current(1); ?>,
                            drilldown: "Order Created"
                        },
                        {
                            name: "Picklist Generated",
                            y: <?= get_total_current(2); ?>,
                            drilldown: "Picklist Generated"
                        },
                        {
                            name: "Assigning To Picker",
                            y: <?= get_total_current(3); ?>,
                            drilldown: "Assigning To Picker"
                        },
                        {
                            name: "Packed",
                            y: <?= get_total_current(4); ?>,
                            drilldown: "Packed"
                        },
                        {
                            name: "Dispatched to LM ",
                            y: <?= get_total_current(5); ?>,
                            drilldown: "Dispatched to LM "
                        },
                        {
                            name: "Delivered",
                            color: 'green',
                            y: <?= get_total_current(7); ?>,
                            drilldown: "Delivered"
                        },
                        {
                            name: "Return",
                            y: <?= get_total_current(8); ?>,
                            drilldown: 'Return',
                            color: '#AD1457'
                        }
                    ]
                }
            ],

        });
        $(".highcharts-credits").empty();

        Highcharts.chart('container2', {
            chart: {
                type: 'column'
            },
            title: {
                text: "<?=lang('lang_MonthlyShipmentDetails');?>"
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
                    data: <?= json_encode($totalorderschart, JSON_NUMERIC_CHECK) ?>
                    //JSON_NUMERIC_CHECK

                }
            ],

        });
        $(".highcharts-credits").empty();


        /*Highcharts.setOptions({
         colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
         return {
         radialGradient: {
         cx: 0.5,
         cy: 0.3,
         r: 0.7
         },
         stops: [
         [0, color],
         [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
         ]
         };
         })
         });
         */
        // Build the chart
        Highcharts.chart('container3', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: "<?=lang('lang_Shipments_Totals');?> "
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}',
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                    name: '',
                    data: [
                        {name: 'Total Shipments', y:<?= $Total_Shipments ?>, color: '#26A69A'},
                        {name: 'Total RTF', y: <?= $Total_Rts; ?>, color: '#00695C'},
                        {name: 'Total Items in Inventory', y:<?php if ($Item_Inventory != 0): ?><?= $Item_Inventory ?><?php else: ?>0<?php endif; ?>, color: '#29B6F6'},
                                            {name: 'Total Items', y: <?= $Total_Items; ?>, color: '#039BE5'},
                                            {name: 'Total Sellers', y: <?= $Total_Sellers; ?>, color: '#0277BD'}

                                        ]
                                    }]
                            });
                            $(".highcharts-credits").empty();


    </script>
</html>
<!-- footer limited  check it-->