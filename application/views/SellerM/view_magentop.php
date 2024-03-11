<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>


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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?> 


                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Magento Product List</strong></h1>

                            </div>
                            <?php
                            $warehouse = Getwarehouse_Dropdata();
                            $storageArr = Getallstorage_drop();
                            ?>
                            <div class="panel-body" >

                                <form method="post" action="<?php echo base_url('Seller/magentoProducts/' . $seller_id); ?>">
                                    <div class="row">
<!--                                        <div class="col-md-3">
                                            <div class="form-group" >
                                                <label  >Alerady exist:</label>          
                                                <select class="form-control" id="exist" name="exist" data-width="100%">
                                                    <option value="">Select </option>
                                                    <option value="Yes" <?php
                                                    if ($aleradyexist == "Yes") {
                                                        echo "selected='selected'";
                                                    }
                                                    ?> >Yes</option>
                                                    <option value="No" <?php
                                                    if ($aleradyexist == "No") {
                                                        echo "selected='selected'";
                                                    }
                                                    ?> >No</option>
                                                </select>

                                            </div>
                                        </div>-->
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label for="">SKU:</label>
                                                <input class="form-control" placeholder="Enter Sku" type="text" id="search_sku" name="search_sku">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group ">

                                                <input class="btn btn-danger pull-right" type="submit" value="Search" style="margin-top:27px;
                                                       margin-right: 10px;">                                        
                                            </div>

                                        </div>
                                    </div>
                                    <input type="hidden" id="pageno" name="pageno" value="<?= $page; ?>">
                                </form>

                                <hr style="border:1px solid #ccc;" />                            
                                <form method="post" action="<?php echo base_url(); ?>Seller/SaveMagentoProducts">
                                    <div class="col-md-3">
                                        <div class="form-group ">

                                            <label>Select Warehouse</label>


                                            <select  class="form-control" name="warehouseid" required>

                                                <?php
                                                foreach ($warehouse as $warehose1) {
                                                    echo '<option value="' . $warehose1['id'] . '">' . $warehose1['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">

                                            <label>Select Storage</label>

                                            <select  class="form-control" name="storageid" required>

                                                <?php
                                                foreach ($storageArr as $storage) {
                                                    echo '<option value="' . $storage['id'] . '">' . $storage['storage_type'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">

                                            <label>Select Sku Capacity</label>
                                            <input type="number" class="form-control"   name="sku_size" value="10" required>
                                            <input type="hidden" class="form-control"   name="seller_id" value="<?= $seller_id; ?>" required>


                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">


                                            <input type="submit" class="btn btn-primary pull-right" value="Save SKU" style="margin-top:27px" /> </div>

                                    </div>
                            </div>


                            <div class="table-responsive" style="padding-bottom:20px;" >
                                <!--style="background-color: green;"-->

                                <table class="table table-striped table-hover table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th> <input type="checkbox" id="checkAll" /> &nbsp;Select Product</th>
                                            <th>SKU</th>
                                            <th>Name</th>
                                               <th>Weight</th>
                                            <th>Image</th>
                                            <th>Aready Exist </th>
                                            

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $sr = 0; ?>

                                            <?php if (!empty($products)): ?>

                                                <?php foreach ($products['items'] as $key => $product): ?>

                                                <tr>
                                                    <?php
                                                    $is_exist = exist_zidsku_id($product['sku'], $this->session->userdata('user_details')['super_id']);
                                                    //    if(empty($is_exist))
                                                    //     {
                                                    ?>
                                                    
                                                    <td><?php echo $sr + 1; ?><br><input type="checkbox"  name="selsku[]" value="<?php echo $sr; ?>"></td>
                                                    <td><input type="hidden" name="sku[]" value="<?= $product['sku']; ?>">
                                                        
                                                        <input type="hidden" name="weight[]" value="<?= $product['weight']; ?>">
                                                        <input type="hidden" name="magento_id[]" value="<?= $product['id']; ?>"><?php echo $product['sku']; ?></td>
                                                    <td><input type="hidden" name="skuname[]" value="<?= $product['name']; ?>">
                                                        <input type="hidden" name="image[]" value="<?= 'https://edumalls.com/media/catalog/product/'.$product['custom_attributes'][3]['value']; ?>"> <?php echo $product['name']; ?></td>
                                            <input type="hidden" name="description[]" value="<?= substr(strip_tags($product['custom_attributes'][2]['value']), 0, 5000) . ""; ?>"> <td><?= $product['weight']; ?></td>
                                            <td><img src="<?= 'https://edumalls.com/media/catalog/product/'.$product['custom_attributes'][3]['value']; ?>" height="50" width="50"></td>
                                            
                                            <td><?php
                                                if (!empty($is_exist)) {
                                                    echo 'Yes';
                                                } else {
                                                    echo 'No';
                                                }
                                                ?></td>
                                            <?php $sr++; //}
                                            ?>
                                            </tr>

    <?php endforeach; ?>
<?php endif; ?>

                                    </tbody>
                                </table>
                                </form>
                            </div>
                            <form method="post" id="productForm" action="<?php echo base_url(); ?>Seller/magentoProducts/<?= $seller_id; ?>">
                                <div class="container">
                                    <div class=" col-md-12">
                                        <ul class="pagination">
                                            <?php if ($current_page > 1) { ?>
                                                <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?= $current_page - 1; ?>')"><< Previous</a></li>
                                            <?php } ?>
                                            <?php for ($i = 1; $i < $total_pages; $i++) { ?>
                                                <li class="page-item <?php if ($current_page == $i) { ?> active <?php } ?>"  style=" margin-left:2px;"><a class="page-link" onclick="Submit_pagination('<?= $i; ?>')" ><?= $i; ?></a> </li> 
                                    <?php } ?>
                                    <?php if ($current_page < $total_pages) { ?>
                                                <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?= $current_page + 1; ?>')">Next >></a></li>
<?php } ?>
                                        </ul>
                                    </div>
                                            <?php if (!empty($products)): ?>    
                                        <div class="container">
                                            <div class="col-md-12">                                        
                                              
                                            </div>
<?php endif; ?>
                                        <input type="hidden" value="<?= $current_page; ?>" name="i" id="pagination" >

                                        </form>               

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
            $(document).ready(function () {
                var table = $('#example').DataTable({});

            });

            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

        </script>
        <script language="javascript" type="text/javascript">
            function Submit_pagination(id) {
                $("#pagination").val(id);
                $("#productForm").submit();
            }
        </script>
        <!-- /page container -->

    </body>
</html>
