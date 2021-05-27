<div class="container">  
  <form id="contact" action="" method="post">
    <h3>Colorlib Contact Form</h3>
    <h4>Contact us for custom quote</h4>
    <fieldset>
      <input placeholder="Your name" type="text" name="name" id="name" tabindex="1" value="" required autofocus>
      <span id="namecheck" style="color: red;" > **Username is missing </span>
    </fieldset>
    <fieldset>
      <input placeholder="Your Email Address" name="email" id="email" type="email" tabindex="2" required>
      <span id="emailcheck" style="color: red;" > **Valid email is missing </span>
    </fieldset>
    <fieldset>
      <input placeholder="Your Phone Number (optional)" name="phone" id="phone" type="tel" tabindex="3">
    </fieldset>
    <fieldset>
      <input placeholder="Your Web Site (optional)" name="site" id="site" type="url" tabindex="4">
      
    </fieldset>
    <fieldset>
      <textarea placeholder="Type your message here...." name="message" id="message" tabindex="5"></textarea>
    </fieldset>
    <?php wp_nonce_field( 'mcf-contact-form' ); ?>
    <input type="hidden" name="action" value="mcf_form">
    <fieldset>
      <button name="submit" type="submit" id="contact-submit" data-submit="...Sending">Submit</button>
    </fieldset>
  </form>
  <h2 id="form_notice"></h2>
</div>