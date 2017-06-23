 <!-- navbar top -->
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
            <!-- navbar-header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">
                    <img src="<?php echo base_url()?>assets/bo/images/logo/logo.png" alt="Satu Scan" />
                </a>
            </div>
            <!-- end navbar-header -->
            <!-- navbar-top-links -->
            <ul class="nav navbar-top-links navbar-right">
           <li class="dropdown">
             <?php $sql = $this->m_master->get_notif_splorder();?>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <?php if ($sql->num_rows() > 0){
                    	echo '<span class="top-label label label-info">'.$sql->num_rows().'</span>  <i class="icon-briefcase fa-3x"></i>';
                    }else{
						echo '<span class="top-label label label-warning">'.$sql->num_rows().'</span>  <i class="icon-briefcase fa-3x"></i>';
					}?>
                        
                    </a>
                    <!-- dropdown alerts-->
                    <ul class="dropdown-menu dropdown-alerts">
                    	<?php
                    	if ($sql->num_rows() > 0){
							$no=1;
							foreach ($sql->result() as $row){
								echo '<li>
			                            <a href="'.base_url('bo/mpsplorder/confirm_notif_order/'.$row->id_supplier_order.'/'.$row->id_transaksi).'">			                                
			                                  '.$no++.'. <b>'.$row->id_transaksi.'</b><br>'.$row->nama_supplier.'<br>
			                                    <span class="text-muted small">'.last_time($row->date_add).'</span>
			                               
			                            </a>
			                        </li>';
								echo '<li class="divider"></li>';
							}
						}else{
							echo '<li> <a class="text-center" href="javascript:void(0)">-- Tidak ada supplier order --</a> </li><li class="divider"></li>';
						}?>                                                                     
                        <li>
                            <a class="text-center" href="<?php echo base_url('bo/mporder/confirm_notif_order')?>">
                                <strong>Lihat Semua Supplier Order</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- end dropdown-alerts -->
                </li>
            <li class="dropdown">
             <?php $sql = $this->m_master->get_notif_so();?>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <?php if ($sql->num_rows() > 0){
                    	echo '<span class="top-label label label-success">'.$sql->num_rows().'</span>  <i class="icon-flag fa-3x"></i>';
                    }else{
						echo '<span class="top-label label label-warning">'.$sql->num_rows().'</span>  <i class="icon-flag fa-3x"></i>';
					}?>
                        
                    </a>
                    <!-- dropdown alerts-->
                    <ul class="dropdown-menu dropdown-alerts">
                    	<?php
                    	if ($sql->num_rows() > 0){
							$no=1;
							foreach ($sql->result() as $row){
								echo '<li>
			                            <a href="'.base_url('bo/mporder/confirm_notif_order/'.$row->id_transaksi).'">			                                
			                                  '.$no++.'. <b>'.$row->id_transaksi.'</b><br>'.$row->nama_customer.'<br>
			                                    <span class="text-muted small">'.last_time($row->date_add).'</span>
			                               
			                            </a>
			                        </li>';
								echo '<li class="divider"></li>';
							}
						}else{
							echo '<li> <a class="text-center" href="javascript:void(0)">-- Tidak ada sales order --</a> </li><li class="divider"></li>';
						}?>                                                                     
                        <li>
                            <a class="text-center" href="<?php echo base_url('bo/mporder/confirm_notif_order')?>">
                                <strong>Lihat Semua Sales Order</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- end dropdown-alerts -->
                </li>
                <!-- main dropdown -->               
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-3x"></i>
                    </a>
                    <!-- dropdown user-->
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo base_url('bo/mpuser')?>"><i class="fa fa-user fa-fw"></i>User Account</a>
                        </li>                       
                        <li class="divider"></li>
                       	 <li><a href="<?php echo base_url('bo/mplogin/logout')?>"><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                        </li>
                    </ul>
                    <!-- end dropdown-user -->
                </li>
                <!-- end main dropdown -->
            </ul>
            <!-- end navbar-top-links -->
        </nav>
        <!-- end navbar top -->