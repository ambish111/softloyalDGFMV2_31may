<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>


    <body ng-app="Appiteminventory">
        <?php $this->load->view('include/main_navbar');
        ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CtritemInvontaryview" >  

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

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading" dir="ltr">
                                        <h1><strong><?= lang('lang_Update'); ?> <?= lang('lang_Expiry_Date'); ?></strong></h1>

                                    </div>

                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 


                                            <form action="<?=base_url();?>ItemInventory/GetUpdateInventoryExpire/<?=$table_id;?>" method="post">
                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >

                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong><?= lang('lang_Expiry_Date'); ?>:</strong>
                                                                <input type="text" id="expity_date" name="expity_date"  class="form-control date" placeholder="<?= lang('lang_Expiry_Date'); ?>" required="required" value="<?=$edit_result->expity_date;?>">
                                                            </div></td>

                                                    </tr>




                                                    <tr>
                                                        <td > <button type="submit"  class="btn btn-info"  ><?= lang('lang_Update'); ?></button></td>
                                                    </tr> 
                                                </tbody>
                                            </table>
                                                </form>
                                          



                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <?php $this->load->view('include/footer'); ?>
                    </div>


                </div>


            </div>




        </div>


        <!-- /page container --> 
        <script>

        </script>
        <script type="text/javascript">

            $('.date').datepicker({

                format: 'dd-mm-yyyy',
                startDate: new Date()

            });


        </script>



    </body>
</html>
