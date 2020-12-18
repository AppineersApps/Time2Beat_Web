
<div class="jumbotron text-xs-center">
   <%if $error eq 1%>
  <div class="error-msg"><img src="<%$this->config->item('site_url')%>/public/images/front/error.png"/></div>
  <h1 class="exp-msg"><%$message%></h1>
  <!-- <p class="lead"> Please contact to {%SYSTEM.COMPANY_NAME%} administrator!</p> -->
  
  
  <!-- <h1 class="thnx-msg">This account is already activated!</h1> -->
  
  <div><!--<img src="<%$this->config->item('site_url')%>/public/images/front/checked.png"/>--><!--<i class="fa fa-check main-content__checkmark" id="checkmark"></i>--></div>
  <!--<h1 class="thnx-msg">Thank You!</h1>-->
  <%else%>
  <div class="thnx-img"><img src="<%$this->config->item('site_url')%>/public/images/front/thanks.png"/></div>
  <p class="lead"><%$message%></p>
  <%/if%>
</div>
