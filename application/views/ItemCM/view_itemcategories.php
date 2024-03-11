<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="icon" href="https://wwwimages2.adobe.com/favicon.ico" type="image/x-icon"> -->
  <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
  <title class="icon-office">Inventory</title>
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
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?> 

          <!-- Basic responsive table -->
          <div class="panel panel-flat" >
            <!--style="padding-bottom:220px;background-color: lightgray;"-->
            <div class="panel-heading">
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
              <h1><strong>Item Categories Table</strong></h1>

              <div class="heading-elements">
                <ul class="icons-list">
                  <!-- <li><a data-action="collapse"></a></li>
                  <li><a data-action="reload"></a></li> -->
                  <!-- <li><a data-action="close"></a></li> -->
                </ul>
              </div>
              <hr>
            </div>

            <div class="panel-body" >

              <!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
 -->
            

            <div class="table-responsive" style="padding-bottom:20px;" >
              <!--style="background-color: green;"-->
              <table class="table table-striped  table-bordered dataTable bg-*" id="example">
                <thead>
                  <tr>
                    <th>Id#</th>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th class="text-center" ><i class="icon-menu-open2"></i></th>
                  </tr>
                </thead>
                <tbody>
        
                  <?php if(!empty($itemcategories)): ?>
                    
                    <?php foreach($itemcategories as $itemcategory): ?>
                      <tr>
                      <td><?= $itemcategory->id; ?></td>
                      <td><?= $itemcategory->name; ?></td>
                      <?php $i=0;?>
                      <?php foreach($itemcategories as $item): ?>
                        
                      <?php if($itemcategory->main_id==$item->id && $i==0): ?>
                      <td><?= $item->name; ?></td>  
                      <?php $i++;?> 
                      <?php elseif($itemcategory->main_id==0 && $i==0):?>
                      <td>-</td>
                      <?php $i++;?> 
                      <?php endif; ?>
                      <?php endforeach;?>

                      <td class="text-center">
                        <ul class="icons-list">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="icon-menu9"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                              <li><a href="<?= site_url('ItemCategory/edit_view/'.$itemcategory->id);?>"><i class="icon-database-edit2"></i> Edit </a></li>
                              <!-- <li><a href="#"><i class="icon-file-pdf"></i> Export to .pdf</a></li>
                              <li><a href="#"><i class="icon-file-excel"></i> Export to .csv</a></li>
                              <li><a href="#"><i class="icon-file-word"></i> Export to .doc</a></li> -->
                            </ul>
                          </li>
                        </ul>
                      </td>
                    </tr>

                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
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
<script>
$(document).ready(function() {
    var table = $('#example').DataTable({});
} );
  
 
</script>
<!-- /page container -->

</body>
</html>
