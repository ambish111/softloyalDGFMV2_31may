<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    </head>

    <style>
        .subject-info-box-1,
        .subject-info-box-2 {
            float: left;
            width: 45%;

            select[multiple],
            select[size] {


                height: 400px !important;
                padding: 0;

                option {
                    padding: 4px 10px 4px 10px;
                }

                option:hover {
                    background: #EEEEEE;
                }
            }
        }

        .subject-info-arrows {
            float: left;
            width: 10%;

            input {
                width: 70%;
                margin-bottom: 5px;
            }
        }
    </style>
    <style>
        .bigdrop {
            width: 100% !important;
        }
        .select2-container {
            min-width: 400px;
        }
        .select2-results {
            background-color: #f5f5f5;
        }

        .select2-selection__choice{
            color:#000!important;
        }
        .select2-container--default .select2-results__option[aria-selected=true]{
            background:#29aba3!important;
        }
        .select2-results__option {
            padding-right: 20px;
            vertical-align: middle;
        }
        .select2-results__option:before {
            content: "";
            display: inline-block;
            position: relative;
            height: 20px;
            width: 20px;
            border: 2px solid #e9e9e9;
            border-radius: 4px;
            background-color: #fff;
            margin-right: 20px;
            vertical-align: middle;
        }
        .select2-results__option[aria-selected=true]:before {
            font-family:fontAwesome;
            content: "\f00c";
            color: #fff;
            background-color: #f77750;
            border: 0;
            display: inline-block;
            padding-left: 3px;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #fff;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eaeaeb;
            color: #272727;
        }
        .select2-container--default .select2-selection--multiple {
            margin-bottom: 10px;
        }
        .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
            border-radius: 4px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #f77750;
            border-width: 2px;
        }
        .select2-container--default .select2-selection--multiple {
            border-width: 2px;
        }
        .select2-container--open .select2-dropdown--below {

            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);

        }
        .select2-selection .select2-selection--multiple:after {
            content: 'hhghgh';
        }
        /* select with icons badges single*/
        .select-icon .select2-selection__placeholder .badge {
            display: none;
        }
        .select-icon .placeholder {
            display: none;
        }
        .select-icon .select2-results__option:before,
        .select-icon .select2-results__option[aria-selected=true]:before {
            display: none !important;
            /* content: "" !important; */
        }
        .select-icon  .select2-search--dropdown {
            display: none;
        }
        .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h1><strong>Conect Seller To Diggistores</strong></h1>
                            </div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('msg') != '') {
                                    echo '<div class="alert alert-info" role="alert">  ' . $this->session->flashdata('msg') . '.</div>';
                                }
                                ?>
                                    <?php if (empty($EditData)) { ?>
                                            <form action="<?= base_url('Diggistores/add_diggistores'); ?>" method="post"  name="add_customer" >
                                        <?php } else { ?>
                                        <form action="<?= base_url('Diggistores/edit_diggistores/' . $id); ?>" method="post"  name="add_customer" >
                                    <?php } ?>




                                        
                                            
                                            

                                            <div class="form-group" >

                                                <label><?= lang('lang_Seller'); ?></label>
                                                <br>
                                                    <?php if (!empty($EditData)) {
                                                        echo '<strong> ' . $EditData[0]->cust_name . '</strong>';
                                                    } else { ?>


                                                    <select  id="courier_id" name="cust_id" class="form-control"  >

                                                        <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                        <?php foreach ($sellers as $seller_detail): ?>
                                                            <option value="<?= $seller_detail->id; ?>"><?= $seller_detail->name; ?></option>
                                                        <?php endforeach; ?>

                                                    </select>
                                                <?php } ?>
                                            </div> 
                                            <div class="form-group" >

                                                <label>Country</label>
                                                <br>
                                                    <select  id="country" name="country" class="form-control" >
                                                        <option value="">--Select Country--</option>
                                                        <?php foreach ($country as $data): ?>
                                                            <option value="<?= $data->country; ?>"><?= $data->country; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                            </div> 
                                            
                                            <div class="form-group " style="margin-bottom 50px !important; min-height: 250px;" >  
                                                <div class="form-group ">
                                                    <label> <?= lang('lang_City'); ?></label>&nbsp;<span class="city_error text-danger hidden"> <b>(City Not Found) </b></span>
                                                </div>  
                                                <div class="subject-info-box-1">
                                                    <select multiple="multiple" id='lstBox1'  class="form-control">
                                                            <?php if (!empty($ListArr)): ?>
                                                                <?php foreach ($ListArr as $rows): ?>
                                                                    <option value="<?= $rows['city']; ?>"> <?= $rows['city']; ?> </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                    </select>
                                                </div>

                                                <div class="subject-info-arrows text-center">
                                                    <input type='button' style="margin-top:5px;" id='btnAllRight' value='>>' class="btn btn-info" /><br />
                                                    <input type='button' style="margin-top:5px;"id='btnRight' value='>' class="btn btn-info" /><br />
                                                    <input type='button' style="margin-top:5px;" id='btnLeft' value='<' class="btn btn-info" /><br />
                                                    <input type='button' style="margin-top:5px;" id='btnAllLeft' value='<<' class="btn btn-info" />
                                                </div>
                                                <div class="subject-info-box-2">
                                                    <select multiple="multiple" name="city_id[]"id='lstBox2' class="form-control">
                                                        <?php if (!empty($pre)): ?>
                                                            <?php foreach ($pre as $rows):
                                                                ?>
                                                                <option selected value="<?= $rows['city']; ?>" > <?= $rows['city']; ?> </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>  
                                                    </select>
                                                </div>
                                            </div>



                                            <div class="form-group " >  


                                                <button class="btn btn-warning" type="button"  id="selectAll"><?= lang('lang_Confirm'); ?></button>
                                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Save</button>
                                            </div>
                                    </form>
                            </div>
                        </div>
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
</html>
<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }
    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>

