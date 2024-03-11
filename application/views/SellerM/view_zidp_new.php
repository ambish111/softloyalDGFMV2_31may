<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <style>
.pagination {
				list-style-type: none;
				padding: 10px 0;
				display: inline-flex;
				justify-content: space-between;
				box-sizing: border-box;
			}
			.pagination li {
				box-sizing: border-box;
				padding-right: 10px;
			}
			.pagination li a {
				box-sizing: border-box;
				background-color: #e2e6e6;
				padding: 8px;
				text-decoration: none;
				font-size: 12px;
				font-weight: bold;
				color: #616872;
				border-radius: 4px;
			}
			.pagination li a:hover {
				background-color: #d4dada;
			}
			.pagination .next a, .pagination .prev a {
				text-transform: uppercase;
				font-size: 12px;
			}
			.pagination .currentpage a {
				background-color: #518acb;
				color: #fff;
			}
			.pagination .currentpage a:hover {
				background-color: #518acb;
			}

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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?> 

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Zid Product List</strong></h1>

                            </div>
                            <?php
                                                    $warehouse = Getwarehouse_Dropdata();
                                                    $storageArr = Getallstorage_drop();
                                                    ?>
                            <div class="panel-body" >

                                    <form method="post" action="<?php echo base_url('Seller/ZidProductsNew/'.$target_url); ?>">
                                                <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group" >
                                                    <label  >Alerady exist:</label>          
                                                    <select class="form-control" id="exist" name="exist" data-width="100%">
                                                        <option value="">Select </option>
                                                        <option value="Yes" <?php  if($aleradyexist == "Yes"){ echo "selected='selected'";  } ?> >Yes</option>
                                                        <option value="No" <?php  if($aleradyexist == "No"){ echo "selected='selected'" ; } ?> >No</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-3">
                                                    <div class="form-group ">
                                                        <label for="">Keywords:</label>
                                                        <input class="form-control" placeholder="Enter keyword" type="text" id="sku" name="sku">
                                                    </div>
                                                </div> -->
                                                <div class="col-md-3">
                                                    <div class="form-group ">
<!--                                                        <button  class="btn btn-primary" value="Save SKU" style="margin-top:27px;
                                                    margin-right: 10px;" disabled>Total:<?php echo $totalproducts; ?></button>-->
                                                        <input class="btn btn-danger pull-right" type="submit" value="Search" style="margin-top:27px;
                                                    margin-right: 10px;">                                        
                                                </div>

                                                </div>
                                                </div>
                                                <input type="hidden" id="pageno" name="pageno" value="<?=$page; ?>">
                                        </form>
                                    
                                    <hr style="border:1px solid #ccc;" />                            
                                        <form method="post" action="<?php echo base_url(); ?>Seller/SaveZidProducts">
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
                                        <input type="hidden" class="form-control"   name="seller_id" value="<?=$seller_id;?>" required>
                                        
                                        
                                        </div>
                                        </div>
                                                    <div class="col-md-3">
                                        <div class="form-group ">
                                        
                                       
   <input type="submit" class="btn btn-primary pull-right" value="Save SKU" style="margin-top:27px" /> </div>

