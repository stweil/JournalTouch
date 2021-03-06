<?php
/**
 * @brief   Get journals and display everything nicely
 *
 * @todo
 * - Handle the sticky stuff better; maybe this way
 *    <div id="stickyleft" class="small-1 columns"></div>
 *    <div id="journals" class="small-10 columns"></div>
 *    <div id="stickyright" class="small-1 columns"></div>
 * - add conditional parts to part.tpl files or something like that
 *
 * @author Daniel Zimmel <zimmel@coll.mpg.de>
 * @author Tobias Zeumer <tzeumer@verweisungsform.de>
 */
ob_start();
require 'sys/class.ListJournals.php';
/* setup methods & objects */
$lister = new ListJournals();
$journals = $lister->getJournals();
$journalUpdates = $lister->getJournalUpdates();
?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo __('MPI JournalTouch') ?></title>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" href="css/local.css" />
    <link rel="stylesheet" href="css/media.css" />
    <link rel="stylesheet" href="foundation-icons/foundation-icons.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
<body>

<!-- Navigation -->
<div class="fixed">
  <nav class="top-bar" data-topbar="" data-options="is_hover: false">
    <ul class="title-area">
      <!-- Title Area -->
      <li class="name"><h1><?php echo __('JournalTouch <em><strong>beta</strong></em> - a library service') ?></h1></li>
      <li class="toggle-topbar menu-icon"><a href="#"><span><?php echo __('menu') ?></span></a></li>
    </ul>
    <section class="top-bar-section">
      <ul class="hide-for-small">
        <li><a href="#" id="aboutLink" data-reveal-id="about" class="button radius"><i class="fi-info"></i> <?php echo __('about') ?></a></li>
      </ul>

      <!-- Right Nav Section -->
      <ul class="right">
        <li class="divider"></li>
        <?php if (count($lister->tagcloud) > 1 && $lister->prefs->show_tagcloud) { ?>
        <li><a href="#" id="myTags" data-reveal-id="tagsPopover"><i class="switcher fi-pencil"></i>&#160;<?php echo __('Tags') ?></a>
        </li><?php } ?><?php if ($lister->filters) { /* show filters only if set */?>
        <li class="has-dropdown">
          <a id="filter-view" href="#"><i class="fi-filter"></i>&#160;<?php echo __('filter') ?></a>
          <ul class="dropdown">
            <li><a class="filter" id="filter-reset" href="#"><i class="fi-refresh"></i>&#160;<?php echo __('show all') ?></a></li>
            <li><a class="filter" id="topJ" href="#"><i class="fi-star"></i>&#160;<?php echo __('MPI favorites') ?></a></li>
            <li><a class="filter" id="new-issue" href="#"><i class="fi-burst-new"></i>&#160;<?php echo __('new issues') ?></a></li>
            <?php
                /* read all filters from the config file */
                  foreach ($lister->filters as $key=>$f) {
                  print '<li><a class="filter" id="filter-'.$key.'" href="#">'.$f.'</a></li>';
                }
            ?>
          </ul>
        </li>
        <?php } ?>
        <li><a id="switch-view" href="#"><i class="switcher fi-list"></i><span>&#160;<?php echo __('list view') ?></span></a></li>
        <li><a href="#" id="myArticles" data-reveal-id="cartPopover"><i class="fi-shopping-bag"></i>&#160;<?php echo __('my basket') ?>(<span class="simpleCart_quantity"></span>)</a></li>
        <li class="divider"></li>
        <?php
          // Nasty and only works with two languages ;)
          if (isset($_GET['lang'])) {
            $href_lang = ($_GET['lang'] == 'de_DE') ?  'en_US' : 'de_DE';
          }
          else {
            $href_lang = 'en_US';
          }
          echo "<li><a id=\"switch-language\" href=\"index.php?lang=$href_lang\"><img src=\"locale/$href_lang.gif\" /></a></li>";
        ?>
      </ul>
    </section>
  </nav>
</div>
<!-- End Navigation -->

<!-- Left and right side elements -->
<!--  Note
      1. For letterbox see setLetterBox() in js/local/conduit.js
      2. Most (re-)sizing is done via css/media.css               -->

  <!-- Make a sticky basket -->
  <a href="#" id="stickyBasket" class="button radius show-for-xlarge-up" data-reveal-id="cartPopover"><i class="fi-shopping-bag"></i>&#160;<?php echo __('Send articles') ?></a>

  <!-- Make a sticky GoUp -->
  <a href="#" id="stickyGoUp" class="button round"><i class="fi-arrow-up"></i></a>
