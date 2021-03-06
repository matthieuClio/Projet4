<?php
	require('../core/Bdd_connexion.php');
	require('../src/model/Billets.php');

	class BackofficeBillet {

		private $bddObj;
		private $ticketObj;
		private $connexion;
		private $pseudo;

		private $nameTicketPost;
		private $textTicketPost;

		// Constructor
		function __construct() {
			// Object
		 	$this->bddObj = new bdd_connexion();
		 	$this->ticketObj = new Ticket();
		 	$this->connexion = $this->bddObj->Start();

		 	$this->pseudo = $_SESSION['pseudo_user'];

		 	if(!empty($_POST['submit_connexion'])) {
		 		$this->nameTicketPost = $_POST['name_ticket'];
		 		$this->textTicketPost = $_POST['text_textarea'];
		 	}
	    }

		function addTicket() {
			if(!empty($_POST['submit_connexion'])) {

				$this->nameTicketPost = $_POST['name_ticket'];
				$this->textTicketPost = $_POST['text_textarea'];

				// Check name
				$nameTicket = $this->ticketObj->checkNameTicket($this->nameTicketPost, $this->connexion);

				if(empty($nameTicket) && !empty($this->textTicketPost)) {
					// Message registration
					$_SESSION['popup'] = 'Billet enregistré';

					// Check user id
					$userId = $this->ticketObj->checkUserId($this->pseudo, $this->connexion);

					// Save the ticket on database
					$this->ticketObj->insertTicket($this->nameTicketPost, $userId, $this->textTicketPost, $this->connexion);
				}
				else if(!empty($nameTicket)) {
					$_SESSION['popup'] = 'Nom du billet déjà pris';
				}
				else if(empty($this->textTicketPost)) {
					$_SESSION['popup'] = 'Le texte du billet est vide';
				}
			}
		} // End function addTicket

		function displayTicketView() {
			// Function DisplayTicket of Ticket object
			$request = $this->ticketObj->displayTicket($this->connexion);

			return $request;
		}

	} // End class BackofficeBillet

	// Object BackofficeBillet
	$objectBackofficeBillet = new BackofficeBillet();
	$objectBackofficeBillet->addTicket();
	$request = $objectBackofficeBillet->displayTicketView();

	// Load the view
	require('../src/view/back/backoffice_billet_view.php');
?>