</div>
</div>
                                                   

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                  
                                        <table class="table table-striped table-hover table-bordered dataTable bg-*" id="">
                                            <thead>
                                                <tr>
                                                    <th> <input type="checkbox" id="checkAll" /> &nbsp;Select Product</th>
                                                    <th>SKU</th>
                                                    <th>Image</th>
                                                    <th>Aready Exist </th>
                                                    <th>Zid ID</th>
                                                    <th>Name</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $sr = 0; ?>

                                                <?php if (!empty($products)): ?>
                                                   
                                                    <?php foreach ($products as $key=>$product): ?>

                                                        <tr>
                                                            <?php
                                                            $is_exist = exist_zidsku_id($product['sku'], $this->session->userdata('user_details')['super_id']);
                                                        //    if(empty($is_exist))
                                                        //     {
                                                            
                                                            ?>
                                                            <td><input type="checkbox"  name="selsku[]" value="<?php echo $sr; ?>"></td>
                                                            <td><input type="hidden" name="sku[]" value="<?= $product['sku']; ?>"><?php echo $product['sku']; ?></td>
                                                            
                                                            <td><input type="hidden" name="image[]" value="<?= $product['images'][0]['image']['small']; ?>">
                                                            <img height="50" width="50" src="<?=$product['images'][0]['image']['thumbnail']; ?>"?>
                                                        </td>
                                                            <td><?php if(!empty($is_exist))
                                                             { echo 'Yes';} else { echo 'No';} ?></td>
                                                            <td><input type="hidden" name="pid[]" value="<?= $product['id']; ?>"><?php echo $product['id']; ?></td>
                                                            <td><input type="hidden" name="skuname[]" value="<?= $product['name']['ar']; ?>">
                                                            <input type="hidden" name="description[]" value="<?= $product['slug']; ?>">
                                                            <?php echo $product['name']['ar']; ?></td>
                                                            
                                                           <?php  $sr++; //}
                                                            ?>
                                                        </tr>

                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                        </form>
                                        </div>
                                        <form method="post" id="productForm" action="<?php echo base_url(); ?>Seller/ZidProductsNew/<?=$seller_id;?>">
                                        <div class="container">
                                            <div class=" col-md-12 text-center" >

                                            <?php if (ceil($total_pages / $perPage) > 0): ?>
                                                <ul class="pagination">
                                                    <?php if ($page > 1): ?>
                                                    <li class="prev"><a  onclick="Submit_pagination('<?php echo $page-1 ?>">Prev</a></li>
                                                    <?php endif; ?>

                                                    <?php if ($page > 3): ?>
                                                    <li class="start"><a  onclick="Submit_pagination('1')">1</a></li>
                                                    <li class="dots">...</li>
                                                    <?php endif; ?>

                                                    <?php if ($page-2 > 0): ?><li class="page"><a  onclick="Submit_pagination('<?php echo $page-2 ?>')"><?php echo $page-2 ?></a></li><?php endif; ?>
                                                    <?php if ($page-1 > 0): ?><li class="page"><a  onclick="Submit_pagination('<?php echo $page-1 ?>')"><?php echo $page-1 ?></a></li><?php endif; ?>

                                                    <li class="currentpage"><a  onclick="Submit_pagination('<?php echo $page ?>')"><?php echo $page ?></a></li>

                                                    <?php if ($page+1 < ceil($total_pages / $perPage)+1): ?><li class="page"><a  onclick="Submit_pagination('<?php echo $page+1 ?>')"><?php echo $page+1 ?></a></li><?php endif; ?>
                                                    <?php if ($page+2 < ceil($total_pages / $perPage)+1): ?><li class="page"><a  onclick="Submit_pagination('<?php echo $page+2 ?>')"><?php echo $page+2 ?></a></li><?php endif; ?>

                                                    <?php if ($page < ceil($total_pages / $perPage)-2): ?>
                                                    <li class="dots">...</li>
                                                    <li class="end"><a  onclick="Submit_pagination('<?php echo $total_pages ?>')"><?php echo $total_pages; ?></a></li>
                                                    <?php endif; ?>

                                                    <?php if ($page < ceil($total_pages / $perPage)): ?>
                                                    <li class="next"><a  onclick="Submit_pagination('<?php echo $page+1 ?>')">Next</a></li>
                                                    <?php endif; ?>
                                                    <?php if($current_page < $total_pages) { ?>
                                                        <li class="page-item"><a class="btn btn-primary btn-sm" onclick="Submit_pagination('<?=$total_pages;?>')">&nbsp;&nbsp;&nbsp;Last</a></li>
                                                    <?php  }?>
                                                </ul>
                                                <?php endif; ?>
                                                <!-- <ul class="pagination">
                                                    <?php if($current_page>1) { ?>
                                                        <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page-1;?>')"><< Previous</a></li>
                                                    <?php } ?>
                                                    <?php for($i=1;$i<$total_pages;$i++) { ?>
                                                            <li class="page-item <?php if($current_page==$i) {?> active <?php } ?>"  style=" margin-left:2px;"><a class="page-link" onclick="Submit_pagination('<?=$i;?>')" ><?=$i;?></a> </li> 
                                                    <?php  }?>
                                                    <?php if ($page+1 < ceil($total_pages / $perPage)+1): ?><li class="page"><a onclick="Submit_pagination('<?php echo $page+1 ?>')"><?php echo $page+1 ?></a></li><?php endif; ?>
                                                    <?php if ($page+2 < ceil($total_pages / $perPage)+1): ?><li class="page"><a onclick="Submit_pagination('<?php echo $page+2 ?>')"><?php echo $page+2 ?></a></li><?php endif; ?>
                                                    <?php if ($page < ceil($total_pages / $perPage)-2): ?>
                                                        <li class="dots">...</li>
                                                        <li class="end page-item"><a onclick="Submit_pagination('<?php echo ceil($total_pages / $perPage) ?>')"> <?php echo ceil($total_pages / $perPage) ?></a></li>
                                                    <?php endif; ?>
                                                    <?php if($current_page < $total_pages) { ?>
                                                        <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page+1;?>')">Next >></a></li>
                                                        <li class="page-item"><a class="page-link btn btn-primary btn-sm" style="" onclick="Submit_pagination('<?=$total_pages;?>')">&nbsp;&nbsp;&nbsp;Last</a></li>
                                                    <?php  }?>
                                                </ul> -->
                                      </div>
                                      <?php if (!empty($products) && 1 && 0): ?>    
                                        <div class="container">
                                            <div class="col-md-12">                                        