<script type="text/javascript">
    function updateTextInput(dta)
    {

        $('#capacity').html(':<strong>' + dta + '</strong>');
    }

    $(".js-select4").select2({
        closeOnSelect: false,
        placeholder: "Select",
        theme: "bootstrap",
        allowHtml: true,
        allowClear: true,
        tags: true // �?оздает новые опции на лету
    });

    $(document).ready(function () {
        $("#e1").select2({dropdownCssClass: 'bigdrop'});
    });
</script>  



<Script>
    $(document).ready(function () {
        $('#selectAll').click(function () {

            $('#lstBox2 option').prop('selected', true);
            $('#subButton').prop('disabled', false);
        });
    });

    $("#country").change(function () {
        var c_name = $(this).val();
        $.ajax({
            url: '<?php echo base_url('Diggistores/filter_by_country'); ?>',
            method: "POST",
            data: {c_name: c_name},
            dataType: "html",
            beforeSend: function () {
                $(".city_error").addClass('hidden');
            },
            complete: function () {},
            success: function (result) {
                var response = $.parseJSON(result);
                if (response.status == "true") {
                    if (response.data.length > 0) {
                        var str = '';

                        for (var i = 0; i < response.data.length; i++) {
                            str += '<option value="' + response.data[i].city + '">' + response.data[i].city + '</option> '
                        }
                        $("#lstBox1").html(str);
                    }
                } else {
                    $("#lstBox1").html("");
                    $(".city_error").removeClass('hidden');
                    //alert(response.message);
                }

            }
        });
    });
    (function () {





        $("#btnRight").click(function (e) {
            var selectedOpts = $("#lstBox1 option:selected");
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $("#lstBox2").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        });

        $("#btnAllRight").click(function (e) {
            var isconfirm = confirm("Do you really want to add cities! its huge...");
            if (isconfirm)
            {


                var selectedOpts = $("#lstBox1 option");
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }

                $("#lstBox2").append($(selectedOpts).clone());
                $(selectedOpts).remove();
                $('#subButton').prop('disabled', true);
                e.preventDefault();
            }
        });

        $("#btnLeft").click(function (e) {
            var selectedOpts = $("#lstBox2 option:selected");
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $("#lstBox1").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        });

        $("#btnAllLeft").click(function (e) {
            var selectedOpts = $("#lstBox2 option");
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $("#lstBox1").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        });
    })(jQuery);
</Script>
