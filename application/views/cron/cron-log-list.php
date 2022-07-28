<style>
.morecontent span {
    display: none;
}
.morelink {
    display: block;
}
a:hover, a:focus {
    text-decoration: none;
    outline: none;
    color: #37a000;
	font-weight:900;
}
</style>
<div class="row">

    <!--  table area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
            <div class="panel-heading no-print">
                
            </div>

            <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                <th>S No.</th>
                <th>Date/Time</th>
                </tr>
                </thead>

                <tbody>
                    <?php $i=1;
                      foreach ($crons as $key => $value) {   ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= date('d-m-Y H:i:s',strtotime($value->added_date)); ?></td>
                        </tr>
                        <?php } ?>

                </tbody>

              </table>
             
            </div>

            <!-- /.card-body -->

          </div>

          <!-- /.card -->

        </div>

        <!-- /.col -->

      </div>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