<!--                                      <?php if($current_page<=$total_pages){ ?>
                                            <ul class="pagination">
                                                <?php if($current_page>1) { ?>
                                                        <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=1;?>')">First Page</a></li>
                                                <?php } ?>
                                                <?php if($current_page>4) { ?>
                                                    <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page-1;?>')"><< Previous</a></li>
                                                <?php } ?>
                                                <?php if($current_page==$page && $current_page>3) { 
                                                        $frd=$page-3;
                                                ?>
                                                <li class="page-item "><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page-3;?>')"><?=$frd;?></a></li>
                                                <?php  }?>
                                                <?php if($current_page==$page && $current_page>2) { 
                                                            $trd=$page-2;
                                                ?>
                                                <li class="page-item "><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page-2;?>')"><?=$trd;?></a></li>
                                                <?php  }?>
                                                <?php if($current_page==$page && $current_page>1) { 
                                                        $snd=$page-1;
                                                ?>
                                                    <li class="page-item "><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page-1;?>')"><?=$snd;?></a></li>
                                                <?php  }?>
                                            
                                                <li class="page-item <?php if($current_page==$page) {?> active <?php } ?>"  style=" margin-left:2px;"><a class="page-link" onclick="Submit_pagination('<?=$i;?>')" ><?=$page;?></a> </li> 

                                                <?php if($current_page==$page && $current_page!=$total_pages) { 
                                                    $snd=$page+1;
                                                ?>
                                                <li class="page-item "><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page+1;?>')"><?=$snd;?></a></li>
                                                <?php  }?>
                                                <?php if($current_page==$page && $current_page!=$total_pages){ 
                                                    $trd=$page+2;
                                                ?>
                                                <li class="page-item "><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page+2;?>')"><?=$trd;?></a></li>
                                                <?php  }?>
                                                <?php if($current_page==$page && $current_page!=$total_pages) { 
                                                    $frd=$page+3;
                                                ?>
                                                <li class="page-item "><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page+3;?>')"><?=$frd;?></a></li>
                                                <?php  }?>
                                                  <?php if( $current_page==$page && $current_page+10!=$total_pages) { ?>
                                                    <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page+10;?>')">. . .</a></li>
                                                <?php  }?> 
                                                <?php if($current_page!=$total_pages) { ?>
                                                    <li class="page-item"><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$current_page+1;?>')">Next >></a></li>
                                                <?php  }?>

                                                <?php if($current_page!=$total_pages) { ?>
                                                    <li class="page-item" ><a class="page-link" style="background-color:#d0caca " onclick="Submit_pagination('<?=$total_pages;?>')" >Last page</a> </li> 
                                                <?php  }?>
                                            </ul>
                                            <?php  }?>                                     -->
                                        </div>
                                        <?php endif; ?>
                                      <input type="hidden" value="<?=$current_page;?>" name="i" id="pagination" >
                                      
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
