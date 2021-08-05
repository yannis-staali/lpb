window.onload = () => {
//////
var stripe = Stripe('pk_test_51JAYzQJ9WCFPfyUzKuclIRHUMRKUhts3EJGNItbNqaxhbXqVYyGxA4gey81WxJ2957JBqzEIWuAk3KdTG02cn59X00zyXuelQT');
var elements = stripe.elements();
///////
var elements = stripe.elements();
var style = {
  base: {
    color: "#32325d",
  }
};

var card = elements.create("card", { style: style });
card.mount("#card-element");
///////////
card.on('change', ({error}) => {
  let displayError = document.getElementById('card-errors');
  if (error) {
    displayError.textContent = error.message;
  } else {
    displayError.textContent = '';
  }
});
//////////
var form = document.getElementById('payment-form');

form.addEventListener('submit', function(ev) {
  ev.preventDefault();
  // If the client secret was rendered server-side as a data-secret attribute
  // on the <form> element, you can retrieve it here by calling `form.dataset.secret`
 var clientSecret = form.dataset.secret;
 
 var diplayerror = document.getElementById('card-errors');
var nomClient = document.getElementById('cardholder-name').value;

    if(nomClient !== '')
    {
        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
            card: card,
            billing_details: {
                name: nomClient
            }
            }
        }).then(function(result) {
            if (result.error) {

            // Show error to your customer (e.g., insufficient funds)
            diplayerror.innerHTML = result.error.message;
            console.log(result.error.message);
            } else {

            // The payment has been processed!
            if (result.paymentIntent.status === 'succeeded') {

              console.log(result);
              console.log(result.paymentIntent.amount);
              
                //on recupere les valeurs 
                var nom = document.getElementById('nom').value;
                var prenom = document.getElementById('prenom').value;
                var telephone = document.getElementById('telephone').value;
                var email = document.getElementById('email').value;
                var adresse = document.getElementById('adresse').value;
                var ville = document.getElementById('ville').value;
                var departement = document.getElementById('departement').value;
                var codepostal = document.getElementById('codepostal').value;
                var pays = document.getElementById('pays').value;
                var idclient = document.getElementById('prodId').value;

                var idCommande = result.paymentIntent.id;

                var totalCout = result.paymentIntent.amount / 100 ;

                //on lance l'ajax pour envoyer les valeurs
                var xhr = new XMLHttpRequest();

                xhr.onload = function() {

                    window.location = "index.php?page=thanks";
                }
                
                xhr.open("POST", "Controleur/traitementpay.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("paiement=ok&nom="+nom+"&prenom="+prenom+"&telephone="+telephone+"&email="+email+"&adresse="+adresse+"&ville="+ville+"&departement="+departement+"&codepostal="+codepostal+"&pays="+pays+"&idCommande="+idCommande+"&idUser="+idclient+"&totalCout="+totalCout);

                // Show a success message to your customer
                // There's a risk of the customer closing the window before callback
                // execution. Set up a webhook or plugin to listen for the
                // payment_intent.succeeded event that handles any business critical
                // post-payment actions.
            }
            }
        });
    }
    else{
        diplayerror.innerHTML = 'Veuillez renseigner le titulaire de la carte';
    }

  
});

}