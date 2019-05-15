<div class="sbox">
  <div class="sbox-title">
      
        <h3 ><?php  echo CNF_APPNAME .'<small> '. CNF_APPDESC .' </small>';?></h3>
        
  </div>
  <div class="sbox-content">
  <div class="text-center">
    <img src="<?php echo base_url().'sximo/themes/mango/img/logo4.png';?>" width="90" height="90" />
  </div>  
    
  <?php echo form_open('user/postlogin'); ?>
  
  <div class="form-group has-feedback">
    <label> Email Address  </label>
    <input type="text" name="email" value="<?php echo $email ?>" class="form-control" placeholder="Email Address">
    <i class="fa fa-envelope form-control-feedback"></i>
  </div>
  
  <div class="form-group has-feedback">
    <label> Password  </label>
    <input type="password" name="password" value="" class="form-control"  placeholder="Password">
    <i class="icon-lock form-control-feedback"></i>
  </div>  
  <?php if( CNF_RECAPTCHA ) { ?>
  <div class="form-group has-feedback">
    <label>Human Challenge <span class="asterix">*</span></label>
    <?php echo $recaptcha_html ?>
    <i class="icon-lock form-control-feedback"></i>
  </div>
  <?php } ?>
  
  <?php if( CNF_CICAPTCHA ) { ?>
  <div class="form-group has-feedback">
    <label>Human Challenge <span class="asterix">*</span></label>
  <?php echo $cicaptcha_html;?>
  </div>
  <?php } ?>
  
  
  <div class="form-group  has-feedback text-center" style=" margin-bottom:20px;" >
        
      <button type="submit" class="btn btn-primary btn-sm btn-block" > Sign In</button>
           

    
     <div class="clr"></div>
    
  </div>  
 <?php echo form_close();?>  
 
  <p class="text-center"><a id="or"  href="#"><small>Forgot password?</small></a></p>
  
  
   <div >
   <form class="form-vertical box" action="<?php echo site_url('user/saveRequest'); ?>" id="fr" method="post" style="margin-top:20px; display:none;margin-bottom:30px;" >

   
       <div class="form-group has-feedback">
     <div class="">
      <label> Email Address </label>
       <input type="text" name="credit_email" value="" class="form-control">
      <i class="icon-envelope form-control-feedback"></i>
    </div>   
    </div>
    <div class="form-group has-feedback">        
          <button type="submit" class="btn btn-danger ">Reset My Password  </button>        
      </div>
    
    <div class="clr"></div>
  
  </form>
  </div>
  
  
  <!-- <p class="text-muted text-center">Do not have an account?</p>         -->
    
    <p style="padding:10px 0" class="text-center">
       </p>      
  </div>      
    
  

  <div class="clr"></div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
  $('#or').click(function(e){
    e.preventDefault();
    $('#fr').toggle();
  });
});
</script>