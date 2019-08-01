<div id="loading_wrap" style=' display:none; position:fixed; height:100%; width:100%; overflow:hidden; top:40%; left:45%;'><?php echo $this->Html->image('submit.gif', array('width' => '150px'));?></div>
<style type="text/css">
.StripeElement {
  background-color: white;
  height: 40px;
  padding: 10px 12px;
  border-radius: 4px;
  border: 1px solid transparent;
  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}
.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}
.StripeElement--invalid {
  border-color: #fa755a;
}
.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
.payemt_form{
  background-color: #e3e2dd;
}
.payemt_form_inner{
  max-width: 600px;
  margin: 0 auto;
  padding: 3% 0;
}
div#card-errors {
    text-align: center;
    margin-top: 10px;
    color: #d40000;
    font-weight: 600;
}
button#submit_button {
    padding: 12px 0;
}
button#submit_button_load {
    padding: 12px 0;
}
.stripe_card_number{width: 60%; display: inline-block;}
.stripe_expiry_date{width: 19%; display: inline-block;}
.stripe_card_cvc{width: 19%; display: inline-block;}

@media only screen and (min-width : 678px) {
 .payemt_form_inner {
    padding: 3% 3%;
}
}
</style>

<div class="panel panel-default">
  <div class="panel-body">
    <div class="payemt_form">
      <div class="payemt_form_inner">
        <form action="/stripe/get_response" method="post" id="payment-form">
          <div class="form-row">
              <?php 
            //   if ($this->Session->check('CustomerError')){
            //     echo  "<p class='text-center alert alert-danger'>".$this->Session->consume('CustomerError')."</p>";
            //   }
              
            //   if ($this->Session->check('ChargeError')){
            //     echo  "<p class='text-center alert alert-danger'>".$this->Session->consume('ChargeError')."</p>";
            //   }

            //   if ($this->Session->check('SubscriptionError')){
            //     echo  "<p class='text-center alert alert-danger'>".$this->Session->consume('SubscriptionError')."</p>";
            //   }
              echo $this->Layout->sessionFlash();
              
              
              $plan_id = Configure::read('stripe_plan_id');
              $plan_currency = Configure::read('App.Currency');
              $plan_amount = Configure::read('stripe_plan_amount');
             ?>
            <h3 style="color: #000; text-align: center; padding:15px 0;"><strong><?php echo $plan_amount; ?>円支払い to JTS Board</strong></h3>
            <input type="hidden" name="plan_currency" class="form-control mb-3 StripeElement StripeElement--empty" value="<?php echo $plan_currency; ?>" readonly ><br>
            <input type="text" name="plan_id" class="form-control mb-3 StripeElement StripeElement--empty" value="<?php echo $plan_id; ?>" readonly ><br>
            <input type="text" name="plan_amount" class="form-control mb-3 StripeElement StripeElement--empty" value="<?php echo $plan_amount; ?>" readonly ><br>

            <!-- <input type="text" name="name" class="form-control mb-3 StripeElement StripeElement--empty" ><br> -->
            <input type="text" name="name" class="form-control mb-3 StripeElement StripeElement--empty" value="<?php echo $name; ?>" readonly ><br>
            <!-- <input type="text" name="email" class="form-control mb-3 StripeElement StripeElement--empty" ><br> -->
            <input type="text" name="email" class="form-control mb-3 StripeElement StripeElement--empty" value="<?php echo $email; ?>" readonly ><br>
             <input type="hidden" name="user_id" class="form-control mb-3 StripeElement StripeElement--empty" value="<?php echo $user_id; ?>"  >
            <div id="card-number" class="stripe_card_number"></div>
            <div id="expiry-date" class="stripe_expiry_date"></div>
            <div id="card-cvc" class="stripe_card_cvc"></div>
          </div>
          <br>
          <button id="submit_button">送信する</button>
          <button id="submit_button_load" class="btn btn-primary btn-block mt-4" disabled="disabled" style="display: none;">読み込み中。少々お待ちください。</button>

          <!-- Used to display form errors. -->
            <div id="card-errors" role="alert" ></div>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript">
  // Create a Stripe client.
var stripe = Stripe('<?php echo $stripe_publishable_key ?>');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    lineHeight: '18px',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

var cardNumberElement = elements.create('cardNumber', {
  style: style,
  placeholder: 'カード番号',
});
cardNumberElement.mount('#card-number');

var cardExpiryElement = elements.create('cardExpiry', {
  style: style,
  placeholder: '月月/年年',
});
cardExpiryElement.mount('#expiry-date');

var cardCvcElement = elements.create('cardCvc', {
  style: style,
  placeholder: 'CVV',
});
cardCvcElement.mount('#card-cvc');


document.querySelector('#payment-form button').classList = 'btn btn-primary btn-block mt-4';

// Create an instance of the card Element.
// var card = elements.create('card', {hidePostalCode: true,});

// // Add an instance of the card Element into the `card-element` <div>.
// card.mount('#card-element');

// Handle real-time validation errors from the card Element.
cardNumberElement.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  stripe.createToken(cardNumberElement).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      $('#loading_wrap').css("display","block",true);
      $('#submit_button').css("display","none",true);
      $('#submit_button_load').css("display","block",true);
      $("#submit_button").attr("disabled", true);
      stripeTokenHandler(result.token);
    }
  });
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}
</script>
