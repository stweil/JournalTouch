<?php
$mylist = $_POST;
/* do we have GET parameters? (currently only used for contact) */
$myaction = $_GET;
/* load classes */
require 'sys/class.CheckoutActions.php';
require 'sys/class.GetUsers.php';
require 'sys/PHPMailer/PHPMailerAutoload.php';
/* setup methods & objects */
$email = new PHPMailer(true);
$action = new CheckoutActions();
?>
<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo __('MPI JournalTouch') ?> - <?php echo __('Checkout') ?></title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/local.css" />
    <link rel="stylesheet" href="css/local-print.css" media="print" />
    <link rel="stylesheet" href="css/media.css" />
    <link rel="stylesheet" href="foundation-icons/foundation-icons.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/local/simpleCart.custom.js"></script> 
    <script src="js/vendor/jquery.unveil.min.js"></script>
    <script src="js/local/conduit.js"></script>
  </head>
  <body>

    <!-- Navigation -->

		<nav class="top-bar" data-topbar>
			<ul class="title-area" style="background: url('img/bgcoll-logo-s.png') no-repeat left top;">
				<!-- Title Area -->
				<li class="name">
					<h1><?php echo __('JournalTouch <em><strong>beta</strong></em> - a library service') ?></h1>
				</li>
				<li class="toggle-topbar"><a class="i fi-arrow-left" href="index.php">&nbsp;Back</a></li>
			</ul>
			
			<section class="top-bar-section">
				<!-- Right Nav Section -->
				<ul class="right">
					<li class="divider"></li>
					<li><a class="i fi-arrow-left" href="index.php">&nbsp;<?php echo __('Back to journal selection') ?></a></li>
				</ul>
			</section>
		</nav>
		
		<!-- End Top Bar -->

    <!-- Contact form (only when called with GET-parameter -->
		<?php if($_GET && $_GET['action'] == 'contact') { ?>
		<div class="row">
			<div class="small-12 columns" style="padding-top:20px">
				<h1><?php echo __('Send your feedback to the library') ?></h1>
			</div>
		</div>
			<form name="Feedback" method="post" action="<?php print $_SERVER['REQUEST_URI']; ?>">

				<div class="row">
					<div class="small-12 columns">
						<label><?php echo __('Your e-mail') ?>

							<?php 
								 $userHandle = new GetUsers();
								 $users = $userHandle->getUsers();
							     if ($users == false) {  
							       print '<input name="username" placeholder="'. __('your username').'" type="text"/>';
                   } else {                             
							       print '<select name="username">';
								     foreach ($users as $name=>$pw) {
								       print '<option>'.$name.'</option>';
								     } 
								     print'	</select>';
							     }
                ?>

						</label>
						<small id="errorUsername" class="error" style="display:none"><?php echo __('please choose a name') ?></small>

					</div>
				</div>

				<div class="row">
					<div class="small-12 columns">
						<label><?php echo __('Your feedback message') ?>
							<textarea name="message" placeholder="<?php echo __('if you have any comments for us please put them here!'); ?>"><?php if (isset($_GET['message'])) { print $_GET['message']; } ?></textarea>
						</label>
					</div>
				</div>

				<div class="row">
					<div class="small-12 columns">
						<!-- flag for POST (first page view contains POST values from cart; BEWARE: sending the form overwrites the values -->
						<input type="hidden" name="mailer" value="true"/>
						<input type="hidden" name="feedback" value="true"/>
						<input class="radius button large right submit" type="submit" value="Submit">
					</div>
				</div>
				
			</form>
		<?php }	else { ?>
		<!-- End Contact form -->

		<div class="row" id="actionGreeter">
			<div class="small-12 columns" style="padding-top:20px">
				<h1><?php echo __('I want to...') ?></h1>
			</div>
		</div>
		
		<!-- End Header and Nav -->

		<div id="actions" class="row">
			<div class="small-12 text-center columns">
				<a id="printArticles" href="#" class="radius button large"><i class="fi-print"></i> <?php echo __('View &amp; Print') ?></a>
				<!--<a id="saveArticles" href="#" class="radius button large disabled"><i class="fi-save"></i> Save/Export</a>-->
				<?php if(empty($_POST['mailer'])) { ?>
			  <a id="sendArticlesToUser" href="#" class="button radius large mailForm"><i class="fi-mail"></i> <?php echo __('Send to my mailbox') ?></a>
				<a id="sendArticlesToLib" href="#" class="button radius large mailForm"><i class="fi-mail"></i> <?php echo __('Send to library to get PDFs') ?></a>
				<?php } else { ?>
				<a id="sendDone" href="#" class="radius button large success"><i class="fi-check"></i> <?php echo __('You already sent your files') ?> </a>
				<?php } ?>
				<a id="resetActions" href="#" class="radius button large reset" style="display:none"><i class="fi-arrow-left"></i> <?php echo __('choose another option') ?></a>
				<!--<a id="emptyCart" href="#" class="radius button large alert"><i class="fi-arrows-out"></i> Clear Data and Logout</a>-->
				<a id="emptyCartConfirm" class="radius large alert button" data-reveal-id="emptyConfirm"><i class="fi-arrows-out"></i> <?php echo __('Clear Data and Logout') ?></a>
			</div>  
		</div>

		<!-- Security confirmation on delete -->
		<div id="emptyConfirm" class="reveal-modal" data-reveal>
			<h3><?php echo __('Do you really want to empty your basket?') ?></h3>
			<a id="emptyCart" href="#" class="radius small alert button close-reveal-modal"><i class="fi-trash"></i> <?php echo __('OK, empty my basket!') ?></a>
			<a id="DoNotemptyCartButton" class="radius small success button close-reveal-modal"><i class="fi-trash"></i> <?php echo __('No, keep basket!') ?></a>
		</div>

		<?php } /* end GET query */ ?>

		<div id="emptyCartSuccess" class="row invisible">
			<div class="small-12 text-center columns">
				<div data-alert class="alert-box success radius">
					<i class="fi-check"></i> <?php echo __('Your articles have been successfully deleted! You will automatically be taken to the start page.') ?>
					<a href="#" class="close">&times;</a>
				</div>
			</div>
		</div>
		
		<div id="actionsResultBox">
<!-- Start Mailer Response -->
		<?php

    if(isset($_POST['mailer']))
    {
    // if we have already sent an e-mail, read again from POST
    if (empty($file)) {$file = $_POST['file'];}

    /* pass the PHPMailer object & save the return value (success or failure?) */
	/* is it feedback? */
    if ($_POST['feedback']) {
        $mailerResponse = $action->sendFeedback($email);
    } else {
        $mailerResponse = $action->sendArticlesAsMail($file, $email);
    }
    /* error handling */
    if ($mailerResponse == "OK") {
		/* default, everything is alright */
    ?>

   <div class="row">
	   <div class="small-12 text-center columns">
		   <div data-alert class="alert-box success radius">
				 <i class="fi-check"></i>&nbsp; <?php echo __('Your message has been successfully sent!') ?>  <a href="#" class="close">&times;</a>
		   </div>
		 </div>
	 </div>
	 
	  <?php
    } else {
		/* something went wrong */
		?>
    <div id="actions" class="row">
			<div class="small-12 text-center columns">
				<div data-alert class="alert-box warning radius">
          <i class="fi-x"></i>&nbsp; <?php print $mailerResponse;?>  <a href="#" class="close">&times;</a>
				</div>
		  </div>
	  </div>


<!-- End Mailer Response -->

<!-- Start Mailer  -->

		<?php
    }

    } else { /* if no mail has been sent yet */

    /* Mailer: show Form */

    /* save selection by default */
			 if (empty($_GET)) { // do not show with any GET parameters
                 $action->saveArticlesAsCSV($mylist);
             }
    }

    ?>

    <div id="mailForm" style="display:none">
			<form name="Request" method="post" action="<?php print $_SERVER['REQUEST_URI']; ?>">

				<div class="row sendArticlesToLib sendArticlesToUser">
					<div class="small-12 columns">
						<label><?php echo __('Your e-mail') ?>

							<?php 
								 $userHandle = new GetUsers();
								 $users = $userHandle->getUsers();
							     if ($users == false) {  
							       print '<input name="username" placeholder="'. __('your username').'" type="text"/>';
                   } else {                             
							       print '<select name="username">';
								     foreach ($users as $name=>$pw) {
								       print '<option>'.$name.'</option>';
								     } 
								     print'	</select>';
							     }
                ?>

						</label>
						<small id="errorUsername" class="error" style="display:none"><?php echo __('please choose a name') ?></small>

					</div>
				</div>

				<div class="row sendArticlesToUser">
					<div class="small-12 columns">
						<label><?php echo __('Attach citations?') ?></label><!--<small class="error">beware: experimental feature</small>-->
						<input type="radio" id="attachFileEndnote" name="attachFile" value="endnote"><label for="attachFileEndnote">Endnote</label>
						<input type="radio" disabled id="attachFileBibTeX" name="attachFile" value="bibtex"><label for="attachFileBibTeX"><s>BibTeX</s></label>
						<input type="radio" id="attachFileCSV" name="attachFile" value="csv"><label for="attachFileBibTeX">CSV</label>
					</div>
				</div>

				<div class="row sendArticlesToLib">
					<div class="small-12 columns">
						<label><?php echo __('Your message') ?>
							<textarea name="message" placeholder="<?php echo __('if you have any comments for us please put them here!'); ?>"></textarea>
						</label>
					</div>
				</div>

				<div class="row sendArticlesToLib sendArticlesToUser">
					<div class="small-12 columns">
						<!-- flag for POST (first page view contains POST values from cart; BEWARE: sending the form overwrites the values -->
						<input type="hidden" name="mailer" value="true"/>
						<input type="hidden" name="file" value="<?php print $file; ?>"/>
						<input type="hidden" name="action" value=""/><!-- this one is important and is set from conduit.js! -->
						<input class="radius button large right submit" type="submit" value="Submit">
					</div>
				</div>
				
			</form>
		</div>


<!-- End Mailer -->

<!-- Start View -->

		<div id="viewBox" class="printArticles" style="display:none">

			<div class="row">
				<div class="small-12 columns print">
					<a href="javascript:window.print();" class="radius button large"><i class="fi-print"></i></a>
				</div>
			</div>

			<div class="row">
				<div class="small-12 columns">

		<?php if (empty($_GET)) { // do not show with any GET parameters
						 // if we have already sent an e-mail, read again from POST
						if (empty($file)) {$file = $_POST['file'];}
		        print $action->getArticlesAsHTML($file);
					  }
					?>
				</div>
			</div>

<!-- End View -->

<!-- Start Save/Export -->
<!-- not in use -->
<!--
			<div id="saveDialog" style="display:none">

				<div class="row">
					<div class="small-12 columns">

					</div>
				</div>
-->
<!-- End Save/Export -->
			</div>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
  </body>
</html>

