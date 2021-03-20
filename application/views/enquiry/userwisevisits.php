<div class="row" id="filter_pannel">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading no-print">
                <div class="btn-group">                    
                    <a class="btn btn-success" href="<?php echo base_url(); ?>"
                        style="margin-left: 5 px !important ;"> <i class="fa fa-list"></i>
                        <?php echo 'Visit List' ?> </a>
                    
                </div> 
            </div>
            <div class="panel-body">
                <div class="widget-title">                    
                </div>
                <div class="panel-body">
                    <form>
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <label>From Date
                                <label>                        
                                <input type="date" name='from_date' value="<?php if (isset($_GET['from_date'])) { echo $_GET['from_date'];}?>" class='form-control' required>
                            </div>
                            <div class="col-md-3">
                                <label>To Date
                                <label>                        
                                <input type="date" value="<?php if (isset($_GET['to_date'])) { echo $_GET['to_date'];}?>" name='to_date' class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="<?php echo base_url('client/user_wise_visit');?>"class="btn btn-default">Reset</a>
                            </div>

                        </div>
                    </form>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr> 
                            <th>
                            Employee
                            </th>
                            <th>
                            Branch
                            </th>
                            <th>
                            Area
                            </th>
                            <th>
                            Region
                            </th>
                            <th>
                            No of visit
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!empty($visits)){
                            foreach ($visits as $key => $value){
                                ?>
                                <tr>
                                    <td><?=$value['employee']??'NA'?></td>
                                    <td><?=$value['branch_name']??'NA'?></td>
                                    <td><?=$value['area_name']??'NA'?></td>
                                    <td><?=$value['region_name']??'NA'?></td>
                                    <td><?=$value['c']?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
            