<!-- END Left and right side elements -->


<!-- About page -->
<div id="about" class="reveal-modal" data-reveal="">
  <div class="row">
    <div class="small-12 medium-12 large-12 columns left">
      <h2><?php echo __('About') ?></h2>
      <p>
        <?php
          $lib_teaser = __("<em>JournalTouch</em> is the <strong>PLACEHOLDER's</strong> alerting service for newly published journal issues.");
          $lib_teaser = str_replace('PLACEHOLDER', ' '.$lister->prefs->lib_name, $lib_teaser);
          echo $lib_teaser;
        ?>
      </p>
      <p>
        <?php echo __('It\'s easy &dash; select a journal and add interesting articles to your shopping basket. If there is an abstract
                      available, it will be indicated with an extra button.
                      When you are finished, click on your basket to check out.
                      You can now send the article information to your e-mail address, send a
                      request for the PDF files to the library, or view/save it as a
                      list. Export for citation management systems like Endnote is also available. <br/>
                      The list of journals is a selection of the journals licensed to the library.')
        ?>
      </p>
    </div>
  </div>
  <div class="row">
    <div class="small-8 medium-9 large-9 columns left">
      <p>
        <?php echo __('If a journal is missing, please let us know and we will add it to the list.<br/><br/> <strong><em>JournalTouch</em> is actively being developed by the library team</strong>.') ?>
        <br /><?php echo __('Tables of contents are provided by <strong>CrossRef</strong> and <strong>JournalTocs</strong>.') ?>
      </p>
    </div>
    <div class="small-4 medium-3 large-3 columns right">
      <ul class="inline-list right">
        <li><a class="button radius" href="checkout.php?action=contact"><i class="fi-comment"></i> <?php echo __('Get in touch!') ?></a></li>
      </ul>
    </div>
  </div>
  <a class="close-reveal-modal button radius alert">×</a>
</div>
<!-- End Header and Nav -->


<!-- start external link -->
<div id="externalPopover" class="reveal-modal" data-reveal="">
  <h3><?php echo __('External Source') ?></h3>
  <a class="close-reveal-modal button radius">×</a>
  <iframe src="" id="externalFrame" width="90%" height="100%" scrollbars="yes"></iframe>
</div>
<!-- end external link -->


<!-- start Tagcloud -->
<?php if (count($lister->tagcloud) > 1 && $lister->prefs->show_tagcloud) { ?>
<div id="tagsPopover" class="reveal-modal" data-reveal="">
  <h3><?php echo __('Tagcloud') ?></h3>
  <a class="close-reveal-modal button radius">×</a>
  <p><a class="filter" id="filter-reset" href="#"><i class="fi-refresh"></i>&#160;<?php echo __('show all') ?></a></p>
  <?php echo $lister->getTagcloud(); ?>
</div>
<?php } ?>
<!-- end Tagcloud -->


<div id="cartPopover" class="reveal-modal" data-reveal="">
  <div class="simpleCart_items"></div>
  <div id="cartData" class="clearfix"></div>
  <div id="shelfIsEmpty" style="display:none">
    <i class="fi-alert"></i> <?php echo __('Your basket is empty.') ?>
  </div>
  <div id="popoverButtons" class="clearfix">
    <a id="checkOutButton" href="javascript:;" class="simpleCart_checkout radius small success button"><i class="fi-share"></i> <?php echo __('Send/Save my articles') ?></a>
    <!--<a id="emptyCartButton" href="javascript:;" class="simpleCart_empty radius small alert button"><i class="fi-trash"></i> Empty my basket</a>-->
    <a id="emptyConfirmButton" class="radius small alert button" data-reveal-id="emptyConfirm"><i class="fi-trash"></i> <?php echo __('Empty my basket') ?></a>
    <a class="close-reveal-modal button radius">×</a>
  </div>
</div>
<!--End #cartPopover-->


<!-- Security confirmation on delete -->
<div id="emptyConfirm" class="reveal-modal" data-reveal="">
  <h3><?php echo __('Do you really want to empty your basket?') ?></h3>
  <a id="emptyCartButton" href="javascript:;" class="simpleCart_empty radius small alert button close-reveal-modal"> <i class="fi-trash"></i> <?php echo __('OK, empty my basket!') ?></a>
  <a id="DoNotemptyCartButton" class="radius small success button close-reveal-modal"><i class="fi-trash"></i> <?php echo __('No, keep basket!') ?></a>
</div>


<!-- First Band (Slider) -->
<?php if ($lister->prefs->show_orbit) { ?>
<div id="view-orbit">
  <div class="row">
    <div class="large-12 columns" style="padding-top:20px">
      <h3><?php echo __('The newest editions') ?></h3>
      <?php // print date('M d Y',strtotime('-7 day'))." - " .date('M d Y',strtotime('today'));  ?>
    </div>
  </div>
  <div class="row">
    <hr />
    <div class="medium-3 large-2 columns hide-for-small logo">&#160;</div>
    <div class="medium-5 large-7 small-11 columns slideshow-wrapper">
      <div class="preloader"></div>
      <ul id="myorbit" data-orbit="" data-options="animation_speed: 1000;timer_speed: 3000;bullets: false">
        <?php
          /* /\* see Class setup *\/ */
          foreach ($journals as $j) {
            if (!empty($j['topJ'])) {
              echo '<li data-orbit-slide="headline">';
              echo '<img class="issn getTOC" id="'.$j['id'].'" src="'.$j['img'].'"/>';
              echo '<div class="orbit-caption">'.$j['title'].'</div>';
              echo '</li>';
            }
          }
        ?>
      </ul>
    </div>
    <div class="medium-4 large-3 columns">
      <div class=" panel radius callout" id="filterPanel" style="display:none">
        <i class="fi-filter"></i>&#160; Filter active:<span id="filterPanelFilter"></span>
      </div>
    </div>
  </div>
</div>
<hr />
<?php } ?>


<!-- Three-up Content Blocks -->
<div class="row">
  <div id="TOCbox" class="small-10 medium-10 large-12 columns">
    <!-- A-Z button bar -->
    <div class="button-bar alphabet">
      <ul class="button-group radius">
        <?php
          $alphas = range('A', 'Z');
          foreach ($alphas as $letter) {
            echo '<li><a href="#" class="tiny button secondary">'.$letter.'</a></li>';
          }
        ?>
      </ul>
    </div>
  </div>
</div>


<div class="row">
  <div class="panel radius callout" id="filterPanel" style="display:none">
  <i class="fi-filter"></i>&#160; <?php echo __('Filter active:') ?>
  <span id="filterPanelFilter"></span></div>
</div>

<!-- Search form -->
<form id="search-form">
  <div class="row">
    <div class="small-9 medium-10 large-12 columns">
      <fieldset>
        <label class="error">
          <input id="search" type="text" placeholder="<?php echo __('Search journal') ?>" />
        </label>
        <small id="noresults" class="error" style="display:none">
          <?php echo __('No journals found') ?>
        </small>
      </fieldset>
    </div>
  </div>
</form>
<!-- End Search form -->


<!-- show updates - this is only some example code how to include the latest journal updates -->
<!-- please refer to the README on how to use it -->
<!--
<div class="row">
  <div id="updateBox" class="small-12 columns">
    <ul>
      <?php
        if (!empty($journalUpdates)) {
          foreach ($journalUpdates as $j) {
            print '<li><a href="#">' . $j['title'] . '</a> (last update <time class="timeago" datetime="'.$j['timestr'].'">' . $j['timestr'] . '</time>)</li>';
          }
        }
      ?>
    </ul>
  </div>
</div>
-->

<!-- Version 2: List -->
<div id="view-accordion" class="row invisible">
  <div class="small-12 columns">
    <h3><?php echo __('Browse all journals from A to Z (list view):') ?></h3>
    <dl class="accordion" data-accordion="">
      <?php
        /* see Class setup */
        foreach ($journals as $j) {
          /* convert found date of last update in the data to a timestring (gets evaluated with jquery.timeago.js) */
          $timestring = date('c', strtotime($j['date'])); //
          $wF = '<time class="timeago" datetime="'.$timestring.'">'.$timestring.'</time>';
          $new_issues = ($j['new']) ? 'new-issue' : '';

          echo '<dd class="search-filter filter-'.$j['filter'].' '.$j['tags'].' '.$j['topJ'].' '.$new_issues.'">';
          echo '<a id="'.$j['id'].'" class="getTOC accordion '.$j['id'].'" href="#issn'.$j['id'].'">';
          echo ($new_issues) ? ' <i class="fi-burst-new large"></i>' : "";
          echo $j['title'];
          echo ($new_issues) ? ' <span class="fresh">['.__("last update") .' '. $wF . ']</span>' : "";
          echo '</a>';
          echo '<div id="issn'.$j['id'].'" class="content"><div class="toc preloader"></div></div>';
          echo '</dd>';
        }
      ?>
    </dl>
  </div>
</div>


<!-- Version 3: Grid -->
<!-- Thumbnails -->
<div id="view-grid">
  <div class="row">
    <div class="small-10 columns">
      <h3><?php echo __('Browse all journals from A to Z (grid view):') ?></h3>
    </div>
  </div>
  <div class="row" id="journalList">
    <?php
      /* see Class setup */
      foreach ($journals as $j) {
        /* convert found date of last update in the data to a timestring (gets evaluated with jquery.timeago.js) */
        $timestring = date('c', strtotime($j['date'])); //
        $wF = '<time class="timeago" datetime="'.$timestring.'">'.$timestring.'</time>';

        $meta = false;

        $jtoc = 'http://www.journaltocs.ac.uk/index.php?action=tocs&issn='.$j['issn'];
        $meta = (($j['metaGotToc']) ? '<a href="'.$jtoc.'" class="button small radius popup"><i class="'.$j['metaGotToc'].'"></i> '.__('TOC').'</a>' : "");
        $link = ($lister->prefs->inst_service) ? $lister->prefs->inst_service.$j['issn'] : '';
        $meta .= (($j['metaOnline'] && $link) ? '<a href="'.$link.'" class="button small radius popup"><i class="'.$j['metaOnline'].'"></i> '.__('Library').'</a>': "<br />");
        $meta .= (($j['metaWebsite']) ? '<a href="'.$j['metaWebsite'].'" class="button small radius popup"><i class="fi-home"></i> '.__('Journal').'</a>': "<br />");
        $print_meta = (($j['metaPrint']) ? 'class="'.$j['metaPrint'].'"' : "");
        $meta .= (($j['metaShelfmark']) ? ' <span class="button small radius"><i '.$print_meta.'> '.$j['metaShelfmark'].'</i></span>' : "&nbsp;");
        $new_issues = ($j['new']) ? 'new-issue' : '';
        $len_title = strlen($j['title']);
        $nbr_title = ($len_title < 100) ? $j['title'] : substr($j['title'], 0, strrpos($j['title'], ' ', $len_title * -1 + 100)).' ...';

        echo '<div class="search-filter large-4 medium-5 small-12 columns div-grid filter-'.$j['filter'].' '.$j['tags'].' '.$j['topJ'].' '.$new_issues.'">';
        echo '<img class="getTOC grid '.$j['id'].'" id="'.$j['id'].'" src="img/lazyloader.gif" data-src="'.$j['img'].'">';
        echo ($new_issues) ? '<i class="fi-burst-new large"></i>' : "";
        /* preload $meta here; toggle when the TOC is fired into the Reveal window (see js) */
        echo (($meta && $lister->prefs->show_metainfo) ? '<span class="metaInfo" style="display:none"><div>'.$meta.'</div></span>' : "");
        echo '<div id="issn'.$j['id'].'" class="getTOC grid panel content">';
        echo '<h5 title="'.$j['title'].'">'.$nbr_title.'</h5>';
        echo ($new_issues) ? '<h6 class="subheader"> <span class="fresh">['.__("last update") .' '. $wF . ']</span> </h6>' : "";
        echo '</div></div>';

      }
    ?>
  </div>
  <!-- End Thumbnails -->

  <!-- Box für Toc-Inhalte, z.B. genutzt von Grid (s. js) -->
  <div id="tocModal" class="reveal-modal xlarge" data-reveal="">
    <div class="toc preloader"></div>
    <!-- This alert box will be switched on if something goes wrong (see conduit.js) -->
    <div data-alert="" id="tocAlertBox" class="alert-box warning invisible">
      <span id="tocAlertText"><?php echo __('Something seems to be wrong with the network') ?></span>
      <a class="button radius" href="checkout.php?action=contact&amp;message=Feed%20error%20report&amp;body=Error%20report%20from%20JournalTouch%20for%20ISSN:%200000-0000"><i class="fi-mail"></i>&#160;<?php echo __('Please notify us!') ?></a>
    </div>
    <!-- This alert box will be switched on if no tocs are found (see conduit.js) -->
    <div data-alert="" id="tocNotFoundBox" class="alert-box info invisible">
      <span id="tocAlertText"><?php echo __('No table of contents found! Are you interested in this title?') ?></span>
      <a class="button radius" href="checkout.php?action=contact&amp;message=The%20table%20of%20contents%20for%20this%20journal%20seems%20to%20be%20missing%20for%20ISSN:%200000-0000"><i class="fi-comment"></i> <?php echo __('Please notify us!') ?></a>
    </div>
    <a class="close-reveal-modal button radius alert">×</a>
  </div>
</div>


<footer>
  <div class="row">
    <div class="small-12 columns">
      <div class="large-6 columns">
        <p>
        <em>JournalTouch</em> © 2014 MPI Collective Goods Library, Bonn</p>
        <p><?php echo __('Tables of contents provided by <a href="http://www.crossref.org">CrossRef</a> and <a href="http://www.journaltocs.ac.uk/">JournalTocs</a>') ?></p>
      </div>
      <div class="large-6 columns">
        <ul class="inline-list right">
          <li><a class="button radius" href="checkout.php?action=contact"><i class="fi-comment"></i> <?php echo __('Get in touch!') ?></a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>


<!-- a fancy screensaver when screen is idle (see css for switching) -->
<?php if ($lister->prefs->show_screensaver) { ?>
<div id="screensaver" style="display:none">
  <div class="row">
    <div class="small-12 medium-12 large-12 columns left">
      <h1><?php echo __('Touch me!') ?></h1>
      <h2><?php echo __('What is this?') ?></h2>
      <p>
        <?php
          $lib_teaser = __("<em>JournalTouch</em> is the <strong>PLACEHOLDER's</strong> alerting service for newly published journal issues.");
          $lib_teaser = str_replace('PLACEHOLDER', ' '.$lister->prefs->lib_name, $lib_teaser);
          echo $lib_teaser;
        ?>
      </p>
      <p>
        <?php echo __('It\'s easy &dash; select a journal and add interesting articles to your shopping basket. If there is an abstract
                      available, it will be indicated with an extra button.
                      When you are finished, click on your basket to check out.
                      You can now send the article information to your e-mail address, send a
                      request for the PDF files to the library, or view/save it as a
                      list. Export for citation management systems like Endnote is also available. <br/>
                      The list of journals is a selection of the journals licensed to the library.') ?>
      </p>
    </div>
  </div>
  <div class="row">
    <div class="small-8 medium-9 large-9 columns left">
      <p>
        <?php echo __('If a journal is missing, please let us know and we will add it to the list.') ?>
        <br />
        <br /><?php echo __('<strong><em>JournalTouch</em> is actively being developed by the library team.</strong>') ?>
        <br /><?php echo __('Tables of contents are provided by <strong>CrossRef</strong> and <strong>JournalTocs</strong>.') ?>
      </p>
    </div>
  </div>
  <div class="row"><h1><?php echo __('Touch the screen to get started...') ?></h1></div>
  <div class="row"><p class="text-center"><img src="img/bgcoll-logo.png"></img></p></div>
</div>
<?php } ?>
<!-- end screensaver -->


<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script src="js/local/simpleCart.custom.js"></script>
<script src="js/vendor/jquery.unveil.min.js"></script>
<script src="js/vendor/waypoints.min.js"></script>
<script src="js/vendor/jquery.timeago.js"></script>
<script src="js/local/conduit.js"></script>
<script src="js/vendor/jquery.quicksearch.min.js"></script>
<script>
simpleCart({
	checkout: {
		type: "SendForm",
		url: "checkout.php"
	},
	cartColumns: [
		{ attr: "name" , label: "Name" } ,
		{ view: "remove" , text: "<?php echo __('remove') ?> <i class=\"fi-trash\"></i>" , label: false }
	]
});

simpleCart.bind( 'beforeRemove' , function(){
  if ($(".row-1").length == false) {
	  $("#shelfIsEmpty").show();
	  $('#checkOutButton, #emptyCartButton, #emptyConfirmButton').hide();
      // remove class to button (for CSS formatting)
      $('#myArticles').removeClass('full');
  } else {
	  $('#checkOutButton, #emptyCartButton, #emptyConfirmButton').show();
	}
});

simpleCart.bind( 'afterAdd' , function(){
   // add class to button (for CSS formatting)
      $('#myArticles').addClass('full');
});

simpleCart.bind( 'load' , function(){
   if (simpleCart.quantity() > 0) { 
      // add class to button (for CSS formatting)
      $('#myArticles').addClass('full');
   }
});

</script>
<script>
$(document).foundation();

var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);
</script>

</body>
</html>

<?php ob_end_flush(); ?>
