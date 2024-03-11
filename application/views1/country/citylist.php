<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Update City List</title>
        <?php $this->load->view('include/file'); ?>
        <style type="text/css">
            .wrapper1, .wrapper2 { width: 100%; overflow-x: scroll; overflow-y: hidden; }
            .wrapper1 { height: 20px; }
            .wrapper2 {}
            .div1 { height: 20px; }
            .div2 { overflow: none; }

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
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 
                        <!--style="background-color: red;"-->
                        <?php
                        if ($this->session->flashdata('succmsg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('succmsg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        if ($this->session->flashdata('errormess'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('errormess') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_City'); ?> <?= lang('lang_List'); ?></strong> </h1>
                            </div>
<!--                            <form id="search_form" name="search_form" method="post" action="<?php //echo base_url('Country/cityList');   ?>">
                                <input type="hidden" id="search_params" name="search_params" value="<?php //echo $search_val;   ?>" />
                            </form>-->
                            <div class="wrapper1">
                                <div class="div1">
                                </div>
                            </div>
                            <div class="wrapper2">
                            <div class="div2">
                                <div class="loader logloder" id="loadershow" style="display:none;"></div>
                                <table class="table datatable-show-all table-bordered table-hover datatable-highlight" id="city_list_paging">
                                    <thead>
                                        <tr> 

                                            <th class="head1" colspan="6">
                                                <input type="text" id="search_val" name="search_val" value="<?php echo isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : ''; ?>" placeholder="<?= lang('lang_searching_by_city_name'); ?>" class="form-control" onfocus="this.value = ''" />
                                            </th>

                                            <th class="head1" colspan="6">
                                                <input type="button" name="Search" class="btn btn-info" value="<?= lang('lang_Search'); ?>" onclick="return Valid_search();" /> 
                                                <input type="button" name="clear_filter" class="btn btn-success" value="<?= lang('lang_Clear_Filter'); ?>" onclick="clearFilter(); return;" /> 
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="head0"><?= lang('lang_Sr_No'); ?></th>
                                            <th class="head1"><?= lang('lang_City'); ?></th>
                                            <th class="head0"> Arabic <?= lang('lang_City'); ?> </th>
                                            <?php if (GetCourierCompanyStausActive('SMSA') == 'Y') { ?>
                                                <th class="head1">SMSA </th>
                                            <?php } ?>
                                          
                                            <?php if (GetCourierCompanyStausActive('UBREEM') == 'Y') { ?>
                                                <th class="head1">UBREEM</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('ARAMEX') == 'Y' || GetCourierCompanyStausActive('Aramex International') == 'Y') { ?>
                                                <th class="head1">ARAMEX </th>
                                                <th class="head1">ARAMEX Country Code</th>
                                            <?php } ?>
                                          
                                            <?php if (GetCourierCompanyStausActive('DOTS') == 'Y') { ?>
                                                <th class="head1">DOTS </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('IMILE') == 'Y') { ?>
                                                <th class="head1">IMILE </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('NAQEL') == 'Y') { ?>
                                                <th class="head1">NAQEL </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Esnad') == 'Y') { ?>
                                                <th class="head1">ESNAD</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Esnad') == 'Y') { ?>
                                                <th class="head1">ESNAD CITY CODE</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SAMANA') == 'Y') { ?>
                                                <th class="head1">SAMANA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AGILITY') == 'Y') { ?>
                                                <th class="head1">AGILITY</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('DESCEN') == 'Y') { ?>
                                                <th class="head1">DESCEN</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AYMAKAN') == 'Y') { ?>
                                                <th class="head1">AYMAKAN</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('ZAJIL') == 'Y') { ?>
                                                <th class="head1">ZAJIL</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('CLEX') == 'Y') { ?>
                                                <th class="head1">CLEX</th>
                                                <?php if (GetCourierCompanyStausActive('RABEL') == 'Y') { ?>
                                                    <th class="head1">RABEL</th>
                                                <?php } ?>
                                                <?php if (GetCourierCompanyStausActive('SPEEDZI') == 'Y') { ?>
                                                    <th class="head1">SPEEDZI</th>
                                                <?php } ?>
                                                <?php if (GetCourierCompanyStausActive('Barqfleet') == 'Y') { ?>
                                                    <th class="head1">BARQ</th>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Labaih') == 'Y') { ?>
                                                <th class="head1">LABAIH</th>
                                            <?php } ?>

                                            <?php if (GetCourierCompanyStausActive('MAKHDOOM') == 'Y') { ?>
                                                <th class="head1">MAKHDOOM </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('MAKHDOOM V2') == 'Y') { ?>
                                                <th class="head1">MAKHDOOM City Code</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Saee') == 'Y') { ?>
                                                <th class="head1">SAEE</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AJEEK') == 'Y') { ?>
                                                <th class="head1">AJEEK</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('EMDAD') == 'Y') { ?>
                                                <th class="head1">EMDAD </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SHIPSY') == 'Y') { ?>
                                                <th class="head1">SHIPSY</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Shipadelivery') == 'Y') { ?>
                                                <th class="head1">SHIPA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('TAMEX') == 'Y') { ?>
                                                <th class="head1">TAMEX</th>
                                            <?php } ?>
                                            <?php //if (GetCourierCompanyStausActive('ZID CITY') == 'Y') { ?>
                                                <!-- <th class="head1">ZID CITY</th> -->
                                            <?php //} ?>
                                            <?php // if (GetCourierCompanyStausActive('SALLA CITY') == 'Y') { ?>
                                                <!-- <th class="head1">SALLA CITY</th> -->
                                            <?php //} ?>
                                            <?php if (GetCourierCompanyStausActive('Alamalkon') == 'Y') { ?>
                                                <th class="head1">Alamalkon </th>
                                            <?php } ?>
                                                   <?php if (GetCourierCompanyStausActive('Saudi Post') == 'Y') { ?>
                                                <th class="head1">Saudi Post Id </th>
                                            <?php } ?>
                                                 <?php if (GetCourierCompanyStausActive('Beez') == 'Y') { ?>
                                                <th class="head1">Beez </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('FedEX') == 'Y') { ?>
                                                <th class="head1">FedEX </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('FedEX') == 'Y') { ?>
                                                <th class="head1">FedEX City Code</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('MomentsKsa') == 'Y') { ?>
                                                <th class="head1">MomentsKsa </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Postagexp') == 'Y') { ?>
                                                <th class="head1">Postagexp </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SMSAEgypt') == 'Y') { ?>
                                                <th class="head1">SMSAEgypt</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Bosta V2') == 'Y') { ?>
                                                <th class="head1">Bosta V2</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('MMCCO') == 'Y') { ?>
                                                <th class="head1">MMCCO</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('KwickBox') == 'Y') { ?>
                                                <th class="head1">KwickBox</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('DHL JONES') == 'Y') { ?>
                                                <th class="head1">DHL JONES</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Thabit') == 'Y') { ?>
                                                <th class="head1">Thabit</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('MICGO') == 'Y') { ?>
                                                <th class="head1">MicGo</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('FDA') == 'Y') { ?>
                                                <th class="head1">FDA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Lastpoint') == 'Y') { ?>
                                                <th class="head1">Lastpoint</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SMB') == 'Y') { ?>
                                                <th class="head1">SMB</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('LAFASTA') == 'Y') { ?>
                                                <th class="head1">LAFASTA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AJA') == 'Y') { ?>
                                                <th class="head1">AJA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Bawani') == 'Y') { ?>
                                                <th class="head1">Bawani</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Flamingo') == 'Y') { ?>
                                                <th class="head1">Flamingo</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AJOUL') == 'Y') { ?>
                                                <th class="head1">AJOUL City Code</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('FLOW') == 'Y') { ?>
                                                <th class="head1">FLOW City</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Mahmool') == 'Y') { ?>
                                                <th class="head1">Mahmool City</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('UPS') == 'Y') { ?>
                                                <th class="head1">UPS City</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Kudhha') == 'Y') { ?>
                                                <th class="head1">Kudhha City</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Mylerz') == 'Y') { ?>
                                                <th class="head1">Mylerz City</th>
                                            <?php } ?>
                                            <th class="head1">ZID CITY</th>
                                            <th class="head1">Salla CITY</th>
                                            <?php if (GetCourierCompanyStausActive('J&T') == 'Y') { ?>
                                                <th class="head1">J&T City</th>
                                                <th class="head1">J&T Country Code</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('EgyptExpress') == 'Y') { ?>
                                                <th class="head1">EgyptExpress City</th>
                                                <th class="head1">EgyptExpress City Code</th>
                                            <?php } ?>
                                            <th class="head1">Country Code</th>
                                            <th class="head1">Currency</th>
                                            <th class="head1">LAT</th>
                                            <th class="head1">LNG</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //print "<pre>"; print_r($result);die;
                                        if (is_array($result) && count($result) > 0) {
                                            $cnt = 1;
                                            foreach ($result as $listdata) {
                                                ?>    
                                                <tr>
                                                    <td><?php echo $cnt++; ?></td>
                                                    <td><?php echo $listdata['city']; ?></td>
                                                    <td>
                                                    <input type="text" name="title" id="title<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['title']; ?>" class="form-control">
                                                        <br>          
                                                        <a class="remove-photo btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'title');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php if(GetCourierCompanyStausActive('SMSA')=='Y'){ ?>
                                                    <td>
                                                        <input type="text" name="samsa_city_name" id="samsa_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['samsa_city']; ?>" class="form-control">
                                                        <br>          
                                                        <a class="remove-photo btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'samsa_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('UBREEM') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="ubreem_city_name" id="ubreem_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['ubreem_city']; ?>" class="form-control">
                                                        <br>          
                                                        <a class="remove-photo btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'ubreem_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                       <?php if ((GetCourierCompanyStausActive('ARAMEX') == 'Y')||(GetCourierCompanyStausActive('Aramex International') == 'Y')){ ?>
                                                    <td>
                                                        <input type="text" name="aramex_city_name" id="aramex_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['aramex_city']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'aramex_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>  
                                                    </td>
                                                    <td>
                                                        <input type="text" name="aramex_country_code" id="aramex_country_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['aramex_country_code']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'aramex_country_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>  
                                                    </td>
                                                      <?php } ?>
                                                      
                                                    <?php if (GetCourierCompanyStausActive('DOTS') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="dots_city_name" id="dots_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['dots_city']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'dots_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>  
                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('IMILE') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="imile_city_name" id="imile_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['imile_city']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'imile_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>  
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('NAQEL') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="naqel_city_name" id="naqel_city_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['naqel_city_code']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'naqel_city_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>  
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('Esnad') == 'Y') { ?>
                                                    <td><input type="text" name="esnad_city_name" id="esnad_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['esnad_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'esnad_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td> 
                                                    <td><input type="text" name="esnad_city_code" id="esnad_city_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['esnad_city_code']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'esnad_city_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td> <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('SAMANA') == 'Y') { ?>
                                                    <td><input type="text" name="samana_city" id="samana_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['samana_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'samana_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                    <?php } ?>
                                                       <?php if (GetCourierCompanyStausActive('AGILITY') == 'Y') { ?>
                                                    <td><input type="text" name="agility_city" id="agility_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['agility_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'agility_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('DESCEN') == 'Y') { ?>
                                                    <td><input type="text" name="descen" id="descen<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['descen']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'descen');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('AYMAKAN') == 'Y') { ?>
                                                    <td><input type="text" name="aymakan" id="aymakan<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['aymakan']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'aymakan');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('ZAJIL') == 'Y') { ?>
                                                    <td><input type="text" name="zajil" id="zajil<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['zajil']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'zajil');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('CLEX') == 'Y') { ?>
                                                    <td><input type="text" name="clex" id="clex<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['clex']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'clex');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                       <?php if (GetCourierCompanyStausActive('RABEL') == 'Y') { ?>
                                                    <td><input type="text" name="rabel_city" id="rabel_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['rabel_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'rabel_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('SPEEDZI') == 'Y') { ?>
                                                    <td><input type="text" name="speedzi_city" id="speedzi_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['speedzi_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'speedzi_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Barqfleet') == 'Y') { ?>
                                                    <td><input type="text" name="barq_city" id="barq_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['barq_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'barq_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Labaih') == 'Y') { ?>

                                                    <td><input type="text" name="labaih" id="labaih<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['labaih']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'labaih');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('MAKHDOOM') == 'Y') { ?>
                                                    <td><input type="text" name="makhdoom_city_name" id="makhdoom<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['makhdoom']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'makhdoom');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('MAKHDOOM V2') == 'Y') { ?>
                                                    <td><input type="text" name="makhdoom_city_code" id="makhdoom_city_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['makhdoom_city_code']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'makhdoom_city_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Saee') == 'Y') { ?>
                                                    <td><input type="text" name="saee_city_name" id="saee_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['saee_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'saee_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('AJEEK') == 'Y') { ?>
                                                    <td><input type="text" name="ajeek_city_name" id="ajeek_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['ajeek_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'ajeek_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('EMDAD') == 'Y') { ?>
                                                    <td><input type="text" name="emdad_city" id="emdad_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['emdad_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'emdad_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                    <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('SHIPSY') == 'Y') { ?>
                                                    <td><input type="text" name="shipsy_city" id="shipsy_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['shipsy_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'shipsy_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Shipadelivery') == 'Y') { ?>
                                                    <td><input type="text" name="shipsa_city" id="shipsa_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['shipsa_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'shipsa_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('TAMEX') == 'Y') { ?>
                                                    <td><input type="text" name="tamex_city" id="tamex_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['tamex_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'tamex_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                     <?php } ?>
                                                     
                                                     <?php if (GetCourierCompanyStausActive('SALLA CITY') == 'Y') { ?>

                                                    <td><input type="text" name="sala" id="sala<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['sala']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'sala');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                     <?php } ?>

                                                    <?php if (GetCourierCompanyStausActive('Alamalkon') == 'Y') { ?>
                                                    <td><input type="text" name="alamalkon_city" id="alamalkon<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['alamalkon']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'alamalkon');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Saudi Post') == 'Y') { ?>
                                                    <td><input type="text" name="saudipost_id" id="saudipost_id<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['saudipost_id']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'saudipost_id');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                      <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Beez') == 'Y') { ?>
                                                    <td><input type="text" name="beez_city" id="beez_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['beez_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'beez_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('FedEX') == 'Y') { ?>
                                                    <td><input type="text" name="fedex_city" id="fedex_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['fedex_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'fedex_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('FedEX') == 'Y') { ?>
                                                    <td><input type="text" name="fedex_city_code" id="fedex_city_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['fedex_city_code']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'fedex_city_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('MomentsKsa') == 'Y') { ?>
                                                    <td><input type="text" name="momentsKsa_city" id="momentsKsa_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['momentsKsa_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'momentsKsa_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                      <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Postagexp') == 'Y') { ?>
                                                    <td><input type="text" name="Postagexp_city" id="Postagexp_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['Postagexp_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'Postagexp_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('SMSAEgypt') == 'Y') { ?>
                                                    <td><input type="text" name="smsa_egypt_city" id="smsa_egypt_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['smsa_egypt_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'smsa_egypt_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Bosta V2') == 'Y') { ?>
                                                    <td><input type="text" name="bosta_city" id="bosta_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['bosta_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'bosta_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('MMCCO') == 'Y') { ?>
                                                    <td><input type="text" name="MMCCO_city" id="MMCCO_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['MMCCO_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'MMCCO_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('KwickBox') == 'Y') { ?>
                                                    <td><input type="text" name="kwickBox_city" id="kwickBox_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['kwickBox_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'kwickBox_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('DHL JONES') == 'Y') { ?>
                                                    <td><input type="text" name="dhl_jones_city" id="dhl_jones_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['dhl_jones_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'dhl_jones_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('Thabit') == 'Y') { ?>
                                                    <td><input type="text" name="thabit_city" id="thabit_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['thabit_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'thabit_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('MICGO') == 'Y') { ?>
                                                    <td><input type="text" name="MICGO_city" id="MICGO_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['MICGO_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'MICGO_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('FDA') == 'Y') { ?>
                                                    <td><input type="text" name="FDA_city" id="FDA_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['FDA_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'FDA_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Lastpoint') == 'Y') { ?>
                                                    <td><input type="text" name="lastpoint_city" id="lastpoint_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['lastpoint_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'lastpoint_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('SMB') == 'Y') { ?>
                                                    <td><input type="text" name="smb_city" id="smb_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['smb_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'smb_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('LAFASTA') == 'Y') { ?>
                                                    <td><input type="text" name="lafasta_city" id="lafasta_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['lafasta_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'lafasta_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('AJA') == 'Y') { ?>
                                                    <td><input type="text" name="AJA_city" id="AJA_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['AJA_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'AJA_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Bawani') == 'Y') { ?>
                                                    <td><input type="text" name="BAWANI_city" id="BAWANI_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['BAWANI_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'BAWANI_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Flamingo') == 'Y') { ?>
                                                    <td><input type="text" name="flamingo_city" id="flamingo_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['flamingo_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'flamingo_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('AJOUL') == 'Y') { ?>
                                                        <td><input type="text" name="ajoul_city_code" id="ajoul_city_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['ajoul_city_code']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'ajoul_city_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('FLOW') == 'Y') { ?>
                                                        <td><input type="text" name="flow_city" id="flow_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['flow_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'flow_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Mahmool') == 'Y') { ?>
                                                        <td><input type="text" name="mahmool_city" id="mahmool_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['mahmool_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'mahmool_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?> 
                                                     <?php if (GetCourierCompanyStausActive('UPS') == 'Y') { ?>
                                                        <td><input type="text" name="ups_city" id="ups_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['ups_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'ups_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Kudhha') == 'Y') { ?>
                                                        <td><input type="text" name="kudhha_city" id="kudhha_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['kudhha_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'kudhha_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>   
                                                     <?php if (GetCourierCompanyStausActive('Mylerz') == 'Y') { ?>
                                                        <td><input type="text" name="mylerz_city" id="mylerz_city<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['mylerz_city']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'mylerz_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                     <?php } ?>   

                                                        <td><input type="text" name="zid" id="zid<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['zid']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'zid');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                        </td>
                                                        <td><input type="text" name="sala" id="sala<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['sala']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'sala');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                        <?php if (GetCourierCompanyStausActive('J&T') == 'Y') { ?>
                                                        <td><input type="text" name="jt_city" id="jt_city<?php echo $listdata['id']; ?>" placeholder="J&T City" value="<?php echo $listdata['jt_city']; ?>" class="form-control">
                                                            <br>
                                                                <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'jt_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                        <td><input type="text" name="jt_country_code" id="jt_country_code<?php echo $listdata['id']; ?>" placeholder="J&T Country Code" value="<?php echo $listdata['jt_country_code']; ?>" class="form-control">
                                                            <br>
                                                                <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'jt_country_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('EgyptExpress') == 'Y') { ?>
                                                        <td><input type="text" name="egyptexpress_city" id="egyptexpress_city<?php echo $listdata['id']; ?>" placeholder="Egyptexpress City" value="<?php echo $listdata['egyptexpress_city']; ?>" class="form-control">
                                                            <br>
                                                                <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'egyptexpress_city');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>
                                                        <td><input type="text" name="egyptexpress_city_code" id="egyptexpress_city_code<?php echo $listdata['id']; ?>" placeholder="Egyptexpress City Code" value="<?php echo $listdata['egyptexpress_city_code']; ?>" class="form-control">
                                                            <br>
                                                                <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'egyptexpress_city_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>

                                                    <?php } ?>    
                                                    <td><input type="text" name="country_code" id="country_code<?php echo $listdata['id']; ?>" placeholder="<?= lang('lang_add_city'); ?>" value="<?php echo $listdata['country_code']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'country_code');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td>  
                                                        
                                                        <td><input type="text" name="currency" id="currency<?php echo $listdata['id']; ?>" placeholder="Currency Code" value="<?php echo $listdata['currency']; ?>" class="form-control">
                                                            <br>
                                                            <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'currency');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>
                                                        </td> 
                                                    <td><input type="text" name="latitute" id="latitute<?php echo $listdata['id']; ?>" placeholder="LAT" value="<?php echo $listdata['latitute']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'latitute');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                    <td><input type="text" name="longitute" id="longitute<?php echo $listdata['id']; ?>" placeholder="LNG" value="<?php echo $listdata['longitute']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'longitute');" value="<?php echo $listdata['id']; ?>"><?= lang('lang_Update'); ?></a>

                                                    </td>
                                                </tr>

                                                <?php
                                            } //endforeach
                                        } else {
                                            ?>
                                            <tr><td colspan="27" class="text-center"><?= lang('lang_No_Data_Found'); ?></td></tr>
                                        <?php } ?>              
                                    </tbody>

                                </table>
                            </div>  
                            </div>
                            <?php if ($totalpages > $startcounter) { ?>
                                <div class="mt-2 mb-4">
                                    <button  class="btn btn-danger" id="load_more_btn" onclick="loadMoreCityData(); return;"><?= lang('lang_Load_More'); ?></button>
                                </div>
                            <?php } ?>

                        </div>

                        <!-- /basic responsive table -->
                        <input type="hidden" name="total_pages" id="total_pages" value="<?php echo $totalpages ?>" />
                        <input type="hidden" name="perPageResult" id="perPageResult" value="<?php echo $per_page_records ?>" />
                        <input type="hidden" name="startcounter" id="startcounter" value="1" />
                        <input type="hidden" name="filter_city" id="filter_city" value="" />
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 

                </div>
                <!-- /main content --> 

            </div>
            <!-- /page content --> 

        </div>


        <!-- /page container -->
        <script type="text/javascript">
             $(function () {
                $('.wrapper1').on('scroll', function (e) {
                    $('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
                }); 
                $('.wrapper2').on('scroll', function (e) {
                    $('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
                });
            });
            $(window).on('load', function (e) {
                $('.div1').width($('table').width());
                $('.div2').width($('table').width());
            });
            function loadMoreCityData() { 
                var startcounter = parseInt($("#startcounter").val());
                var perPageResult = parseInt($("#perPageResult").val());
                var total_pages = parseInt($("#total_pages").val());
                var search_val = $("#search_val").val();
                if (startcounter > 0) {
                    start = perPageResult * startcounter;
                }

                $.ajax({
                    url: '<?php echo base_url('Country/filter_city'); ?>',
                    data: {offset: start, limit: perPageResult, filter_by: search_val},
                    error: function () { },
                    dataType: 'html',
                    type: 'POST',
                    beforeSend: function () {
                        $("#loadershow").show();
                    },
                    complete: function () {
                        $("#loadershow").hide();
                    },
                    success: function (data) {
                        $("#startcounter").val(startcounter + 1);
                        $('#city_list_paging > tbody:last-child').append(data);
                        if (startcounter >= (total_pages - 1)) {
                            $("#load_more_btn").hide();
                        }
                    }

                });
            }

            function updateCityListData(select_id, type) {
           // alert(select_id);
             //alert(type);
                var columnVal = $("#" + type + select_id).val();
                var city_id = select_id;
                var column_name = type;
              ///  alert(columnVal);

                $.ajax({
                    url: '<?php echo base_url('Country/UpdateCityList'); ?>',
                    data: {city_id: city_id, columnVal: columnVal, column_name: column_name},
                    error: function () { },
                    dataType: 'html',
                    type: 'POST',
                    success: function (data) {
                        var jsonData = $.parseJSON(data);
                        if (jsonData.success) {
                            alert('Data updated successfully');
                        }
                    },

                });

            }
            function Valid_search() {
                var searchVal = $("#search_val").val();

                if (searchVal == '') {
                    alert("Please enter city name to search");
                    return false;
                }
                const url = window.location.href.split('?')[0];
                window.location.href = url + '?filter_by=' + searchVal;

            }
            function clearFilter() {
                const url = window.location.href.split('?')[0];
                window.location.href = url;
            }
        </script>
    </body>
</html>
