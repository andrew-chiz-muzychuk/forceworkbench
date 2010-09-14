<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Language" content="UTF-8" />
        <meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
        <link rel="stylesheet" href="style/master.css" type="text/css" />
        <link rel="stylesheet" href="style/pro_dropdown.css" type="text/css" />
        <link rel="Shortcut Icon" href="images/bluecube-16x16.png" />
        <link rel="stylesheet" type="text/css" href="style/simpletree.css" />
        <script type="text/javascript" src="script/simpletreemenu.js">
        /***********************************************
        * Simple Tree Menu - Dynamic Drive DHTML code library (www.dynamicdrive.com)
        * This notice MUST stay intact for legal use
        * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
        ***********************************************/
        </script>
        <?php
        $myPage = getMyPage();
        $title = $myPage->showTitle ? ": " . $myPage->title : "";
        print "<title>Workbench$title</title>"
        ?>

    </head>
<body>
<script type="text/javascript" src="script/wz_tooltip.js"></script>
<script type="text/javascript" src="script/pro_dropdown.js"></script>
<?php
if (isset($_SESSION['config']['areTablesSortable']) && $_SESSION['config']['areTablesSortable'] && (basename($_SERVER['PHP_SELF'])=="query.php" || basename($_SERVER['PHP_SELF'])=="search.php")) {
    print "<script type='text/javascript' src='script/sortable.js'></script>";
}

//check for latest version
if (!isset($_GET['skipVC']) && (isset($_GET['autoLogin']) || 'login.php'==basename($_SERVER['PHP_SELF']))) {
    try {
        if (extension_loaded('curl')) {
            $ch = curl_init();
            if (stristr($GLOBALS["WORKBENCH_VERSION"],'beta')) {
                curl_setopt ($ch, CURLOPT_URL, 'http://forceworkbench.sourceforge.net/latestVersionAvailableBeta.txt');
            } else {
                curl_setopt ($ch, CURLOPT_URL, 'http://forceworkbench.sourceforge.net/latestVersionAvailable.txt');
            }
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $latestVersionAvailable = trim(curl_exec($ch));
            curl_close($ch);

            if (preg_match('/^[0-9]+.[0-9]+/',$latestVersionAvailable) && !stristr($GLOBALS["WORKBENCH_VERSION"],'trunk') && !stristr($GLOBALS["WORKBENCH_VERSION"],'alpha') && !stristr($GLOBALS["WORKBENCH_VERSION"],'i')) {
                if ($latestVersionAvailable != $GLOBALS["WORKBENCH_VERSION"]) {
                    print "<div style='background-color: #EAE9E4; width: 100%; padding: 2px;'><a href='http://code.google.com/p/forceworkbench/' target='_blank' style='font-size: 8pt; font-weight: bold; color: #0046ad;'>A newer version of Workbench is available for download</a></div><br/>";
                }
            }
        }
    } catch (Exception $e) {
        //do nothing
    }
}
?>


<div id='mainBlock'>

<div id='navMenu' style="clear: both;">
    <span class="preload1"></span>
    <span class="preload2"></span>
    <ul id="nav">
    <?php
    foreach ($GLOBALS["MENUS"] as $menu => $pages) {
        if (isReadOnlyMode() && $menu == "Data") { //special-case for Data menu, since all read-only
            continue;
        }
        print "<li class='top'><a class='top_link'><span class='down'>" . strtolower($menu) ."</span></a>\n" .
                  "<ul class='sub'>";
        foreach ($pages as $href => $page) {
            if (!$page->onNavBar || (!isLoggedIn() && $page->requiresSfdcSession) || (isLoggedIn() && $page->title == 'Login') || (!$page->isReadOnly && isReadOnlyMode())) {
                continue;
            }
            print "<li><a href='$href' onmouseover=\"Tip('$page->desc')\" target=\"" . $page->window . "\">$page->title</a></li>\n";
        }
        print "</ul></li>";
    
        if(!isLoggedIn()) break; //only show first "Workbench" menu if not logged in
    }
    ?>
    </ul>
</div>

<?php
print "<table width='100%' border='0'><tr>";
if ($myPage->showTitle) {
    print "<td id='pageTitle'>" . $myPage->title . "</td>";
}
if (isLoggedIn()) {
    print "<td id='myUserInfo'><a href='sessionInfo.php'>" . $_SESSION['getUserInfo']->userFullName . " at " . $_SESSION['getUserInfo']->organizationName . " on API " . getApiVersion() . "</a></td>";
}
print "</tr></table>";

if (isset($errors)) {
    print "<p/>";
    displayError($errors, false, true);
}

?>